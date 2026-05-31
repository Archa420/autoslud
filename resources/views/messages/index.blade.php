@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl text-white">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Sarakstes
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Ziņojumi
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                Šeit redzamas tavas sarakstes par sludinājumiem.
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
            Atpakaļ
        </a>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        @if($messages->count() === 0)
            <div class="rounded-2xl border border-dashed border-white/15 bg-slate-950/50 p-10 text-center">
                <h2 class="text-base font-bold text-white">
                    Ziņojumu vēl nav
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Ziņojumi par sludinājumiem parādīsies šajā sadaļā.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($messages as $message)
                    @php
                        $isSent = $message->sender_id === auth()->id();
                        $otherUser = $isSent ? $message->receiver : $message->sender;
                    @endphp

                    <article class="rounded-3xl border border-white/10 bg-slate-950/50 p-5 shadow-lg shadow-black/10 transition hover:border-amber-400/30 hover:bg-slate-900/70">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider {{ $isSent ? 'text-amber-300' : 'text-emerald-300' }}">
                                    {{ $isSent ? 'Nosūtīts' : 'Saņemts' }}
                                </p>

                                <h2 class="mt-1 text-2xl font-black uppercase leading-tight text-white"
                                    style="font-family:'Bebas Neue', sans-serif;">
                                    {{ $message->ad?->title ?? 'Sludinājums dzēsts' }}
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $isSent ? 'Kam:' : 'No:' }}
                                    <span class="font-bold text-slate-300">
                                        {{ $otherUser?->username ?? $otherUser?->email ?? 'Nezināms lietotājs' }}
                                    </span>
                                </p>
                            </div>

                            <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-slate-400">
                                {{ $message->created_at?->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <div class="mt-4 line-clamp-2 rounded-2xl border border-white/10 bg-slate-950/70 p-4 text-sm leading-relaxed text-slate-300">
                            {{ $message->content }}
                        </div>

                        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                            <a href="{{ route('messages.show', $message) }}"
                               class="inline-flex justify-center rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 transition hover:bg-amber-300 active:scale-95">
                                Atvērt saraksti
                            </a>

                            @if($message->ad)
                                <a href="{{ route('ads.show', $message->ad) }}"
                                   class="inline-flex justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
                                    Atvērt sludinājumu
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection