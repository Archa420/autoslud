<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $ads = $request->user()
            ->favoriteAds()
            ->with(['primaryImage', 'images', 'auction'])
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', compact('ads'));
    }

    public function store(Request $request, Ad $ad)
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'ad_id' => $ad->id,
        ]);

        return back()->with('success', 'Sludinājums pievienots favorītiem.');
    }

    public function destroy(Request $request, Ad $ad)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('ad_id', $ad->id)
            ->delete();

        return back()->with('success', 'Sludinājums noņemts no favorītiem.');
    }
}