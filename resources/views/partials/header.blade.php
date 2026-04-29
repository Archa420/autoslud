<header class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/90 backdrop-blur-xl">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-60">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_20%,rgba(56,189,248,0.10),transparent_45%),radial-gradient(circle_at_60%_0%,rgba(168,85,247,0.08),transparent_50%),radial-gradient(circle_at_88%_40%,rgba(236,72,153,0.08),transparent_55%)]"></div>
    </div>

    <div class="relative mx-auto flex max-w-6xl items-center justify-between gap-6 px-4 py-4">
        <a href="{{ route('home') }}" class="group inline-flex shrink-0 items-center gap-3">
            <span class="relative inline-flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl bg-white/5 ring-1 ring-white/10">
                <span aria-hidden="true" class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(56,189,248,0.40),transparent_55%),radial-gradient(circle_at_80%_60%,rgba(236,72,153,0.30),transparent_55%),radial-gradient(circle_at_40%_90%,rgba(168,85,247,0.24),transparent_60%)]"></span>
                <span class="relative text-sm font-black tracking-tight text-white">AS</span>
            </span>

            <span class="text-lg font-semibold tracking-tight text-white">
                Autoslud
            </span>
        </a>

        <nav class="hidden items-center gap-6 md:flex">
            <a href="{{ route('home') }}"
               class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                Sākums
            </a>

            <a href="{{ route('ads.index') }}"
               class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                Sludinājumi
            </a>

            <a href="{{ route('izsoles') }}"
               class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                Izsoles
            </a>

            @auth
                <a href="{{ route('favorites.index') }}"
                   class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                    Favorīti
                </a>

                <a href="{{ route('messages.index') }}"
                   class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                    Ziņojumi
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}"
                       class="text-sm font-semibold text-amber-300 underline-offset-8 transition hover:text-amber-200 hover:underline">
                        Admin panelis
                    </a>
                @endif

                <a href="{{ route('dashboard') }}"
                   class="text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline">
                    Profils
                </a>
            @endauth
        </nav>

        <div class="flex shrink-0 items-center gap-3">
            <a href="{{ auth()->check() ? route('ads.create') : route('login') }}"
               class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-sky-500/90 via-fuchsia-500/75 to-violet-500/85 px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_60px_-30px_rgba(56,189,248,0.9)] transition hover:brightness-110">
                Pārdot auto
            </a>

            @auth
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white/80 transition hover:bg-white/10 hover:text-white">
                        Iziet
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden text-sm font-semibold text-white/75 underline-offset-8 transition hover:text-white hover:underline sm:inline-flex">
                    Ielogoties
                </a>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white/85 transition hover:bg-white/10 hover:text-white">
                    Reģistrēties
                </a>
            @endauth
        </div>
    </div>
</header>