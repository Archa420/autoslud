@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $displayName = $user->username ?: $user->email;
    $initial = mb_substr($displayName, 0, 1);
@endphp

<div class="flex flex-col gap-8">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
            Mans profils
        </h1>

        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
            Pārvaldi savu profilu, pievienotos sludinājumus un favorītus vienuviet.
        </p>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
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

            <div class="mt-6 space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span class="text-slate-500">Lietotāja ID</span>
                    <span class="font-semibold text-slate-900">
                        {{ $user->id }}
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span class="text-slate-500">Sludinājumi</span>
                    <span class="font-semibold text-slate-900">
                        {{ $ads->total() }}
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span class="text-slate-500">Profila statuss</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                        Aktīvs
                    </span>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Labot profilu
            </a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-950">
                Profila informācija
            </h2>

            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Lietotāja profils nodrošina piekļuvi personīgajiem sludinājumiem, favorītiem un profila datiem.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Lietotājvārds
                    </p>

                    <p class="mt-1 break-words font-semibold text-slate-950">
                        {{ $displayName }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        E-pasts
                    </p>

                    <p class="mt-1 break-words font-semibold text-slate-950">
                        {{ $user->email }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Konts izveidots
                    </p>

                    <p class="mt-1 font-semibold text-slate-950">
                        {{ $user->created_at?->format('d.m.Y H:i') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Pēdējās izmaiņas
                    </p>

                    <p class="mt-1 font-semibold text-slate-950">
                        {{ $user->updated_at?->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">
                    Mani sludinājumi
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Šeit redzami visi tevis pievienotie sludinājumi.
                </p>
            </div>

            <a href="{{ route('ads.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                Pievienot sludinājumu
            </a>
        </div>

        @if($ads->count() === 0)
            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                <h3 class="text-base font-semibold text-slate-800">
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
                        $displayPrice = $isAuction ? ($auction->current_bid ?? $auction->starting_bid) : $ad->price;
                    @endphp

                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <a href="{{ route('ads.show', $ad) }}" class="block">
                            <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                                @if($img)
                                    <img
                                        src="{{ asset('storage/' . $img) }}"
                                        alt="{{ $ad->title }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
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
                            <h3 class="line-clamp-1 text-base font-semibold text-slate-950">
                                {{ $ad->title }}
                            </h3>

                            <p class="mt-1 line-clamp-1 text-xs text-slate-500">
                                {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                            </p>

                            <div class="mt-4 rounded-2xl border {{ $isAuction ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50' }} px-4 py-4">
                                <p class="text-xs font-semibold uppercase tracking-wide {{ $isAuction ? 'text-amber-700' : 'text-slate-500' }}">
                                    {{ $isAuction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                                </p>

                                <p class="mt-1 text-xl font-bold text-slate-950">
                                    {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                                </p>

                                @if($isAuction)
                                    <div class="mt-3 space-y-1 border-t border-amber-200 pt-3 text-xs text-slate-600">
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

                            <div class="mt-4 grid grid-cols-3 gap-2">
                                <a href="{{ route('ads.show', $ad) }}"
                                   class="rounded-xl bg-slate-950 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-slate-800">
                                    Atvērt
                                </a>

                                <a href="{{ route('ads.edit', $ad) }}"
                                   class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-center text-sm font-semibold text-amber-700 transition hover:bg-amber-100">
                                    Labot
                                </a>

                                <form method="POST"
                                      action="{{ route('ads.destroy', $ad) }}"
                                      onsubmit="return confirm('Vai tiešām vēlies dzēst šo sludinājumu?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                                        Dzēst
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $ads->links() }}
            </div>
        @endif
    </section>

    <footer class="py-8 text-center text-sm text-slate-500">
        © {{ date('Y') }} Autoslud
    </footer>
</div>
@endsection