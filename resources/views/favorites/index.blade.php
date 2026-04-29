@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-slate-900">
            Mani favorīti
        </h1>
        <p class="mt-2 text-slate-600">
            Šeit redzami sludinājumi, kurus esi saglabājis.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
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

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="aspect-[16/10] bg-slate-100">
                        @if($img)
                            <img
                                src="{{ asset('storage/'.$img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full items-center justify-center text-slate-400">
                                Nav attēla
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-semibold text-slate-900">
                                {{ $ad->title }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }}
                            </p>
                        </div>

                        @if($auction)
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                Izsole
                            </span>
                        @endif
                    </div>

                    <p class="mt-4 text-xl font-bold text-slate-900">
                        {{ $price !== null ? number_format($price, 2, '.', ' ') . ' €' : '—' }}
                    </p>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('ads.show', $ad) }}"
                           class="flex-1 rounded-xl bg-slate-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-slate-700">
                            Atvērt
                        </a>

                        <form action="{{ route('favorites.destroy', $ad) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                            >
                                Noņemt
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500">
                Tev vēl nav saglabātu favorītu.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $ads->links() }}
    </div>
</div>
@endsection