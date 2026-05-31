@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-8 text-white">

    <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Auto izsoles
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Izsoles
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                Aktīvās auto izsoles — piedalies un iesniedz savu cenas piedāvājumu.
            </p>
        </div>

        <a href="{{ route('ads.index', ['type' => 'auction']) }}"
           class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-slate-300 backdrop-blur-md transition hover:bg-white/10 hover:text-white active:scale-95">
            Skatīt visus izsoļu sludinājumus
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($auctions as $auction)
            @php
                $ad = $auction->ad;
                $img = $ad->primaryImage?->path ?? $ad->images->first()?->path;

                $startingBid = (float) ($auction->starting_bid ?? 0);
                $highestBidModel = $auction->highestBid;
                $highestBid = (float) ($highestBidModel?->amount ?? $auction->current_bid ?? 0);
                $currentBid = max($startingBid, $highestBid);

                $minimumStep = (float) ($auction->minimum_bid_step ?? 1);
                $minimumAllowedBid = $currentBid + $minimumStep;

                $buyoutPrice = $auction->buyout_price ? (float) $auction->buyout_price : null;

                $bidsCount = $auction->bids?->count() ?? 0;

                $isSeller = auth()->check() && auth()->id() === $ad->user_id;
                $isHighestBidder = auth()->check() && $highestBidModel && $highestBidModel->user_id === auth()->id();
                $hasAuctionSubscription = auth()->check() && auth()->user()->hasAuctionSubscription();
            @endphp

            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/[.05] shadow-xl shadow-black/20 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:border-amber-400/30">

                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="relative h-48 w-full overflow-hidden bg-slate-900">
                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105 group-hover:opacity-100"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-500">
                                Nav attēla
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent to-transparent"></div>

                        <div class="absolute left-3 top-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-950"></span>
                                Izsole
                            </span>
                        </div>

                        @if($isHighestBidder)
                            <div class="absolute right-3 top-3">
                                <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-300 backdrop-blur-md">
                                    Tu esi vadībā
                                </span>
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="line-clamp-1 text-xl font-black uppercase leading-tight text-white"
                        style="font-family:'Bebas Neue', sans-serif;">
                        {{ $ad->title }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-400">
                        {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                    </p>

                    @if($ad->description)
                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-400">
                            {{ $ad->description }}
                        </p>
                    @endif

                    <div class="mt-4 rounded-2xl border border-amber-400/20 bg-amber-400/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-300">
                            Pašreizējā izsoles cena
                        </p>

                        <p class="mt-1 text-4xl font-black leading-none text-white"
                           style="font-family:'Bebas Neue', sans-serif;">
                            {{ number_format($currentBid, 2, '.', ' ') }} €
                        </p>

                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-slate-400">
                            <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                                <p class="text-slate-500">Sākumcena</p>
                                <p class="font-bold text-white">
                                    {{ number_format($startingBid, 2, '.', ' ') }} €
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                                <p class="text-slate-500">Min. solis</p>
                                <p class="font-bold text-white">
                                    {{ number_format($minimumStep, 2, '.', ' ') }} €
                                </p>
                            </div>

                            @if($buyoutPrice)
                                <div class="col-span-2 rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                                    <p class="text-slate-500">Izpirkuma piedāvājuma cena</p>
                                    <p class="font-bold text-white">
                                        {{ number_format($buyoutPrice, 2, '.', ' ') }} €
                                    </p>
                                </div>
                            @endif

                            <div class="col-span-2 rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                                <p class="text-slate-500">Nākamais minimālais solījums</p>
                                <p class="font-bold text-white">
                                    {{ number_format($minimumAllowedBid, 2, '.', ' ') }} €
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <p class="text-slate-500">Solījumi</p>
                            <p class="font-bold text-white">
                                {{ $bidsCount }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <p class="text-slate-500">Beidzas</p>
                            <p class="font-bold text-white">
                                {{ $auction->ends_at?->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($auction->status === 'active' && $auction->ends_at && now()->lessThan($auction->ends_at))
                        @auth
                            @if($isSeller)
                                <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4">
                                    <p class="text-sm text-slate-400">
                                        Tu nevari solīt savā izsolē.
                                    </p>

                                    @if($highestBidModel)
                                        <div class="mt-3 border-t border-white/10 pt-3 text-xs text-slate-400">
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
                                        </div>
                                    @endif
                                </div>
                            @elseif(! $hasAuctionSubscription)
                                <div class="mt-4 rounded-2xl border border-amber-400/20 bg-slate-950/50 p-4">
                                    <p class="text-sm text-slate-300">
                                        Lai piedalītos izsolēs, nepieciešams aktīvs izsoļu abonements.
                                    </p>

                                    <a href="{{ route('auction-subscription.index') }}"
                                       class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
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
                                   class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                                    Pieslēgties
                                </a>
                            </div>
                        @endauth
                    @else
                        <p class="mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4 text-sm text-slate-400">
                            Šī izsole nav aktīva vai jau ir beigusies.
                        </p>
                    @endif

                    <a href="{{ route('ads.show', $ad) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
                        Atvērt sludinājumu
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-white/10 bg-white/[.05] p-10 text-center text-slate-400 shadow-xl shadow-black/20 backdrop-blur-xl">
                Nav aktīvu izsoļu.
            </div>
        @endforelse
    </div>

</div>
@endsection