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
        if ($auction->status !== 'active') {
            return back()->with('error', 'Šī izsole vairs nav aktīva.');
        }

        if ($auction->starts_at && now()->lessThan($auction->starts_at)) {
            return back()->with('error', 'Šī izsole vēl nav sākusies.');
        }

        if ($auction->ends_at && now()->greaterThanOrEqualTo($auction->ends_at)) {
            $auction->update([
                'status' => 'finished',
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
            DB::transaction(function () use ($request, $auction) {
                $auction = Auction::where('id', $auction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $startingBid = (float) ($auction->starting_bid ?? 0);

                $highestBid = (float) Bid::where('auction_id', $auction->id)
                    ->max('amount');

                $currentBid = max($startingBid, $highestBid);

                $minimumStep = (float) ($auction->minimum_bid_step ?? 1);
                $minimumAllowedBid = $currentBid + $minimumStep;

                $bidAmount = (float) $request->amount;

                if ($bidAmount < $minimumAllowedBid) {
                    throw new \Exception(
                        'Solījumam jābūt vismaz ' . number_format($minimumAllowedBid, 2, '.', ' ') . ' €.'
                    );
                }

                Bid::create([
                    'auction_id' => $auction->id,
                    'user_id' => Auth::id(),
                    'amount' => $bidAmount,
                ]);

                $auction->update([
                    'current_bid' => $bidAmount,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Solījums veiksmīgi pievienots!');
    }
}