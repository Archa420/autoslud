<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-200">
            Dzēst kontu
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-red-100/80">
            Kad konts tiks dzēsts, visi ar šo kontu saistītie dati tiks neatgriezeniski dzēsti.
            Pirms konta dzēšanas pārliecinies, ka esi saglabājis visu nepieciešamo informāciju.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center rounded-2xl border border-red-400/30 bg-red-500/20 px-5 py-3 text-sm font-black uppercase tracking-wider text-red-100 transition hover:bg-red-500/30 active:scale-95"
    >
        Dzēst kontu
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="bg-slate-950 p-6 text-white">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-red-200">
                Vai tiešām vēlies dzēst savu kontu?
            </h2>

            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                Kad konts tiks dzēsts, visi ar to saistītie dati tiks neatgriezeniski dzēsti.
                Lai apstiprinātu konta dzēšanu, ievadi savu paroli.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">
                    Parole
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-500 outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/20"
                    placeholder="Parole"
                />

                @if($errors->userDeletion->get('password'))
                    <div class="mt-2 space-y-1">
                        @foreach($errors->userDeletion->get('password') as $message)
                            <p class="text-sm text-red-300">
                                {{ $message }}
                            </p>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    Atcelt
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl border border-red-400/30 bg-red-500/20 px-5 py-2.5 text-sm font-black uppercase tracking-wider text-red-100 transition hover:bg-red-500/30 active:scale-95"
                >
                    Dzēst kontu
                </button>
            </div>
        </form>
    </x-modal>
</section>