<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur-xl shadow-sm">
    @php
        $navBase = 'inline-flex h-10 items-center rounded-xl px-4 text-sm font-semibold transition duration-150 ease-in-out';
        $navActive = 'bg-blue-600 text-white shadow-sm shadow-blue-600/20';
        $navInactive = 'text-slate-600 hover:bg-blue-50 hover:text-blue-700';

        $responsiveBase = 'flex h-12 items-center border-l-4 px-4 text-base font-semibold transition duration-150 ease-in-out';
        $responsiveActive = 'border-blue-600 bg-blue-50 text-blue-700';
        $responsiveInactive = 'border-transparent text-slate-600 hover:border-blue-300 hover:bg-slate-50 hover:text-blue-700';

        $actionBase = 'inline-flex h-10 items-center justify-center rounded-xl px-4 text-sm font-semibold transition duration-150 ease-in-out';

        $isHome = request()->routeIs('home');
        $isAds = request()->routeIs('ads.index') || request()->routeIs('ads.show');
        $isIzsoles = request()->routeIs('izsoles');
        $isFavorites = request()->routeIs('favorites.index');
        $isAdsCreate = request()->routeIs('ads.create');
        $isMessages = request()->routeIs('messages.index') || request()->routeIs('messages.*');
        $isAdmin = request()->routeIs('admin.*');
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">

            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-sm font-black text-white shadow-sm shadow-blue-600/30">
                            AS
                        </span>

                        <span class="hidden leading-tight sm:block">
                            <span class="block text-base font-bold text-slate-900">
                                Autoslud
                            </span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
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
                           class="{{ $navBase }} {{ $navInactive }}">
                            Mans profils
                        </a>

                        <a href="{{ route('favorites.index') }}"
                           class="{{ $navBase }} {{ $isFavorites ? $navActive : $navInactive }}">
                            Favorīti
                        </a>

                        <a href="{{ route('ads.create') }}"
                           class="{{ $navBase }} {{ $isAdsCreate ? $navActive : $navInactive }}">
                            Pievienot
                        </a>

                        @if(Route::has('messages.index'))
                            <a href="{{ route('messages.index') }}"
                               class="{{ $navBase }} {{ $isMessages ? $navActive : $navInactive }}">
                                Ziņojumi
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.users') }}"
                               class="{{ $navBase }} {{ $isAdmin ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/20' : 'text-amber-600 hover:bg-amber-50 hover:text-amber-700' }}">
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side - bez dropdown -->
            <div class="hidden items-center sm:ms-6 sm:flex">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="hidden text-sm font-semibold text-slate-600 lg:inline">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="{{ $actionBase }} border border-slate-200 bg-white text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                Iziet
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}"
                           class="{{ $actionBase }} text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                            Pieslēgties
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="{{ $actionBase }} bg-blue-600 text-white shadow-sm shadow-blue-600/20 hover:bg-blue-700">
                                Reģistrēties
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-blue-50 hover:text-blue-700">
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

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white sm:hidden">

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
                   class="{{ $responsiveBase }} {{ $responsiveInactive }}">
                    Mani sludinājumi
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="{{ $responsiveBase }} {{ $isFavorites ? $responsiveActive : $responsiveInactive }}">
                    Favorīti
                </a>

                <a href="{{ route('ads.create') }}"
                   class="{{ $responsiveBase }} {{ $isAdsCreate ? $responsiveActive : $responsiveInactive }}">
                    Pievienot sludinājumu
                </a>

                @if(Route::has('messages.index'))
                    <a href="{{ route('messages.index') }}"
                       class="{{ $responsiveBase }} {{ $isMessages ? $responsiveActive : $responsiveInactive }}">
                        Ziņojumi
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}"
                       class="{{ $responsiveBase }} {{ $isAdmin ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-transparent text-amber-600 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700' }}">
                        Admin
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <div class="border-t border-slate-200 px-4 py-4">
                <div>
                    <div class="font-semibold text-slate-800">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-sm font-medium text-slate-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        Profils
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="flex h-11 w-full items-center justify-center rounded-xl border border-red-200 bg-red-50 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                            Iziet
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 border-t border-slate-200 px-4 py-4">
                <a href="{{ route('login') }}"
                   class="flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    Pieslēgties
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="flex h-11 items-center justify-center rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700">
                        Reģistrēties
                    </a>
                @endif
            </div>
        @endauth
    </div>
</nav>