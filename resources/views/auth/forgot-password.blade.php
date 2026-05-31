@extends('layouts.app')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-10rem)] max-w-md items-center py-10 text-white">
    <div class="w-full rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="mb-7">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Paroles atjaunošana
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-5xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Aizmirsi paroli?
            </h1>

            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                Ievadi savu e-pasta adresi, un mēs nosūtīsim paroles atjaunošanas saiti.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-5 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-bold text-slate-300">
                    E-pasts
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder:text-slate-500 outline-none transition focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                    placeholder="epasts@piemers.lv"
                >

                @error('email')
                    <p class="mt-2 text-sm font-medium text-red-300">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-amber-400 px-4 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95"
            >
                Nosūtīt atjaunošanas saiti
            </button>

            <p class="text-center text-sm text-slate-500">
                Atceries paroli?
                <a href="{{ route('login') }}"
                   class="font-bold text-amber-300 transition hover:text-amber-200 hover:underline">
                    Ielogoties
                </a>
            </p>
        </form>
    </div>
</div>
@endsection