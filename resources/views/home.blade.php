@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-10">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 shadow-sm md:p-12">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 right-0 h-72 w-72 rounded-full bg-amber-200/40 blur-3xl"></div>
            <div class="absolute -bottom-32 left-10 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>
        </div>

        <div class="relative max-w-3xl">
            <h1 class="text-4xl font-semibold tracking-tight text-slate-950 md:text-5xl">
                autoslud
            </h1>

            <p class="mt-4 text-lg text-slate-600">
                Lietoti auto, izsoles un svaigākie sludinājumi vienā vietā.
                Ātri, pārskatāmi un bez lieka trokšņa.
            </p>

            <form action="{{ route('ads.index') }}" method="GET" class="mt-8">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Meklē pēc markas, modeļa vai atslēgvārda…"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-slate-900 placeholder:text-slate-400 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 px-6 py-4 text-sm font-bold text-white transition hover:bg-slate-800">
                        Meklēt
                    </button>
                </div>
            </form>
        </div>

        <div class="relative mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Jaunākie sludinājumi</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">
                    {{ $listings->count() }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <p class="text-sm text-amber-700">Izsoles</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">
                    Seko līdzi un piedalies solīšanā.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Privātie pārdevēji</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">
                    Reāli auto no reāliem cilvēkiem.
                </p>
            </div>
        </div>
    </section>

    {{-- SECTION TITLE --}}
    <div>
        <h2 class="text-2xl font-semibold text-slate-950">
            Jaunākie sludinājumi
        </h2>
        <p class="mt-2 text-slate-600">
            Pārlūko svaigākos piedāvājumus un atver detaļas.
        </p>
    </div>

    {{-- LISTINGS GRID --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($listings as $l)
            @php
                $img = $l->primaryImage?->path ?? $l->images->first()?->path;

                $auction = $l->auction;
                $isAuction = $auction !== null;

                $displayPrice = $isAuction
                    ? ($auction->current_bid ?? $auction->starting_bid)
                    : $l->price;
            @endphp

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <a href="{{ route('ads.show', $l) }}" class="block">
                    <div class="relative h-48 w-full bg-slate-100">
                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $l->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                Nav bildes
                            </div>
                        @endif

                        <div class="absolute left-3 top-3">
                            @if($isAuction)
                                <span class="rounded-full border border-amber-300 bg-amber-100/95 px-3 py-1 text-xs font-bold text-amber-800 shadow-sm">
                                    Izsole
                                </span>
                            @else
                                <span class="rounded-full border border-emerald-200 bg-emerald-50/95 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                    Pārdošana
                                </span>
                            @endif
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="line-clamp-1 text-lg font-semibold text-slate-950">
                        {{ $l->title }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $l->brand }} {{ $l->model }} · {{ $l->year }} · {{ number_format($l->mileage_km ?? 0, 0, '.', ' ') }} km
                    </p>

                    @if($l->description)
                        <p class="mt-3 line-clamp-2 text-sm text-slate-600">
                            {{ $l->description }}
                        </p>
                    @endif

                    <div class="mt-4 rounded-xl border {{ $isAuction ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50' }} px-3 py-3">
                        <p class="text-xs font-semibold {{ $isAuction ? 'text-amber-700' : 'text-slate-500' }}">
                            {{ $isAuction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                        </p>

                        <p class="mt-1 text-xl font-bold text-slate-950">
                            {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                        </p>

                        @if($isAuction)
                            <div class="mt-2 text-xs text-slate-600">
                                <p>
                                    Sākumcena:
                                    <span class="font-semibold text-slate-900">
                                        {{ number_format($auction->starting_bid, 2, '.', ' ') }} €
                                    </span>
                                </p>

                                @if($auction->ends_at)
                                    <p>
                                        Beidzas:
                                        <span class="font-semibold text-slate-900">
                                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('ads.show', $l) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Atvērt sludinājumu
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 shadow-sm">
                Nav neviena sludinājuma.
            </div>
        @endforelse
    </div>

    <footer class="py-8 text-center text-sm text-slate-500">
        © {{ date('Y') }} A-Auction
    </footer>

</div>
@endsection