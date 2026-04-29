@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Ziņojumi
            </h1>

            <p class="mt-2 text-slate-600">
                Šeit redzamas tavas sarakstes par sludinājumiem.
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Atpakaļ
        </a>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        @if($messages->count() === 0)
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                <h2 class="text-base font-semibold text-slate-800">
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

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:bg-slate-100">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ $isSent ? 'Nosūtīts' : 'Saņemts' }}
                                </p>

                                <h2 class="mt-1 text-base font-semibold text-slate-950">
                                    {{ $message->ad?->title ?? 'Sludinājums dzēsts' }}
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $isSent ? 'Kam:' : 'No:' }}
                                    <span class="font-semibold text-slate-800">
                                        {{ $otherUser?->username ?? $otherUser?->email ?? 'Nezināms lietotājs' }}
                                    </span>
                                </p>
                            </div>

                            <div class="text-sm text-slate-500">
                                {{ $message->created_at?->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <div class="mt-4 line-clamp-2 rounded-xl bg-white p-4 text-sm leading-relaxed text-slate-700 ring-1 ring-slate-200">
                            {{ $message->content }}
                        </div>

                        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                            <a href="{{ route('messages.show', $message) }}"
                               class="inline-flex justify-center rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                Atvērt saraksti
                            </a>

                            @if($message->ad)
                                <a href="{{ route('ads.show', $message->ad) }}"
                                   class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
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