@extends('layouts.app')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-10rem)] max-w-md items-center py-10 text-white">
    <div class="w-full rounded-3xl border border-white/10 bg-white/[.05] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="mb-7">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    E-pasta apstiprināšana
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-5xl"
                style="font-family:'Bebas Neue', sans-serif;">
                Apstiprini e-pastu
            </h1>

            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                Paldies par reģistrāciju! Pirms turpini, apstiprini savu e-pasta adresi, izmantojot saiti, ko nosūtījām uz tavu e-pastu.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
                Jauna apstiprinājuma saite tika nosūtīta uz e-pasta adresi, kuru norādīji reģistrācijas laikā.
            </div>
        @endif

        <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-4">
            <p class="text-sm leading-relaxed text-slate-400">
                Ja e-pastu nesaņēmi, vari nosūtīt apstiprinājuma saiti vēlreiz.
            </p>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95 sm:w-auto"
                >
                    Nosūtīt vēlreiz
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white active:scale-95 sm:w-auto"
                >
                    Iziet
                </button>
            </form>
        </div>
    </div>
</div>
@endsection