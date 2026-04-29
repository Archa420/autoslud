<section>
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label for="username" class="block text-sm font-semibold text-slate-700">
                Vārds
            </label>

            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username', $user->username) }}"
                required
                autofocus
                autocomplete="username"
                class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
            >

            @error('username')
                <p class="mt-2 text-sm font-medium text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700">
                E-pasts
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
                class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
            >

            @error('email')
                <p class="mt-2 text-sm font-medium text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <p class="text-sm text-amber-800">
                        E-pasta adrese nav apstiprināta.

                        <button form="send-verification"
                                class="font-semibold underline transition hover:text-amber-900">
                            Nosūtīt apstiprinājuma e-pastu vēlreiz.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-700">
                            Jauna apstiprinājuma saite tika nosūtīta uz e-pastu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Saglabāt izmaiņas
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-emerald-700"
                >
                    Saglabāts.
                </p>
            @endif
        </div>
    </form>
</section>