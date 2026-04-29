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