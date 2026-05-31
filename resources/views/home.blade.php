@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

<div class="-mx-4 -my-6 min-h-screen overflow-hidden bg-slate-950 px-4 py-8 text-white sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8" style="font-family:'DM Sans',sans-serif;">

    <div class="pointer-events-none fixed inset-0 z-0">
        <div class="absolute left-1/2 top-[-22rem] h-[42rem] w-[42rem] -translate-x-1/2 rounded-full bg-amber-400/10 blur-3xl"></div>
        <div class="absolute right-[-18rem] top-32 h-[36rem] w-[36rem] rounded-full bg-blue-700/25 blur-3xl"></div>
        <div class="absolute bottom-[-16rem] left-[-12rem] h-[34rem] w-[34rem] rounded-full bg-slate-700/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.025)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.025)_1px,transparent_1px)] bg-[size:48px_48px]"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <section class="relative mb-20 pt-8 md:pt-16">

            <div class="pointer-events-none absolute right-[-3rem] top-8 select-none text-[20vw] font-black leading-none text-white/[.035]"
                 style="font-family:'Bebas Neue',sans-serif;">
                AUTO
            </div>

            <div class="relative grid gap-12 lg:grid-cols-[1fr_320px] lg:items-end">

                <div>
                    <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-400"></span>
                        <span class="text-xs font-semibold uppercase tracking-[.16em] text-amber-100/70">
                            Latvijas auto platforma
                        </span>
                    </div>

                    <h1 class="max-w-4xl text-[clamp(4rem,12vw,10rem)] font-black uppercase leading-[.85] tracking-tight text-white"
                        style="font-family:'Bebas Neue',sans-serif;">
                        Atrodi
                        <span class="bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent">savu</span>
                        auto.
                    </h1>

                    <p class="mt-7 max-w-xl text-base font-light leading-relaxed text-slate-300/70">
                        Lietoti auto, izsoles un privātsludinājumi — ātri, pārskatāmi un bez lieka trokšņa.
                    </p>

                    <form action="{{ route('ads.index') }}" method="GET" class="mt-9">
                        <div class="flex max-w-2xl items-center overflow-hidden rounded-2xl border border-white/10 bg-white/[.06] p-1.5 shadow-2xl shadow-black/30 backdrop-blur-md">
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Marka, modelis vai atslēgvārds…"
                                class="flex-1 border-0 bg-transparent px-5 py-4 text-sm text-white placeholder-white/30 outline-none ring-0 focus:border-0 focus:ring-0"
                            >

                            <button type="submit"
                                class="shrink-0 rounded-xl bg-amber-400 px-6 py-3 text-sm font-black uppercase tracking-widest text-neutral-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-300 active:scale-95"
                                style="font-family:'Bebas Neue',sans-serif;">
                                Meklēt
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-2 gap-4 lg:grid-cols-1">
                    <div class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-xl shadow-black/20 backdrop-blur-md">
                        <p class="text-6xl font-black leading-none text-white" style="font-family:'Bebas Neue',sans-serif;">
                            {{ $listings->count() }}
                        </p>
                        <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                            Sludinājumi
                        </p>
                    </div>

                    <div class="rounded-3xl border border-amber-400/20 bg-amber-400/10 p-6 shadow-xl shadow-black/20 backdrop-blur-md">
                        <p class="text-6xl font-black leading-none text-amber-400" style="font-family:'Bebas Neue',sans-serif;">
                            24/7
                        </p>
                        <p class="mt-2 text-xs font-bold uppercase tracking-widest text-amber-100/50">
                            Pieejams
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <div class="mb-8 flex items-center gap-4">
            <h2 class="text-xs font-bold uppercase tracking-[.22em] text-slate-400">
                Jaunākie sludinājumi
            </h2>
            <div class="h-px flex-1 bg-gradient-to-r from-white/15 to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($listings as $index => $l)
                @php
                    $img = $l->primaryImage?->path ?? $l->images->first()?->path;
                    $auction = $l->auction;
                    $isAuction = $auction !== null;
                    $displayPrice = $isAuction
                        ? ($auction->current_bid ?? $auction->starting_bid)
                        : $l->price;
                    $isFeatured = $index === 0;
                @endphp

                <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-xl shadow-black/25 transition duration-300 hover:-translate-y-1 hover:border-amber-400/30 {{ $isFeatured ? 'sm:col-span-2' : '' }}">
                    <div class="relative {{ $isFeatured ? 'aspect-video' : 'aspect-[4/3]' }} overflow-hidden">

                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $l->title }}"
                                class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105 group-hover:opacity-100"
                                loading="{{ $isFeatured ? 'eager' : 'lazy' }}"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-slate-900">
                                <svg class="h-10 w-10 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <rect x="3" y="6" width="18" height="13" rx="2"/>
                                    <circle cx="12" cy="12.5" r="3"/>
                                </svg>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-amber-500/10 opacity-0 transition duration-300 group-hover:opacity-100"></div>

                        <div class="absolute inset-0 flex flex-col justify-end p-5 md:p-6">

                            <div class="mb-3">
                                @if($isAuction)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-neutral-950 shadow-lg shadow-amber-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-neutral-950"></span>
                                        Izsole
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur-sm">
                                        Pārdošana
                                    </span>
                                @endif
                            </div>

                            <h3 class="{{ $isFeatured ? 'text-3xl' : 'text-xl' }} font-black uppercase leading-tight text-white"
                                style="font-family:'Bebas Neue',sans-serif;">
                                {{ $l->title }}
                            </h3>

                            <p class="mt-1 text-xs text-slate-300/70">
                                {{ $l->brand }} {{ $l->model }} · {{ $l->year }} · {{ number_format($l->mileage_km ?? 0, 0, '.', ' ') }} km
                            </p>

                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs text-slate-400">
                                        {{ $isAuction ? 'Pašreizējā cena' : 'Cena' }}
                                    </p>

                                    <p class="{{ $isFeatured ? 'text-4xl' : 'text-3xl' }} font-black leading-tight text-white"
                                       style="font-family:'Bebas Neue',sans-serif;">
                                        {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                                    </p>

                                    @if($isAuction && $auction->ends_at)
                                        <p class="text-xs font-medium text-amber-300/90">
                                            Beidzas {{ $auction->ends_at->format('d.m. H:i') }}
                                        </p>
                                    @endif
                                </div>

                                <a href="{{ route('ads.show', $l) }}"
                                   class="flex shrink-0 items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm transition hover:border-amber-400 hover:bg-amber-400 hover:text-neutral-950 active:scale-95">
                                    Skatīt
                                    <svg class="h-3.5 w-3.5 transition duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 14 14" stroke="currentColor" stroke-width="2">
                                        <path d="M2 7h10M8 3l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

            @empty
                <div class="col-span-full flex flex-col items-center gap-3 rounded-3xl border border-dashed border-white/15 bg-white/[.04] py-24 text-center backdrop-blur-md">
                    <p class="text-sm text-slate-400">
                        Nav neviena sludinājuma.
                    </p>
                </div>
            @endforelse
        </div>

        <footer class="mt-20 border-t border-white/10 py-8 text-center text-xs font-light uppercase tracking-widest text-slate-500">
            © {{ date('Y') }} autoslud
        </footer>

    </div>
</div>

@endsection