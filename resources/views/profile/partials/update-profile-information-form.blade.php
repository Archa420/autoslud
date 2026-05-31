<section>
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label for="username" class="block text-sm font-bold text-slate-300">
                Lietotājvārds
            </label>

            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username', $user->username) }}"
                required
                autofocus
                autocomplete="username"
                class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                placeholder="Ievadi lietotājvārdu"
            >

            @error('username')
                <p class="mt-2 text-sm font-medium text-red-300">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-300">
                E-pasts
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
                class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                placeholder="Ievadi e-pasta adresi"
            >

            @error('email')
                <p class="mt-2 text-sm font-medium text-red-300">
                    {{ $message }}
                </p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-400/20 bg-amber-400/10 px-4 py-3">
                    <p class="text-sm text-amber-200">
                        E-pasta adrese nav apstiprināta.

                        <button form="send-verification"
                                class="font-bold underline underline-offset-4 transition hover:text-amber-100">
                            Nosūtīt apstiprinājuma e-pastu vēlreiz.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-300">
                            Jauna apstiprinājuma saite tika nosūtīta uz e-pastu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                Saglabāt izmaiņas
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-emerald-300"
                >
                    Saglabāts.
                </p>
            @endif
        </div>
    </form>
</section>