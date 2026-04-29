@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md py-10">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Ielogoties</h1>
            <p class="mt-1 text-sm text-slate-600">Piekļūsti saviem sludinājumiem un izsolēm.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-semibold text-slate-800">E-pasts</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="epasts@piemers.lv"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-slate-800">Parole</label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                            Aizmirsu paroli
                        </a>
                    @endif
                </div>

                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    placeholder="••••••••"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                <span class="text-sm text-slate-700">Atcerēties mani</span>
            </label>

            <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Ielogoties
            </button>

            <p class="text-center text-sm text-slate-600">
                Nav konta?
                <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:underline">Reģistrēties</a>
            </p>
        </form>
    </div>
</div>
@endsection
