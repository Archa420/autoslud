<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $user = Auth::user();

        if (! $user->hasAuctionSubscription()) {
            return redirect()
                ->route('auction-subscription.index')
                ->with('error', 'Lai piedalītos izsolēs, nepieciešams aktīvs izsoļu abonements.');
        }

        $auction->loadMissing('ad');

        if ($auction->ad && $auction->ad->user_id === $user->id) {
            return back()->with('error', 'Tu nevari solīt savā izsolē.');
        }

        if ($auction->status !== 'active') {
            return back()->with('error', 'Šī izsole vairs nav aktīva.');
        }

        if ($auction->starts_at && now()->lessThan($auction->starts_at)) {
            return back()->with('error', 'Šī izsole vēl nav sākusies.');
        }

        if ($auction->ends_at && now()->greaterThanOrEqualTo($auction->ends_at)) {
            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            $auction->update([
                'status' => 'finished',
                'winner_user_id' => $highestBid?->user_id,
                'current_bid' => $highestBid?->amount ?? $auction->starting_bid,
            ]);

            return back()->with('error', 'Šī izsole jau ir beigusies.');
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ], [
            'amount.required' => 'Lūdzu ievadi solījuma summu.',
            'amount.numeric' => 'Solījuma summai jābūt skaitlim.',
            'amount.min' => 'Solījuma summai jābūt lielākai par 0.',
        ]);

        try {
            DB::transaction(function () use ($request, $auction, $user) {
                $auction = Auction::with('ad')
                    ->where('id', $auction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($auction->status !== 'active') {
                    throw new \Exception('Šī izsole vairs nav aktīva.');
                }

                if ($auction->ad && $auction->ad->user_id === $user->id) {
                    throw new \Exception('Tu nevari solīt savā izsolē.');
                }

                if ($auction->ends_at && now()->greaterThanOrEqualTo($auction->ends_at)) {
                    $highestBid = Bid::where('auction_id', $auction->id)
                        ->orderByDesc('amount')
                        ->first();

                    $auction->update([
                        'status' => 'finished',
                        'winner_user_id' => $highestBid?->user_id,
                        'current_bid' => $highestBid?->amount ?? $auction->starting_bid,
                    ]);

                    throw new \Exception('Šī izsole jau ir beigusies.');
                }

                $startingBid = (float) ($auction->starting_bid ?? 0);

                $highestBidAmount = (float) Bid::where('auction_id', $auction->id)
                    ->max('amount');

                $currentBid = max($startingBid, $highestBidAmount);

                $minimumStep = (float) ($auction->minimum_bid_step ?? 1);
                $minimumAllowedBid = $currentBid + $minimumStep;

                $bidAmount = (float) $request->amount;

                if ($bidAmount < $minimumAllowedBid) {
                    throw new \Exception(
                        'Solījumam jābūt vismaz ' . number_format($minimumAllowedBid, 2, '.', ' ') . ' €.'
                    );
                }

                if ($auction->buyout_price && $bidAmount > (float) $auction->buyout_price) {
                    throw new \Exception(
                        'Solījums nedrīkst pārsniegt izpirkuma cenu ' . number_format((float) $auction->buyout_price, 2, '.', ' ') . ' €.'
                    );
                }

                Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => $user->id,
                    'amount' => $bidAmount,
                ]);

                $updateData = [
                    'current_bid' => $bidAmount,
                ];

                if ($auction->buyout_price && $bidAmount >= (float) $auction->buyout_price) {
                    $updateData['status'] = 'finished';
                    $updateData['winner_user_id'] = $user->id;
                }

                $auction->update($updateData);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($auction->buyout_price && (float) $request->amount >= (float) $auction->buyout_price) {
            return back()->with('success', 'Izpirkuma piedāvājums iesniegts. Pārdevējs var ar tevi sazināties.');
        }

        return back()->with('success', 'Solījums veiksmīgi pievienots!');
    }

    public function destroy(Auction $auction)
    {
        $user = Auth::user();

        if ($auction->status !== 'active') {
            return back()->with('error', 'Šī izsole vairs nav aktīva, tāpēc likmi nevar noņemt.');
        }

        if ($auction->ends_at && now()->greaterThanOrEqualTo($auction->ends_at)) {
            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            $auction->update([
                'status' => 'finished',
                'winner_user_id' => $highestBid?->user_id,
                'current_bid' => $highestBid?->amount ?? $auction->starting_bid,
            ]);

            return back()->with('error', 'Šī izsole jau ir beigusies, tāpēc likmi nevar noņemt.');
        }

        try {
            DB::transaction(function () use ($auction, $user) {
                $auction = Auction::where('id', $auction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $deleted = Bid::where('auction_id', $auction->id)
                    ->where('user_id', $user->id)
                    ->delete();

                if ($deleted === 0) {
                    throw new \Exception('Tev nav likmes šajā izsolē.');
                }

                $highestBid = Bid::where('auction_id', $auction->id)
                    ->orderByDesc('amount')
                    ->first();

                $auction->update([
                    'current_bid' => $highestBid?->amount ?? $auction->starting_bid,
                    'winner_user_id' => null,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Tava likme tika noņemta.');
    }
}