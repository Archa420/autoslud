@extends('layouts.app')

@section('content')
@php
    $images = $ad->images->sortBy('sort_order')->values();
    $imageSources = $images->map(fn ($image) => asset('storage/' . $image->path))->values();
    $firstSrc = $imageSources->first();

    $auction = $ad->auction;
    $isAuction = $auction !== null;

    $startingBid = $isAuction
        ? (float) ($auction->starting_bid ?? 0)
        : null;

    $highestBidModel = $isAuction ? $auction->highestBid : null;

    $highestBid = $isAuction
        ? (float) ($highestBidModel?->amount ?? $auction->current_bid ?? 0)
        : null;

    $currentBid = $isAuction
        ? max($startingBid, $highestBid)
        : null;

    $minimumStep = $isAuction
        ? (float) ($auction->minimum_bid_step ?? 1)
        : 1;

    $minimumAllowedBid = $isAuction
        ? $currentBid + $minimumStep
        : null;

    $buyoutPrice = $isAuction && $auction->buyout_price
        ? (float) $auction->buyout_price
        : null;

    $isFavorite = auth()->check()
        ? $ad->isFavoritedBy(auth()->user())
        : false;

    $isSeller = auth()->check() && auth()->id() === $ad->user_id;
    $isHighestBidder = auth()->check() && $highestBidModel && $highestBidModel->user_id === auth()->id();
    $isWinner = auth()->check() && $isAuction && $auction->winner_user_id === auth()->id();

    $fuelMap = [
        'diesel' => 'Dīzelis',
        'petrol' => 'Benzīns',
        'petrol_lpg' => 'Benzīns + gāze',
        'hybrid' => 'Hibrīds',
        'electric' => 'Elektriskais',
    ];

    $gearboxMap = [
        'manual' => 'Manuālā',
        'automatic' => 'Automāts',
    ];
@endphp

<div class="mx-auto max-w-6xl text-white">
    <a href="{{ route('ads.index') }}"
       class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
        ← Atpakaļ
    </a>

    @if(session('status'))
        <div class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-4 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    @if($isAuction && $isHighestBidder && $auction->status === 'active')
        <div class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-300">
            Tu šobrīd esi augstākais solītājs.
        </div>
    @endif

    @if($isAuction && $isWinner && $auction->status === 'finished')
        <div class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-300">
            Tu uzvarēji šo izsoli. Pārdevējs var ar tevi sazināties, lai vienotos par darījumu.
        </div>
    @endif

    <div class="mt-6 grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
        <div
            x-data="{
                images: @js($imageSources),
                activeIndex: 0,
                lightboxOpen: false,

                get activeImage() {
                    return this.images[this.activeIndex] ?? null;
                },

                setActive(index) {
                    this.activeIndex = index;
                },

                openLightbox(index = this.activeIndex) {
                    if (!this.images.length) return;
                    this.activeIndex = index;
                    this.lightboxOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                    document.body.classList.remove('overflow-hidden');
                },

                nextImage() {
                    if (!this.images.length) return;
                    this.activeIndex = (this.activeIndex + 1) % this.images.length;
                },

                previousImage() {
                    if (!this.images.length) return;
                    this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                }
            }"
            @keydown.escape.window="closeLightbox()"
            @keydown.arrow-right.window="lightboxOpen && nextImage()"
            @keydown.arrow-left.window="lightboxOpen && previousImage()"
            class="space-y-4"
        >
            <div class="aspect-[4/3] w-full overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-2xl shadow-black/25">
                @if($firstSrc)
                    <button
                        type="button"
                        class="block h-full w-full cursor-zoom-in"
                        @click="openLightbox(activeIndex)"
                    >
                        <img :src="activeImage" alt="Sludinājuma bilde" class="h-full w-full object-cover opacity-95 transition duration-700 hover:scale-105 hover:opacity-100" loading="lazy">
                    </button>
                @else
                    <div class="flex h-full w-full items-center justify-center text-slate-500">
                        Nav attēlu
                    </div>
                @endif
            </div>

            @if($images->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-1">
                    @foreach($imageSources as $index => $src)
                        <button
                            type="button"
                            class="shrink-0 overflow-hidden rounded-2xl border transition hover:border-amber-400/50"
                            :class="activeIndex === {{ $index }} ? 'border-amber-400 ring-2 ring-amber-400/20' : 'border-white/10'"
                            @click="setActive({{ $index }})"
                            style="width: 88px; height: 66px;"
                            aria-label="Atvērt bildi"
                        >
                            <img src="{{ $src }}" alt="" class="h-full w-full object-cover opacity-90" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif

            @if($ad->description)
                <div class="rounded-3xl border border-white/10 bg-white/[.05] p-5 shadow-xl shadow-black/20 backdrop-blur-xl">
                    <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                        style="font-family:'Bebas Neue', sans-serif;">
                        Apraksts
                    </h2>

                    <div class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-400">
                        {{ $ad->description }}
                    </div>
                </div>
            @endif

            <div
                x-show="lightboxOpen"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                @click.self="closeLightbox()"
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-3xl font-light text-white transition hover:bg-white/20"
                    @click="closeLightbox()"
                    aria-label="Aizvērt"
                >
                    ×
                </button>

                <button
                    type="button"
                    class="absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-4xl text-white transition hover:bg-white/20"
                    @click.stop="previousImage()"
                    x-show="images.length > 1"
                    aria-label="Iepriekšējā bilde"
                >
                    ‹
                </button>

                <img
                    :src="activeImage"
                    alt="Sludinājuma bilde"
                    class="max-h-[85vh] max-w-[92vw] rounded-2xl object-contain shadow-2xl"
                >

                <button
                    type="button"
                    class="absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-4xl text-white transition hover:bg-white/20"
                    @click.stop="nextImage()"
                    x-show="images.length > 1"
                    aria-label="Nākamā bilde"
                >
                    ›
                </button>

                <div
                    class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white"
                    x-show="images.length > 1"
                >
                    <span x-text="activeIndex + 1"></span>/<span x-text="images.length"></span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-amber-200/80">
                            {{ $isAuction ? ($auction->status === 'finished' ? 'Pabeigta izsole' : 'Izsole') : 'Pārdošana' }}
                        </span>
                    </div>

                    <h1 class="text-4xl font-black uppercase leading-tight text-white md:text-5xl"
                        style="font-family:'Bebas Neue', sans-serif;">
                        {{ $ad->title }}
                    </h1>
                </div>
            </div>

            <p class="mt-2 text-sm text-slate-400">
                {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
            </p>

            <div class="mt-5">
                @auth
                    @if($isFavorite)
                        <form action="{{ route('favorites.destroy', $ad) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="w-full rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-300 transition hover:bg-red-500/20"
                            >
                                Noņemt no favorītiem
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $ad) }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white"
                            >
                                Pievienot favorītiem
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex w-full items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Pieslēdzies, lai pievienotu favorītiem
                    </a>
                @endauth
            </div>

            @if($isAuction)
                <div class="mt-5 rounded-3xl border border-amber-400/20 bg-amber-400/10 p-4">
                    <div class="text-xs font-black uppercase tracking-wider text-amber-300">
                        Pašreizējā izsoles cena
                    </div>

                    <div class="mt-1 text-5xl font-black leading-none text-white"
                         style="font-family:'Bebas Neue', sans-serif;">
                        {{ number_format($currentBid, 2, '.', ' ') }} €
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-sm text-slate-400 sm:grid-cols-2">
                        <p class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <span class="block text-xs text-slate-500">Sākumcena</span>
                            <span class="font-bold text-white">
                                {{ number_format($startingBid, 2, '.', ' ') }} €
                            </span>
                        </p>

                        <p class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <span class="block text-xs text-slate-500">Min. solis</span>
                            <span class="font-bold text-white">
                                {{ number_format($minimumStep, 2, '.', ' ') }} €
                            </span>
                        </p>

                        @if($buyoutPrice)
                            <p class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2 sm:col-span-2">
                                <span class="block text-xs text-slate-500">Izpirkuma piedāvājuma cena</span>
                                <span class="font-bold text-white">
                                    {{ number_format($buyoutPrice, 2, '.', ' ') }} €
                                </span>
                            </p>
                        @endif

                        @if($auction->status === 'active')
                            <p class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2 sm:col-span-2">
                                <span class="block text-xs text-slate-500">Nākamais minimālais solījums</span>
                                <span class="font-bold text-white">
                                    {{ number_format($minimumAllowedBid, 2, '.', ' ') }} €
                                </span>
                            </p>
                        @endif

                        <p class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2 sm:col-span-2">
                            <span class="block text-xs text-slate-500">Beidzas</span>
                            <span class="font-bold text-white">
                                {{ $auction->ends_at?->format('d.m.Y H:i') }}
                            </span>
                        </p>
                    </div>

                    @if($isSeller)
                        <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4 text-sm text-slate-300">
                            <h3 class="font-black uppercase tracking-wider text-white">
                                Izsoles informācija pārdevējam
                            </h3>

                            @if($highestBidModel)
                                <div class="mt-3 space-y-1">
                                    <p>
                                        Augstākais solītājs:
                                        <span class="font-bold text-white">
                                            {{ $highestBidModel->user?->name }}
                                        </span>
                                    </p>

                                    <p>
                                        E-pasts:
                                        <span class="font-bold text-white">
                                            {{ $highestBidModel->user?->email }}
                                        </span>
                                    </p>

                                    <p>
                                        Solījums:
                                        <span class="font-bold text-white">
                                            {{ number_format($highestBidModel->amount, 2, '.', ' ') }} €
                                        </span>
                                    </p>
                                </div>
                            @else
                                <p class="mt-3 text-slate-400">
                                    Šai izsolei vēl nav solījumu.
                                </p>
                            @endif
                        </div>
                    @endif

                    @if($auction->status === 'active' && $auction->ends_at && now()->lessThan($auction->ends_at))
                        @auth
                            @if($isSeller)
                                <p class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4 text-sm text-slate-400">
                                    Tu nevari solīt savā izsolē.
                                </p>
                            @elseif(! auth()->user()->hasAuctionSubscription())
                                <div class="mt-4 rounded-2xl border border-amber-400/20 bg-slate-950/50 p-4">
                                    <p class="text-sm text-slate-300">
                                        Lai piedalītos izsolēs, nepieciešams aktīvs izsoļu abonements.
                                    </p>

                                    <a href="{{ route('auction-subscription.index') }}"
                                       class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                                        Aktivizēt abonementu
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('bids.store', $auction) }}" method="POST" class="mt-4">
                                    @csrf

                                    <label for="amount_{{ $auction->id }}" class="block text-sm font-bold text-slate-300">
                                        Tavs solījums
                                    </label>

                                    <input
                                        type="number"
                                        name="amount"
                                        id="amount_{{ $auction->id }}"
                                        step="{{ number_format($minimumStep, 2, '.', '') }}"
                                        min="{{ number_format($minimumAllowedBid, 2, '.', '') }}"
                                        @if($buyoutPrice) max="{{ number_format($buyoutPrice, 2, '.', '') }}" @endif
                                        placeholder="{{ number_format($minimumAllowedBid, 2, '.', '') }}"
                                        required
                                        class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder:text-slate-500 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                                    >

                                    <button
                                        type="submit"
                                        class="mt-3 w-full rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95"
                                    >
                                        Iesniegt solījumu
                                    </button>
                                </form>

                                @if($buyoutPrice)
                                    <form action="{{ route('bids.store', $auction) }}" method="POST" class="mt-3">
                                        @csrf

                                        <input type="hidden" name="amount" value="{{ number_format($buyoutPrice, 2, '.', '') }}">

                                        <button
                                            type="submit"
                                            class="w-full rounded-2xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 text-sm font-black uppercase tracking-wider text-amber-300 transition hover:bg-amber-400 hover:text-slate-950 active:scale-95"
                                        >
                                            Iesniegt izpirkuma piedāvājumu
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4">
                                <p class="text-sm text-slate-400">
                                    Lai piedalītos izsolē, nepieciešams pieslēgties.
                                </p>

                                <a href="{{ route('login') }}"
                                   class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                                    Pieslēgties
                                </a>
                            </div>
                        @endauth
                    @else
                        <p class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4 text-sm text-slate-400">
                            Šī izsole nav aktīva vai jau ir beigusies.
                        </p>
                    @endif
                </div>
            @else
                <div class="mt-5 rounded-3xl border border-white/10 bg-slate-950/50 p-4">
                    <div class="text-xs font-black uppercase tracking-wider text-slate-500">
                        Cena
                    </div>

                    <div class="mt-1 text-5xl font-black leading-none text-white"
                         style="font-family:'Bebas Neue', sans-serif;">
                        {{ $ad->price !== null ? number_format($ad->price, 2, '.', ' ') . ' €' : '—' }}
                    </div>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Motora tilpums</div>
                    <div class="font-bold text-white">{{ $ad->engine_cc }} cc</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Degvielas tips</div>
                    <div class="font-bold text-white">{{ $fuelMap[$ad->fuel_type] ?? $ad->fuel_type }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Kārba</div>
                    <div class="font-bold text-white">
                        {{ $gearboxMap[$ad->gearbox_type] ?? $ad->gearbox_type }} · {{ $ad->gears_count }}
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Durvis</div>
                    <div class="font-bold text-white">{{ $ad->doors_count }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Virsbūves tips</div>
                    <div class="font-bold text-white">{{ $ad->body_type ?: 'Nav norādīts' }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Krāsa</div>
                    <div class="font-bold text-white">{{ $ad->color }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Atrašanās vieta</div>
                    <div class="font-bold text-white">{{ $ad->location ?: 'Nav norādīts' }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-3">
                    <div class="text-xs text-slate-500">Kontakti</div>
                    <div class="font-bold text-white">{{ $ad->contacts ?: 'Nav norādīts' }}</div>
                </div>
            </div>

            @auth
                @if(auth()->id() !== $ad->user_id)
                    <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/50 p-4">
                        <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                            style="font-family:'Bebas Neue', sans-serif;">
                            Nosūtīt ziņu pārdevējam
                        </h2>

                        <form method="POST" action="{{ route('messages.store', $ad) }}" class="mt-3">
                            @csrf

                            <textarea
                                name="content"
                                rows="4"
                                required
                                class="w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                                placeholder="Piemēram: Vai auto vēl ir pieejams?"
                            >{{ old('content') }}</textarea>

                            @error('content')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror

                            <button
                                type="submit"
                                class="mt-3 w-full rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95"
                            >
                                Nosūtīt ziņu
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/50 p-4">
                    <p class="text-sm text-slate-400">
                        Lai nosūtītu ziņu pārdevējam, nepieciešams pieslēgties.
                    </p>

                    <a href="{{ route('login') }}"
                       class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                        Pieslēgties
                    </a>
                </div>
            @endauth

            @if(auth()->check() && auth()->id() === $ad->user_id)
                <div class="mt-5 flex gap-3">
                    <a href="{{ route('ads.edit', $ad) }}"
                       class="flex-1 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-center text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Labot sludinājumu
                    </a>

                    <form method="POST"
                          action="{{ route('ads.destroy', $ad) }}"
                          class="flex-1"
                          onsubmit="return confirm('Vai tiešām vēlies dzēst šo sludinājumu?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="w-full rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-300 transition hover:bg-red-500/20">
                            Dzēst
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection