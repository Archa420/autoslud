<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuctionSubscriptionController extends Controller
{
    public function index()
    {
        return view('auction-subscription.index');
    }

    public function checkout(Request $request)
    {
        if ($request->user()->subscribed('auction_access')) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Tev jau ir aktīvs izsoļu abonements.');
        }

        return $request->user()
            ->newSubscription('auction_access', config('services.stripe.auction_access_price'))
            ->checkout([
                'success_url' => route('auction-subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('auction-subscription.cancel'),
            ]);
    }

    public function success(Request $request)
    {
        return view('auction-subscription.success');
    }

    public function cancel()
    {
        return view('auction-subscription.cancel');
    }
}