<section>
    <header>
        <h2 class="text-lg font-bold text-white">
            Paroles maiņa
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-slate-400">
            Drošības nolūkos izmanto garu un unikālu paroli, kuru neizmanto citās vietnēs.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-slate-300">
                Pašreizējā parole
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder:text-slate-500 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                autocomplete="current-password"
                placeholder="Ievadi pašreizējo paroli"
            >

            @if($errors->updatePassword->get('current_password'))
                <div class="mt-2 space-y-1">
                    @foreach($errors->updatePassword->get('current_password') as $message)
                        <p class="text-sm text-red-300">
                            {{ $message }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-slate-300">
                Jaunā parole
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder:text-slate-500 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                autocomplete="new-password"
                placeholder="Ievadi jauno paroli"
            >

            @if($errors->updatePassword->get('password'))
                <div class="mt-2 space-y-1">
                    @foreach($errors->updatePassword->get('password') as $message)
                        <p class="text-sm text-red-300">
                            {{ $message }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-slate-300">
                Apstiprini jauno paroli
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder:text-slate-500 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                autocomplete="new-password"
                placeholder="Atkārtoti ievadi jauno paroli"
            >

            @if($errors->updatePassword->get('password_confirmation'))
                <div class="mt-2 space-y-1">
                    @foreach($errors->updatePassword->get('password_confirmation') as $message)
                        <p class="text-sm text-red-300">
                            {{ $message }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="rounded-2xl bg-amber-400 px-5 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95"
            >
                Saglabāt paroli
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-emerald-300"
                >
                    Parole saglabāta.
                </p>
            @endif
        </div>
    </form>
</section>