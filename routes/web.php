<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\Ad;

Route::get('/', function (Request $request) {
    $q = $request->string('q')->toString();
    $type = $request->string('type')->toString();
    $priceFrom = $request->input('price_from');
    $priceTo = $request->input('price_to');

    $listings = Ad::query()
        ->with(['primaryImage', 'images', 'auction'])
        ->when($q, function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%");
            });
        })
        ->when($type === 'auction', fn ($query) => $query->whereHas('auction'))
        ->when($type === 'fixed', fn ($query) => $query->whereDoesntHave('auction'))
        ->when($priceFrom !== null && $priceFrom !== '', function ($query) use ($priceFrom) {
            $query->where(function ($qq) use ($priceFrom) {
                $qq->where(function ($q1) use ($priceFrom) {
                    $q1->whereDoesntHave('auction')->where('price', '>=', $priceFrom);
                })->orWhereHas('auction', function ($q2) use ($priceFrom) {
                    $q2->where(function ($auctionQuery) use ($priceFrom) {
                        $auctionQuery->where('current_bid', '>=', $priceFrom)
                            ->orWhere(function ($fallbackQuery) use ($priceFrom) {
                                $fallbackQuery->whereNull('current_bid')
                                    ->where('starting_bid', '>=', $priceFrom);
                            });
                    });
                });
            });
        })
        ->when($priceTo !== null && $priceTo !== '', function ($query) use ($priceTo) {
            $query->where(function ($qq) use ($priceTo) {
                $qq->where(function ($q1) use ($priceTo) {
                    $q1->whereDoesntHave('auction')->where('price', '<=', $priceTo);
                })->orWhereHas('auction', function ($q2) use ($priceTo) {
                    $q2->where(function ($auctionQuery) use ($priceTo) {
                        $auctionQuery->where('current_bid', '<=', $priceTo)
                            ->orWhere(function ($fallbackQuery) use ($priceTo) {
                                $fallbackQuery->whereNull('current_bid')
                                    ->where('starting_bid', '<=', $priceTo);
                            });
                    });
                });
            });
        })
        ->latest()
        ->take(12)
        ->get();

    return view('home', compact('listings'));
})->name('home');

Route::get('/izsoles', [AuctionController::class, 'index'])
    ->name('izsoles');

Route::get('/pardot-auto', function () {
    return redirect()->route('ads.create');
})->middleware('auth')->name('pardot-auto');

Route::prefix('sludinajumi')->group(function () {
    Route::get('/', [AdController::class, 'index'])
        ->name('ads.index');

    Route::middleware('auth')->group(function () {
        Route::get('/pievienot', [AdController::class, 'create'])
            ->name('ads.create');

        Route::post('/', [AdController::class, 'store'])
            ->name('ads.store');

        Route::get('/{ad}/labot', [AdController::class, 'edit'])
            ->whereNumber('ad')
            ->name('ads.edit');

        Route::patch('/{ad}', [AdController::class, 'update'])
            ->whereNumber('ad')
            ->name('ads.update');

        Route::delete('/{ad}', [AdController::class, 'destroy'])
            ->whereNumber('ad')
            ->name('ads.destroy');

        Route::post('/{ad}/zina', [MessageController::class, 'store'])
            ->whereNumber('ad')
            ->name('messages.store');
    });

    Route::get('/{ad}', [AdController::class, 'show'])
        ->whereNumber('ad')
        ->name('ads.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        $ads = $user
            ->ads()
            ->with(['primaryImage', 'images', 'auction'])
            ->latest()
            ->paginate(12);

        $userBids = $user
            ->bids()
            ->with([
                'auction.ad.primaryImage',
                'auction.ad.images',
                'auction.highestBid',
            ])
            ->latest()
            ->get()
            ->groupBy('auction_id');

        return view('dashboard', compact('ads', 'userBids'));
    })->name('dashboard');

    Route::post('/izsoles/{auction}/bid', [BidController::class, 'store'])
        ->whereNumber('auction')
        ->name('bids.store');

    Route::delete('/izsoles/{auction}/bid', [BidController::class, 'destroy'])
        ->whereNumber('auction')
        ->name('bids.destroy');

    Route::get('/favoriti', [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favoriti/{ad}', [FavoriteController::class, 'store'])
        ->whereNumber('ad')
        ->name('favorites.store');

    Route::delete('/favoriti/{ad}', [FavoriteController::class, 'destroy'])
        ->whereNumber('ad')
        ->name('favorites.destroy');

    Route::get('/zinas', [MessageController::class, 'index'])
        ->name('messages.index');

    Route::get('/zinas/{message}', [MessageController::class, 'show'])
        ->whereNumber('message')
        ->name('messages.show');

    Route::post('/zinas/{message}/atbildet', [MessageController::class, 'reply'])
        ->whereNumber('message')
        ->name('messages.reply');

    Route::prefix('admin')->group(function () {
        Route::get('/lietotaji', [AdminController::class, 'users'])
            ->name('admin.users');

        Route::patch('/lietotaji/{user}/bloket', [AdminController::class, 'toggleBlock'])
            ->whereNumber('user')
            ->name('admin.users.toggle-block');

        Route::get('/sludinajumi', [AdminController::class, 'ads'])
            ->name('admin.ads');

        Route::delete('/sludinajumi/{ad}', [AdminController::class, 'destroyAd'])
            ->whereNumber('ad')
            ->name('admin.ads.destroy');

        Route::get('/izsoles', [AdminController::class, 'auctions'])
            ->name('admin.auctions');

        Route::patch('/izsoles/{auction}/status', [AdminController::class, 'updateAuctionStatus'])
            ->whereNumber('auction')
            ->name('admin.auctions.status');

        Route::delete('/izsoles/{auction}', [AdminController::class, 'destroyAuction'])
            ->whereNumber('auction')
            ->name('admin.auctions.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';