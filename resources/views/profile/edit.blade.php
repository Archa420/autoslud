@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $displayName = $user->username ?: 'Nav norādīts';
    $initial = mb_substr($user->username ?: $user->email, 0, 1);
@endphp

<div class="flex flex-col gap-8">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                    Profila iestatījumi
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    Rediģē lietotāja profila informāciju, paroli un konta iestatījumus.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                ← Atpakaļ uz profilu
            </a>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            Profila informācija veiksmīgi atjaunota.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            Parole veiksmīgi atjaunota.
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-xl font-bold uppercase text-white shadow-sm">
                    {{ $initial }}
                </div>

                <div class="min-w-0">
                    <h2 class="truncate text-lg font-semibold text-slate-950">
                        {{ $displayName }}
                    </h2>

                    <p class="truncate text-sm text-slate-500">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Lietotāja ID
                    </p>

                    <p class="mt-1 font-bold text-slate-950">
                        {{ $user->id }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Statuss
                    </p>

                    <p class="mt-1 font-bold text-emerald-700">
                        Aktīvs
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">
                    Manas likmes
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                    Pārskats par auto izsolēm, kurās esi piedalījies.
                </p>
            </div>
        </div>

        <div class="mt-6">
            @if(isset($userBids) && $userBids->count())
                <div class="grid gap-4">
                    @foreach($userBids as $auctionId => $bids)
                        @php
                            $latestBid = $bids->first();
                            $auction = $latestBid->auction;
                            $ad = $auction?->ad;
                            $highestBid = $auction?->highestBid;
                            $currentBid = $highestBid?->amount ?? $auction?->current_bid ?? $auction?->starting_bid ?? 0;
                            $isWinning = $highestBid && $highestBid->user_id === $user->id;
                            $image = $ad?->images?->sortBy('sort_order')->first();
                        @endphp

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="h-20 w-28 shrink-0 overflow-hidden rounded-xl bg-slate-200">
                                        @if($image)
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                 alt="{{ $ad?->title }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-xs font-semibold text-slate-500">
                                                Nav foto
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-950">
                                            {{ $ad?->title ?? 'Sludinājums nav pieejams' }}
                                        </h3>

                                        <div class="mt-2 grid gap-1 text-sm text-slate-500">
                                            <p>
                                                Tava pēdējā likme:
                                                <span class="font-semibold text-slate-900">
                                                    €{{ number_format($latestBid->amount, 2, ',', ' ') }}
                                                </span>
                                            </p>

                                            <p>
                                                Pašreizējā augstākā likme:
                                                <span class="font-semibold text-slate-900">
                                                    €{{ number_format($currentBid, 2, ',', ' ') }}
                                                </span>
                                            </p>

                                            @if($auction?->ends_at)
                                                <p>
                                                    Izsole beidzas:
                                                    <span class="font-semibold text-slate-900">
                                                        {{ $auction->ends_at->format('d.m.Y H:i') }}
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-start gap-2 sm:items-end">
                                    @if($isWinning)
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                            Tu esi vadībā
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                            Tevi pārsolīja
                                        </span>
                                    @endif

                                    @if($ad)
                                        <a href="{{ route('ads.show', $ad) }}"
                                           class="text-sm font-semibold text-slate-950 underline-offset-4 hover:underline">
                                            Skatīt sludinājumu
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                    <p class="text-sm font-semibold text-slate-700">
                        Tu vēl neesi veicis nevienu likmi.
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Kad piedalīsies izsolēs, tās parādīsies šeit.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">
                    Profila informācija
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                    Šeit iespējams mainīt lietotāja vārdu un e-pasta adresi. Šie dati tiek izmantoti lietotāja identificēšanai sistēmā.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">
                    Paroles maiņa
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                    Drošības nolūkos izmanto garu un unikālu paroli, kuru neizmanto citās vietnēs.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-lg font-semibold text-red-700">
                    Konta dzēšana
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-red-600">
                    Dzēšot kontu, tiks neatgriezeniski dzēsti lietotāja dati, kas saistīti ar profilu.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </section>

</div>
@endsection