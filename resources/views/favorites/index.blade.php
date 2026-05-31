@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl text-white">
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Saglabātie sludinājumi
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Mani favorīti
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                Šeit redzami sludinājumi, kurus esi saglabājis.
            </p>
        </div>

        <a href="{{ route('ads.index') }}"
           class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
            Skatīt sludinājumus
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($ads as $ad)
            @php
                $img = $ad->primaryImage?->path ?? $ad->images->first()?->path;
                $auction = $ad->auction;
                $price = $auction
                    ? ($auction->current_bid ?? $auction->starting_bid)
                    : $ad->price;
            @endphp

            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/[.05] shadow-xl shadow-black/20 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:border-amber-400/30">
                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105 group-hover:opacity-100"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full items-center justify-center text-slate-500">
                                Nav attēla
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent to-transparent"></div>

                        <div class="absolute left-3 top-3">
                            @if($auction)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-950"></span>
                                    Izsole
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-md">
                                    Pārdošana
                                </span>
                            @endif
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <h2 class="line-clamp-1 text-xl font-black uppercase leading-tight text-white"
                        style="font-family:'Bebas Neue', sans-serif;">
                        {{ $ad->title }}
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }}
                    </p>

                    <div class="mt-4 rounded-2xl border {{ $auction ? 'border-amber-400/20 bg-amber-400/10' : 'border-white/10 bg-slate-950/50' }} px-4 py-4">
                        <p class="text-xs font-bold uppercase tracking-wide {{ $auction ? 'text-amber-300' : 'text-slate-500' }}">
                            {{ $auction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                        </p>

                        <p class="mt-1 text-3xl font-black leading-none text-white"
                           style="font-family:'Bebas Neue', sans-serif;">
                            {{ $price !== null ? number_format($price, 2, '.', ' ') . ' €' : '—' }}
                        </p>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('ads.show', $ad) }}"
                           class="flex-1 rounded-2xl bg-amber-400 px-4 py-2.5 text-center text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                            Atvērt
                        </a>

                        <form action="{{ route('favorites.destroy', $ad) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-2.5 text-sm font-bold text-red-300 transition hover:bg-red-500/20"
                            >
                                Noņemt
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-white/10 bg-white/[.05] p-10 text-center text-slate-400 shadow-xl shadow-black/20 backdrop-blur-xl">
                Tev vēl nav saglabātu favorītu.
            </div>
        @endforelse
    </div>

    <div class="mt-8 text-slate-300">
        {{ $ads->links() }}
    </div>
</div>
@endsection