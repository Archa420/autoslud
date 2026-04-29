@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md py-10">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Reģistrēties</h1>
            <p class="mt-1 text-sm text-slate-600">Izveido kontu, lai vari pievienot sludinājumus.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-semibold text-slate-800">Vārds</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="Jānis Bērziņš"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-800">E-pasts</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="epasts@piemers.lv"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-800">Parole</label>
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="••••••••"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-800">Atkārtot paroli</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="••••••••"
                />
            </div>

            <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Izveidot kontu
            </button>

            <p class="text-center text-sm text-slate-600">
                Jau ir konts?
                <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Ielogoties</a>
            </p>
        </form>
    </div>
</div>
@endsection
