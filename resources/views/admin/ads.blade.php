@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Admin panelis — Sludinājumi
            </h1>

            <p class="mt-2 text-slate-600">
                Visu sludinājumu pārvaldība un dzēšana.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.users') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Lietotāji
            </a>

            <a href="{{ route('admin.auctions') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Izsoles
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">ID</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Sludinājums</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Lietotājs</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Tips</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Cena</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Izveidots</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-700">Darbības</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($ads as $ad)
                        @php
                            $auction = $ad->auction;
                            $isAuction = $auction !== null;
                            $displayPrice = $isAuction ? ($auction->current_bid ?? $auction->starting_bid) : $ad->price;
                        @endphp

                        <tr>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $ad->id }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-950">
                                    {{ $ad->title }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $ad->user?->username ?? 'Nav lietotāja' }}
                            </td>

                            <td class="px-5 py-4">
                                @if($isAuction)
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                        Izsole
                                    </span>
                                @else
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                        Pārdošana
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                            </td>

                            <td class="px-5 py-4 text-slate-500">
                                {{ $ad->created_at?->format('d.m.Y H:i') }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('ads.show', $ad) }}"
                                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        Atvērt
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.ads.destroy', $ad) }}"
                                          onsubmit="return confirm('Dzēst šo sludinājumu?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                            Dzēst
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $ads->links() }}
        </div>
    </div>
</div>
@endsection