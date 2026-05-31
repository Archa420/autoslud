<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
    @php
        $navBase = 'inline-flex h-10 items-center rounded-xl px-4 text-sm font-semibold transition duration-150 ease-in-out';
        $navActive = 'bg-amber-400 text-slate-950 shadow-sm shadow-amber-400/20';
        $navInactive = 'text-slate-300 hover:bg-white/10 hover:text-white';

        $responsiveBase = 'flex h-12 items-center border-l-4 px-4 text-base font-semibold transition duration-150 ease-in-out';
        $responsiveActive = 'border-amber-400 bg-amber-400/10 text-amber-300';
        $responsiveInactive = 'border-transparent text-slate-300 hover:border-amber-400/50 hover:bg-white/5 hover:text-white';

        $actionBase = 'inline-flex h-10 items-center justify-center rounded-xl px-4 text-sm font-semibold transition duration-150 ease-in-out';

        $isHome = request()->routeIs('home');
        $isAds = request()->routeIs('ads.index') || request()->routeIs('ads.show');
        $isIzsoles = request()->routeIs('izsoles');
        $isFavorites = request()->routeIs('favorites.index');
        $isAdsCreate = request()->routeIs('ads.create');
        $isMessages = request()->routeIs('messages.index') || request()->routeIs('messages.*');
        $isAdmin = request()->routeIs('admin.*');
        $isSubscription = request()->routeIs('auction-subscription.*');
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">

            <div class="flex">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}"
                       class="bg-gradient-to-r from-white via-amber-200 to-amber-400 bg-clip-text text-3xl font-black uppercase tracking-[0.2em] text-transparent transition hover:opacity-80"
                       style="font-family:'Bebas Neue', sans-serif;">
                        Autoslud
                    </a>
                </div>

                <div class="hidden items-center space-x-2 sm:ms-10 sm:flex">
                    <a href="{{ route('home') }}"
                       class="{{ $navBase }} {{ $isHome ? $navActive : $navInactive }}">
                        Sākums
                    </a>

                    <a href="{{ route('ads.index') }}"
                       class="{{ $navBase }} {{ $isAds ? $navActive : $navInactive }}">
                        Sludinājumi
                    </a>

                    <a href="{{ route('izsoles') }}"
                       class="{{ $navBase }} {{ $isIzsoles ? $navActive : $navInactive }}">
                        Izsoles
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="{{ $navBase }} {{ request()->routeIs('dashboard') ? $navActive : $navInactive }}">
                            Mans panelis
                        </a>

                        <a href="{{ route('favorites.index') }}"
                           class="{{ $navBase }} {{ $isFavorites ? $navActive : $navInactive }}">
                            Favorīti
                        </a>

                        <a href="{{ route('ads.create') }}"
                           class="{{ $navBase }} {{ $isAdsCreate ? $navActive : $navInactive }}">
                            Pievienot
                        </a>

                        <a href="{{ route('auction-subscription.index') }}"
                           class="{{ $navBase }} {{ $isSubscription ? $navActive : (auth()->user()->hasAuctionSubscription() ? 'text-emerald-300 hover:bg-emerald-500/10 hover:text-emerald-200' : $navInactive) }}">
                            {{ auth()->user()->hasAuctionSubscription() ? 'Abonements aktīvs' : 'Abonements' }}
                        </a>

                        @if(Route::has('messages.index'))
                            <a href="{{ route('messages.index') }}"
                               class="{{ $navBase }} {{ $isMessages ? $navActive : $navInactive }}">
                                Ziņojumi
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.users') }}"
                               class="{{ $navBase }} {{ $isAdmin ? 'bg-red-500 text-white shadow-sm shadow-red-500/20' : 'text-red-300 hover:bg-red-500/10 hover:text-red-200' }}">
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden items-center sm:ms-6 sm:flex">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="hidden text-sm font-semibold text-slate-300 lg:inline">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="{{ $actionBase }} border border-white/10 bg-white/5 text-slate-300 hover:border-red-400/40 hover:bg-red-500/10 hover:text-red-300">
                                Iziet
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}"
                           class="{{ $actionBase }} text-slate-300 hover:bg-white/10 hover:text-white">
                            Pieslēgties
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="{{ $actionBase }} bg-amber-400 text-slate-950 shadow-sm shadow-amber-400/20 hover:bg-amber-300">
                                Reģistrēties
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/10 bg-slate-950/95 backdrop-blur-xl sm:hidden">

        <div class="space-y-1 py-3">
            <a href="{{ route('home') }}"
               class="{{ $responsiveBase }} {{ $isHome ? $responsiveActive : $responsiveInactive }}">
                Sākums
            </a>

            <a href="{{ route('ads.index') }}"
               class="{{ $responsiveBase }} {{ $isAds ? $responsiveActive : $responsiveInactive }}">
                Sludinājumi
            </a>

            <a href="{{ route('izsoles') }}"
               class="{{ $responsiveBase }} {{ $isIzsoles ? $responsiveActive : $responsiveInactive }}">
                Izsoles
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="{{ $responsiveBase }} {{ request()->routeIs('dashboard') ? $responsiveActive : $responsiveInactive }}">
                    Mans panelis
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="{{ $responsiveBase }} {{ $isFavorites ? $responsiveActive : $responsiveInactive }}">
                    Favorīti
                </a>

                <a href="{{ route('ads.create') }}"
                   class="{{ $responsiveBase }} {{ $isAdsCreate ? $responsiveActive : $responsiveInactive }}">
                    Pievienot sludinājumu
                </a>

                <a href="{{ route('auction-subscription.index') }}"
                   class="{{ $responsiveBase }} {{ $isSubscription ? $responsiveActive : (auth()->user()->hasAuctionSubscription() ? 'border-emerald-400 bg-emerald-500/10 text-emerald-300' : $responsiveInactive) }}">
                    {{ auth()->user()->hasAuctionSubscription() ? 'Abonements aktīvs' : 'Izsoļu abonements' }}
                </a>

                @if(Route::has('messages.index'))
                    <a href="{{ route('messages.index') }}"
                       class="{{ $responsiveBase }} {{ $isMessages ? $responsiveActive : $responsiveInactive }}">
                        Ziņojumi
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}"
                       class="{{ $responsiveBase }} {{ $isAdmin ? 'border-red-400 bg-red-500/10 text-red-300' : 'border-transparent text-red-300 hover:border-red-400/50 hover:bg-red-500/10 hover:text-red-200' }}">
                        Admin
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <div class="border-t border-white/10 px-4 py-4">
                <div>
                    <div class="font-semibold text-white">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-sm font-medium text-slate-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="flex h-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-sm font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Profils
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="flex h-11 w-full items-center justify-center rounded-xl border border-red-400/30 bg-red-500/10 text-sm font-semibold text-red-300 transition hover:bg-red-500/20">
                            Iziet
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 border-t border-white/10 px-4 py-4">
                <a href="{{ route('login') }}"
                   class="flex h-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-sm font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                    Pieslēgties
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="flex h-11 items-center justify-center rounded-xl bg-amber-400 text-sm font-semibold text-slate-950 shadow-sm shadow-amber-400/20 transition hover:bg-amber-300">
                        Reģistrēties
                    </a>
                @endif
            </div>
        @endauth
    </div>
</nav>