@extends('layouts.app')

@section('content')
@php
    $base = $conversation->first() ?? $message;
    $ad = $base->ad;
    $otherUser = $base->sender_id === auth()->id() ? $base->receiver : $base->sender;
@endphp

<div class="mx-auto max-w-4xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Sarakste
            </h1>

            <p class="mt-2 text-slate-600">
                {{ $ad?->title ?? 'Sludinājums dzēsts' }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Sarakste ar:
                <span class="font-semibold text-slate-800">
                    {{ $otherUser?->username ?? $otherUser?->email ?? 'Nezināms lietotājs' }}
                </span>
            </p>
        </div>

        <a href="{{ route('messages.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Atpakaļ
        </a>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="max-h-[520px] space-y-4 overflow-y-auto rounded-2xl bg-slate-50 p-4">
            @foreach($conversation as $item)
                @php
                    $mine = $item->sender_id === auth()->id();
                @endphp

                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] rounded-2xl px-4 py-3 text-sm shadow-sm
                        {{ $mine ? 'bg-slate-950 text-white' : 'bg-white text-slate-800 ring-1 ring-slate-200' }}">
                        <div class="mb-1 text-xs {{ $mine ? 'text-white/60' : 'text-slate-400' }}">
                            {{ $mine ? 'Tu' : ($item->sender?->username ?? $item->sender?->email ?? 'Lietotājs') }}
                            · {{ $item->created_at?->format('d.m.Y H:i') }}
                        </div>

                        <div class="whitespace-pre-line leading-relaxed">
                            {{ $item->content }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('messages.reply', $message) }}" class="mt-5">
            @csrf

            <label class="text-sm font-semibold text-slate-800">
                Atbildēt
            </label>

            <textarea
                name="content"
                rows="4"
                required
                class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                placeholder="Raksti atbildi..."
            >{{ old('content') }}</textarea>

            <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:justify-between">
                @if($ad)
                    <a href="{{ route('ads.show', $ad) }}"
                       class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Atvērt sludinājumu
                    </a>
                @endif

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                    Nosūtīt atbildi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection