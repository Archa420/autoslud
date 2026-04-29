@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Admin panelis — Izsoles
            </h1>

            <p class="mt-2 text-slate-600">
                Visu izsoļu pārskats, statusa maiņa un dzēšana.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.users') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Lietotāji
            </a>

            <a href="{{ route('admin.ads') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Sludinājumi
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
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Sākumcena</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Pašreizējā cena</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Statuss</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Beidzas</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-700">Darbības</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($auctions as $auction)
                        <tr>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $auction->id }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-950">
                                    {{ $auction->ad?->title ?? 'Sludinājums dzēsts' }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $auction->ad?->brand }} {{ $auction->ad?->model }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $auction->ad?->user?->username ?? 'Nav lietotāja' }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ number_format($auction->starting_bid, 2, '.', ' ') }} €
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ $auction->current_bid !== null ? number_format($auction->current_bid, 2, '.', ' ') . ' €' : '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.auctions.status', $auction) }}">
                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                        class="rounded-xl border-slate-200 bg-white text-xs font-semibold focus:border-amber-400 focus:ring-amber-200"
                                    >
                                        <option value="active" @selected($auction->status === 'active')>
                                            active
                                        </option>
                                        <option value="finished" @selected($auction->status === 'finished')>
                                            finished
                                        </option>
                                        <option value="cancelled" @selected($auction->status === 'cancelled')>
                                            cancelled
                                        </option>
                                    </select>
                                </form>
                            </td>

                            <td class="px-5 py-4 text-slate-500">
                                {{ $auction->ends_at?->format('d.m.Y H:i') }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($auction->ad)
                                        <a href="{{ route('ads.show', $auction->ad) }}"
                                           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                            Atvērt
                                        </a>
                                    @endif

                                    <form method="POST"
                                          action="{{ route('admin.auctions.destroy', $auction) }}"
                                          onsubmit="return confirm('Dzēst šo izsoli? Sludinājums paliks, bet izsoles dati tiks dzēsti.');">
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
            {{ $auctions->links() }}
        </div>
    </div>
</div>
@endsection