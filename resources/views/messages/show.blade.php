@extends('layouts.app')

@section('content')
@php
    $base = $conversation->first() ?? $message;
    $ad = $base->ad;
    $otherUser = $base->sender_id === auth()->id() ? $base->receiver : $base->sender;
@endphp

<div class="mx-auto max-w-4xl text-white">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Privāta sarakste
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Sarakste
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                {{ $ad?->title ?? 'Sludinājums dzēsts' }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Sarakste ar:
                <span class="font-bold text-slate-300">
                    {{ $otherUser?->username ?? $otherUser?->email ?? 'Nezināms lietotājs' }}
                </span>
            </p>
        </div>

        <a href="{{ route('messages.index') }}"
           class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
            Atpakaļ
        </a>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-6 rounded-3xl border border-white/10 bg-white/[.05] p-5 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="max-h-[520px] space-y-4 overflow-y-auto rounded-3xl border border-white/10 bg-slate-950/50 p-4">
            @foreach($conversation as $item)
                @php
                    $mine = $item->sender_id === auth()->id();
                @endphp

                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] rounded-3xl px-4 py-3 text-sm shadow-lg shadow-black/10
                        {{ $mine ? 'bg-amber-400 text-slate-950' : 'border border-white/10 bg-white/5 text-slate-300' }}">
                        <div class="mb-1 text-xs font-bold {{ $mine ? 'text-slate-950/60' : 'text-slate-500' }}">
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

            <label class="text-sm font-bold text-slate-300">
                Atbildēt
            </label>

            <textarea
                name="content"
                rows="4"
                required
                class="mt-2 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                placeholder="Raksti atbildi..."
            >{{ old('content') }}</textarea>

            <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:justify-between">
                @if($ad)
                    <a href="{{ route('ads.show', $ad) }}"
                       class="inline-flex justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
                        Atvērt sludinājumu
                    </a>
                @endif

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                    Nosūtīt atbildi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection