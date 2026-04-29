<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private function checkAdmin(Request $request): void
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function users(Request $request)
    {
        $this->checkAdmin($request);

        $users = User::query()
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggleBlock(Request $request, User $user)
    {
        $this->checkAdmin($request);

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Tu nevari bloķēt pats sevi.');
        }

        $user->update([
            'is_blocked' => !$user->is_blocked,
        ]);

        return back()->with('status', 'Lietotāja statuss atjaunināts.');
    }

    public function ads(Request $request)
    {
        $this->checkAdmin($request);

        $ads = Ad::query()
            ->with(['user', 'primaryImage', 'images', 'auction'])
            ->latest()
            ->paginate(20);

        return view('admin.ads', compact('ads'));
    }

    public function destroyAd(Request $request, Ad $ad)
    {
        $this->checkAdmin($request);

        $ad->delete();

        return back()->with('status', 'Sludinājums izdzēsts.');
    }

    public function auctions(Request $request)
    {
        $this->checkAdmin($request);

        $auctions = Auction::query()
            ->with(['ad.user', 'winner'])
            ->latest()
            ->paginate(20);

        return view('admin.auctions', compact('auctions'));
    }

    public function updateAuctionStatus(Request $request, Auction $auction)
    {
        $this->checkAdmin($request);

        $data = $request->validate([
            'status' => ['required', Rule::in(['active', 'finished', 'cancelled'])],
        ]);

        $auction->update([
            'status' => $data['status'],
        ]);

        return back()->with('status', 'Izsoles statuss atjaunināts.');
    }

    public function destroyAuction(Request $request, Auction $auction)
    {
        $this->checkAdmin($request);

        $auction->delete();

        return back()->with('status', 'Izsole izdzēsta.');
    }
}