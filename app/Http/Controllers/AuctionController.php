<?php

namespace App\Http\Controllers;

use App\Models\Auction;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::query()
            ->with([
                'ad.primaryImage',
                'ad.images',
                'bids.user',
                'highestBid.user',
            ])
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest('ends_at')
            ->get();

        return view('izsoles', compact('auctions'));
    }
}