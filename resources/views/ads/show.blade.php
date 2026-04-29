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

    $highestBid = $isAuction
        ? (float) ($auction->highestBid?->amount ?? $auction->current_bid ?? 0)
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

    $isFavorite = auth()->check()
        ? $ad->isFavoritedBy(auth()->user())
        : false;

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

<div class="mx-auto max-w-5xl">
    <a href="{{ route('ads.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
        ← Atpakaļ
    </a>

    @if(session('status'))
        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
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
            <div class="aspect-[4/3] w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                @if($firstSrc)
                    <button
                        type="button"
                        class="block h-full w-full cursor-zoom-in"
                        @click="openLightbox(activeIndex)"
                    >
                        <img :src="activeImage" alt="Sludinājuma bilde" class="h-full w-full object-cover" loading="lazy">
                    </button>
                @else
                    <div class="flex h-full w-full items-center justify-center text-slate-400">
                        Nav attēlu
                    </div>
                @endif
            </div>

            @if($images->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-1">
                    @foreach($imageSources as $index => $src)
                        <button
                            type="button"
                            class="shrink-0 overflow-hidden rounded-xl border transition hover:border-slate-400"
                            :class="activeIndex === {{ $index }} ? 'border-slate-900 ring-2 ring-slate-200' : 'border-slate-200'"
                            @click="setActive({{ $index }})"
                            style="width: 88px; height: 66px;"
                            aria-label="Atvērt bildi"
                        >
                            <img src="{{ $src }}" alt="" class="h-full w-full object-cover" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif

            @if($ad->description)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-950">
                        Apraksts
                    </h2>

                    <div class="mt-3 text-sm leading-7 text-slate-700 whitespace-pre-line">
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

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-900">
                    {{ $ad->title }}
                </h1>

                @if($isAuction)
                    <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                        Izsole
                    </span>
                @endif
            </div>

            <p class="mt-2 text-sm text-slate-600">
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
                                class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-100"
                            >
                                Noņemt no favorītiem
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $ad) }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-50"
                            >
                                Pievienot favorītiem
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-50">
                        Pieslēdzies, lai pievienotu favorītiem
                    </a>
                @endauth
            </div>

            @if($isAuction)
                <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <div class="text-sm font-semibold text-amber-700">
                        Pašreizējā izsoles cena
                    </div>

                    <div class="mt-1 text-3xl font-bold text-slate-900">
                        {{ number_format($currentBid, 2, '.', ' ') }} €
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600 sm:grid-cols-2">
                        <p>
                            Sākumcena:
                            <span class="font-semibold text-slate-900">
                                {{ number_format($startingBid, 2, '.', ' ') }} €
                            </span>
                        </p>

                        <p>
                            Min. solis:
                            <span class="font-semibold text-slate-900">
                                {{ number_format($minimumStep, 2, '.', ' ') }} €
                            </span>
                        </p>

                        <p class="sm:col-span-2">
                            Nākamais minimālais solījums:
                            <span class="font-semibold text-slate-900">
                                {{ number_format($minimumAllowedBid, 2, '.', ' ') }} €
                            </span>
                        </p>

                        <p class="sm:col-span-2">
                            Beidzas:
                            <span class="font-semibold text-slate-900">
                                {{ $auction->ends_at?->format('d.m.Y H:i') }}
                            </span>
                        </p>
                    </div>

                    @if($auction->status === 'active' && $auction->ends_at && now()->lessThan($auction->ends_at))
                        @auth
                            <form action="{{ route('bids.store', $auction) }}" method="POST" class="mt-4">
                                @csrf

                                <label for="amount_{{ $auction->id }}" class="block text-sm font-semibold text-slate-700">
                                    Tavs solījums
                                </label>

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount_{{ $auction->id }}"
                                    step="0.01"
                                    min="{{ number_format($minimumAllowedBid, 2, '.', '') }}"
                                    placeholder="{{ number_format($minimumAllowedBid, 2, '.', '') }}"
                                    required
                                    class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                                >

                                <button
                                    type="submit"
                                    class="mt-3 w-full rounded-xl bg-amber-400 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300"
                                >
                                    Solīt
                                </button>
                            </form>
                        @else
                            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                                <p class="text-sm text-slate-600">
                                    Lai piedalītos izsolē, nepieciešams pieslēgties.
                                </p>

                                <a href="{{ route('login') }}"
                                   class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-amber-400 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300">
                                    Pieslēgties
                                </a>
                            </div>
                        @endauth
                    @else
                        <p class="mt-4 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                            Šī izsole nav aktīva vai jau ir beigusies.
                        </p>
                    @endif
                </div>
            @else
                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-sm font-semibold text-slate-600">
                        Cena
                    </div>

                    <div class="mt-1 text-3xl font-bold text-slate-900">
                        {{ $ad->price !== null ? number_format($ad->price, 2, '.', ' ') . ' €' : '—' }}
                    </div>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Motora tilpums</div>
                    <div class="font-semibold text-slate-900">{{ $ad->engine_cc }} cc</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Degvielas tips</div>
                    <div class="font-semibold text-slate-900">{{ $fuelMap[$ad->fuel_type] ?? $ad->fuel_type }}</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Kārba</div>
                    <div class="font-semibold text-slate-900">
                        {{ $gearboxMap[$ad->gearbox_type] ?? $ad->gearbox_type }} · {{ $ad->gears_count }}
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Durvis</div>
                    <div class="font-semibold text-slate-900">{{ $ad->doors_count }}</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Virsbūves tips</div>
                    <div class="font-semibold text-slate-900">{{ $ad->body_type ?: 'Nav norādīts' }}</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Krāsa</div>
                    <div class="font-semibold text-slate-900">{{ $ad->color }}</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Atrašanās vieta</div>
                    <div class="font-semibold text-slate-900">{{ $ad->location ?: 'Nav norādīts' }}</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="text-xs text-slate-500">Kontakti</div>
                    <div class="font-semibold text-slate-900">{{ $ad->contacts ?: 'Nav norādīts' }}</div>
                </div>
            </div>

            @auth
                @if(auth()->id() !== $ad->user_id)
                    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <h2 class="text-base font-semibold text-slate-950">
                            Nosūtīt ziņu pārdevējam
                        </h2>

                        <form method="POST" action="{{ route('messages.store', $ad) }}" class="mt-3">
                            @csrf

                            <textarea
                                name="content"
                                rows="4"
                                required
                                class="w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                                placeholder="Piemēram: Vai auto vēl ir pieejams?"
                            >{{ old('content') }}</textarea>

                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <button
                                type="submit"
                                class="mt-3 w-full rounded-xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800"
                            >
                                Nosūtīt ziņu
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">
                        Lai nosūtītu ziņu pārdevējam, nepieciešams pieslēgties.
                    </p>

                    <a href="{{ route('login') }}"
                       class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Pieslēgties
                    </a>
                </div>
            @endauth

            @if(auth()->check() && auth()->id() === $ad->user_id)
                <div class="mt-5 flex gap-3">
                    <a href="{{ route('ads.edit', $ad) }}"
                       class="flex-1 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-center text-sm font-bold text-amber-700 transition hover:bg-amber-100">
                        Labot sludinājumu
                    </a>

                    <form method="POST"
                          action="{{ route('ads.destroy', $ad) }}"
                          class="flex-1"
                          onsubmit="return confirm('Vai tiešām vēlies dzēst šo sludinājumu?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-100">
                            Dzēst
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection