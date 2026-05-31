@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $displayName = $user->username ?: 'Nav norādīts';
    $initial = mb_substr($user->username ?: $user->email, 0, 1);
@endphp

<div class="flex flex-col gap-8 text-white">

    <div class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                        Profila pārvaldība
                    </span>
                </div>

                <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Profila iestatījumi
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                    Rediģē lietotāja profila informāciju, paroli un konta iestatījumus.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95">
                ← Atpakaļ uz profilu
            </a>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-300">
            Profila informācija veiksmīgi atjaunota.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-300">
            Parole veiksmīgi atjaunota.
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-400 text-2xl font-black uppercase text-slate-950 shadow-lg shadow-amber-400/20">
                    {{ $initial }}
                </div>

                <div class="min-w-0">
                    <h2 class="truncate text-lg font-bold text-white">
                        {{ $displayName }}
                    </h2>

                    <p class="truncate text-sm text-slate-500">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                        Lietotāja ID
                    </p>

                    <p class="mt-1 font-bold text-white">
                        {{ $user->id }}
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                        Statuss
                    </p>

                    <p class="mt-1 font-bold text-emerald-300">
                        Aktīvs
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Profila informācija
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    Šeit iespējams mainīt lietotāja vārdu un e-pasta adresi.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-xl shadow-black/20 backdrop-blur-xl md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-white"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Paroles maiņa
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    Drošības nolūkos izmanto garu un unikālu paroli.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-red-400/30 bg-red-950/30 p-6 shadow-xl shadow-black/20 backdrop-blur-xl md:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-red-200"
                    style="font-family:'Bebas Neue', sans-serif;">
                    Konta dzēšana
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-red-200/80">
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