@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-8">

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Izsoles
            </h1>

            <p class="mt-2 text-slate-600">
                Aktīvās auto izsoles — piedalies un iegūsti labāko cenu.
            </p>
        </div>

        <a href="{{ route('ads.index', ['type' => 'auction']) }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Skatīt visus izsoļu sludinājumus
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($auctions as $auction)
            @php
                $ad = $auction->ad;
                $img = $ad->primaryImage?->path ?? $ad->images->first()?->path;

                $startingBid = (float) ($auction->starting_bid ?? 0);
                $highestBid = (float) ($auction->highestBid?->amount ?? $auction->current_bid ?? 0);
                $currentBid = max($startingBid, $highestBid);

                $minimumStep = (float) ($auction->minimum_bid_step ?? 1);
                $minimumAllowedBid = $currentBid + $minimumStep;

                $bidsCount = $auction->bids?->count() ?? 0;
            @endphp

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="relative h-48 w-full bg-slate-100">
                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                Nav attēla
                            </div>
                        @endif

                        <div class="absolute left-3 top-3">
                            <span class="rounded-full border border-amber-300 bg-amber-100/95 px-3 py-1 text-xs font-bold text-amber-800 shadow-sm">
                                Izsole
                            </span>
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="line-clamp-1 text-lg font-semibold text-slate-950">
                        {{ $ad->title }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                    </p>

                    @if($ad->description)
                        <p class="mt-3 line-clamp-2 text-sm text-slate-600">
                            {{ $ad->description }}
                        </p>
                    @endif

                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-xs font-semibold text-amber-700">
                            Pašreizējā izsoles cena
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-950">
                            {{ number_format($currentBid, 2, '.', ' ') }} €
                        </p>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-slate-600">
                            <div class="rounded-xl border border-amber-100 bg-white/70 px-3 py-2">
                                <p class="text-slate-400">Sākumcena</p>
                                <p class="font-semibold text-slate-900">
                                    {{ number_format($startingBid, 2, '.', ' ') }} €
                                </p>
                            </div>

                            <div class="rounded-xl border border-amber-100 bg-white/70 px-3 py-2">
                                <p class="text-slate-400">Min. solis</p>
                                <p class="font-semibold text-slate-900">
                                    {{ number_format($minimumStep, 2, '.', ' ') }} €
                                </p>
                            </div>

                            <div class="col-span-2 rounded-xl border border-amber-100 bg-white/70 px-3 py-2">
                                <p class="text-slate-400">Nākamais minimālais solījums</p>
                                <p class="font-semibold text-slate-900">
                                    {{ number_format($minimumAllowedBid, 2, '.', ' ') }} €
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <p class="text-slate-400">Solījumi</p>
                            <p class="font-semibold text-slate-900">
                                {{ $bidsCount }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <p class="text-slate-400">Beidzas</p>
                            <p class="font-semibold text-slate-900">
                                {{ $auction->ends_at?->format('d.m.Y H:i') }}
                            </p>
                        </div>
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
                                    class="mt-3 w-full rounded-xl bg-amber-300 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-200"
                                >
                                    Solīt
                                </button>
                            </form>
                        @else
                            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-600">
                                    Lai piedalītos izsolē, nepieciešams pieslēgties.
                                </p>

                                <a href="{{ route('login') }}"
                                   class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-amber-300 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-200">
                                    Pieslēgties
                                </a>
                            </div>
                        @endauth
                    @else
                        <p class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            Šī izsole nav aktīva vai jau ir beigusies.
                        </p>
                    @endif

                    <a href="{{ route('ads.show', $ad) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Atvērt sludinājumu
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 shadow-sm">
                Nav aktīvu izsoļu.
            </div>
        @endforelse
    </div>

</div>
@endsection