@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $displayName = $user->username ?: $user->email;
    $initial = mb_substr($displayName, 0, 1);
@endphp

<div class="flex flex-col gap-8 text-white">

    <div class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                Lietotāja panelis
            </span>
        </div>

        <h1 class="mt-4 text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
            style="font-family:'Bebas Neue', sans-serif;">
            Mans profils
        </h1>

        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
            Pārvaldi savu profilu, pievienotos sludinājumus, favorītus un izsoļu likmes vienuviet.
        </p>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-5 py-4 text-sm font-medium text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex min-w-0 items-center gap-5">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-amber-400 text-4xl font-black uppercase text-slate-950 shadow-lg shadow-amber-400/20">
                    {{ $initial }}
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/70">
                        Tavs profils
                    </p>

                    <h2 class="mt-1 truncate text-3xl font-black text-white md:text-4xl"
                        style="font-family:'Bebas Neue', sans-serif;">
                        {{ $displayName }}
                    </h2>

                    <p class="mt-1 truncate text-sm font-medium text-slate-500">
                        {{ $user->email }}
                    </p>

                    <div class="mt-3">
                        @if($user->hasAuctionSubscription())
                            <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">
                                Izsoļu abonements aktīvs
                            </span>
                        @else
                            <a href="{{ route('auction-subscription.index') }}"
                               class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-xs font-bold text-amber-300 transition hover:bg-amber-400 hover:text-slate-950">
                                Aktivizēt izsoļu abonementu
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                Labot profilu
            </a>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                    Konts izveidots
                </p>

                <p class="mt-1 font-bold text-white">
                    {{ $user->created_at?->format('d.m.Y H:i') }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                    Pēdējās izmaiņas
                </p>

                <p class="mt-1 font-bold text-white">
                    {{ $user->updated_at?->format('d.m.Y H:i') }}
                </p>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl md:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Manas likmes
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Izsoles, kurās esi veicis likmes.
                </p>
            </div>

            <a href="{{ route('izsoles') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
                Apskatīt izsoles
            </a>
        </div>

        <div class="mt-6">
            @if(isset($userBids) && $userBids->count())
                <div class="grid gap-3">
                    @foreach($userBids as $auctionId => $bids)
                        @php
                            $latestBid = $bids->first();
                            $auction = $latestBid->auction;
                            $ad = $auction?->ad;
                            $highestBid = $auction?->highestBid;
                            $currentBid = $highestBid?->amount ?? $auction?->current_bid ?? $auction?->starting_bid ?? 0;
                            $isHighest = $highestBid && $highestBid->user_id === $user->id;
                            $isWinner = $auction && $auction->status === 'finished' && $auction->winner_user_id === $user->id;
                            $img = $ad?->primaryImage?->path ?? $ad?->images?->first()?->path;
                            $userBidCount = $bids->count();
                            $auctionEnded = $auction?->status === 'finished' || ($auction?->ends_at && $auction->ends_at->isPast());
                        @endphp

                        <article class="rounded-2xl border border-white/10 bg-slate-950/50 p-3 transition hover:border-amber-400/30 hover:bg-slate-900/70">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex min-w-0 flex-1 items-center gap-4">
                                    <a href="{{ $ad ? route('ads.show', $ad) : '#' }}"
                                       class="h-20 w-28 shrink-0 overflow-hidden rounded-xl bg-slate-900">
                                        @if($img)
                                            <img
                                                src="{{ asset('storage/' . $img) }}"
                                                alt="{{ $ad?->title }}"
                                                class="h-full w-full object-cover opacity-90 transition duration-300 hover:scale-105 hover:opacity-100"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center px-2 text-center text-[11px] text-slate-500">
                                                Nav bildes
                                            </div>
                                        @endif
                                    </a>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="line-clamp-1 text-base font-bold text-white">
                                                {{ $ad?->title ?? 'Sludinājums nav pieejams' }}
                                            </h3>

                                            @if($isWinner)
                                                <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-300">
                                                    Tu uzvarēji
                                                </span>
                                            @elseif($auctionEnded)
                                                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-bold text-slate-300">
                                                    Beigusies
                                                </span>
                                            @elseif($isHighest)
                                                <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-300">
                                                    Tu esi vadībā
                                                </span>
                                            @else
                                                <span class="rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-bold text-amber-300">
                                                    Pārsolīts
                                                </span>
                                            @endif
                                        </div>

                                        @if($ad)
                                            <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                                                {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                                            </p>
                                        @endif

                                        <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm">
                                            <p class="text-slate-500">
                                                Cena:
                                                <span class="font-bold text-white">
                                                    {{ number_format($currentBid, 2, '.', ' ') }} €
                                                </span>
                                            </p>

                                            <p class="text-slate-500">
                                                Tava likme:
                                                <span class="font-bold text-white">
                                                    {{ number_format($latestBid->amount, 2, '.', ' ') }} €
                                                </span>
                                            </p>

                                            <p class="text-slate-500">
                                                Beidzas:
                                                <span class="font-bold text-white">
                                                    {{ $auction?->ends_at ? $auction->ends_at->format('d.m.Y H:i') : '—' }}
                                                </span>
                                            </p>

                                            <p class="text-slate-500">
                                                Likmes:
                                                <span class="font-bold text-white">
                                                    {{ $userBidCount }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if($ad && $auction)
                                    <div class="flex shrink-0 gap-2 md:flex-col md:items-end">
                                        <a href="{{ route('ads.show', $ad) }}"
                                           class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-2 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                                            Atvērt
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-white/15 bg-slate-950/50 p-10 text-center">
                    <h3 class="text-base font-bold text-white">
                        Tu vēl neesi veicis nevienu likmi
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Kad piedalīsies kādā auto izsolē, tava likme parādīsies šeit.
                    </p>

                    <a href="{{ route('izsoles') }}"
                       class="mt-5 inline-flex items-center justify-center rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                        Apskatīt izsoles
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl md:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Mani sludinājumi
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Šeit redzami visi tevis pievienotie sludinājumi.
                </p>
            </div>

            <a href="{{ route('ads.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                Pievienot sludinājumu
            </a>
        </div>

        @if($ads->count() === 0)
            <div class="mt-6 rounded-2xl border border-dashed border-white/15 bg-slate-950/50 p-10 text-center">
                <h3 class="text-base font-bold text-white">
                    Tev vēl nav neviena sludinājuma
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Pievieno savu pirmo auto sludinājumu, lai tas būtu redzams platformā.
                </p>
            </div>
        @else
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($ads as $ad)
                    @php
                        $img = $ad->primaryImage?->path ?? $ad->images->first()?->path;
                        $auction = $ad->auction;
                        $isAuction = $auction !== null;
                        $highestBid = $auction?->highestBid;
                        $winner = $auction?->winner;
                        $displayPrice = $isAuction ? ($auction->current_bid ?? $auction->starting_bid) : $ad->price;
                    @endphp

                    <article class="group overflow-hidden rounded-3xl border border-white/10 bg-slate-950/50 shadow-lg shadow-black/20 transition duration-300 hover:-translate-y-1 hover:border-amber-400/30">
                        <a href="{{ route('ads.show', $ad) }}" class="block">
                            <div class="relative h-44 w-full overflow-hidden bg-slate-900">
                                @if($img)
                                    <img
                                        src="{{ asset('storage/' . $img) }}"
                                        alt="{{ $ad->title }}"
                                        class="h-full w-full object-cover opacity-90 transition duration-300 group-hover:scale-105 group-hover:opacity-100"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-slate-500">
                                        Nav bildes
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                                <div class="absolute left-3 top-3">
                                    @if($isAuction)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-950"></span>
                                            {{ $auction->status === 'finished' ? 'Pabeigta izsole' : 'Izsole' }}
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
                            <h3 class="line-clamp-1 text-xl font-black uppercase leading-tight text-white"
                                style="font-family:'Bebas Neue', sans-serif;">
                                {{ $ad->title }}
                            </h3>

                            <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                                {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                            </p>

                            <div class="mt-4 rounded-2xl border {{ $isAuction ? 'border-amber-400/20 bg-amber-400/10' : 'border-white/10 bg-slate-900/60' }} px-4 py-4">
                                <p class="text-xs font-bold uppercase tracking-wide {{ $isAuction ? 'text-amber-300' : 'text-slate-500' }}">
                                    {{ $isAuction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                                </p>

                                <p class="mt-1 text-3xl font-black leading-none text-white"
                                   style="font-family:'Bebas Neue', sans-serif;">
                                    {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                                </p>

                                @if($isAuction)
                                    <div class="mt-3 space-y-1 border-t border-amber-400/20 pt-3 text-xs text-slate-400">
                                        <p>
                                            Sākumcena:
                                            <span class="font-bold text-white">
                                                {{ number_format($auction->starting_bid, 2, '.', ' ') }} €
                                            </span>
                                        </p>

                                        @if($auction->buyout_price)
                                            <p>
                                                Izpirkuma cena:
                                                <span class="font-bold text-white">
                                                    {{ number_format($auction->buyout_price, 2, '.', ' ') }} €
                                                </span>
                                            </p>
                                        @endif

                                        @if($highestBid)
                                            <p>
                                                Augstākais solītājs:
                                                <span class="font-bold text-white">
                                                    {{ $highestBid->user?->name }} · {{ $highestBid->user?->email }}
                                                </span>
                                            </p>
                                        @else
                                            <p>Vēl nav solījumu.</p>
                                        @endif

                                        @if($auction->status === 'finished')
                                            <p>
                                                Uzvarētājs:
                                                <span class="font-bold text-white">
                                                    {{ $winner ? $winner->name . ' · ' . $winner->email : 'Nav' }}
                                                </span>
                                            </p>
                                        @endif

                                        @if($auction->ends_at)
                                            <p>
                                                Beidzas:
                                                <span class="font-bold text-white">
                                                    {{ $auction->ends_at->format('d.m.Y H:i') }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-2">
                                <a href="{{ route('ads.show', $ad) }}"
                                   class="rounded-xl bg-amber-400 px-3 py-2 text-center text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                                    Atvērt
                                </a>

                                <a href="{{ route('ads.edit', $ad) }}"
                                   class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                                    Labot
                                </a>

                                <form method="POST"
                                      action="{{ route('ads.destroy', $ad) }}"
                                      onsubmit="return confirm('Vai tiešām vēlies dzēst šo sludinājumu?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="w-full rounded-xl border border-red-400/30 bg-red-500/10 px-3 py-2 text-sm font-bold text-red-300 transition hover:bg-red-500/20">
                                        Dzēst
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6 text-slate-300">
                {{ $ads->links() }}
            </div>
        @endif
    </section>

    <footer class="py-8 text-center text-sm text-slate-500">
        © {{ date('Y') }} Autoslud
    </footer>
</div>
@endsection