<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl shadow-sm">
    @php
        $isHome = request()->routeIs('home');
        $isAds = request()->routeIs('ads.index') || request()->routeIs('ads.show');
        $isIzsoles = request()->routeIs('izsoles');
        $isDashboard = request()->routeIs('dashboard');
        $isFavorites = request()->routeIs('favorites.index');
        $isAdsCreate = request()->routeIs('ads.create');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">

            <!-- Left side -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-500 text-white shadow-md group-hover:scale-105 transition duration-200">
                        <x-application-logo class="h-6 w-6 fill-current text-white" />
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-lg font-bold text-slate-900 leading-tight">TavaPlatforma</div>
                        <div class="text-xs text-slate-500 -mt-0.5">Sludinājumi un izsoles</div>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center rounded-2xl border border-slate-200 bg-white/70 px-2 py-2 shadow-sm">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $isHome ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Sākums
                    </a>

                    <a href="{{ route('ads.index') }}"
                       class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $isAds ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Sludinājumi
                    </a>

                    <a href="{{ route('izsoles') }}"
                       class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $isIzsoles ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Izsoles
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $isDashboard ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Mans profils
                        </a>

                        <a href="{{ route('favorites.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $isFavorites ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Favorīti
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <a href="{{ route('ads.create') }}"
                       class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg hover:scale-[1.02] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Pievienot sludinājumu
                    </a>

                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 shadow-sm hover:shadow-md hover:border-slate-300 transition">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-slate-700">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <div class="text-left">
                                    <div class="text-sm font-semibold text-slate-800 leading-tight">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Mans profils
                                    </div>
                                </div>

                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</div>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                Profils
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard')">
                                Mani sludinājumi
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('favorites.index')">
                                Favorīti
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('ads.create')">
                                Pievienot sludinājumu
                            </x-dropdown-link>

                            <div class="border-t border-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    Iziet
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Pieslēgties
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="rounded-2xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                            Reģistrēties
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Mobile hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2.5 text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
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

    <!-- Mobile menu -->
    <div x-show="open"
         x-transition
         class="sm:hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('home') }}"
               class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isHome ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                Sākums
            </a>

            <a href="{{ route('ads.index') }}"
               class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isAds ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                Sludinājumi
            </a>

            <a href="{{ route('izsoles') }}"
               class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isIzsoles ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                Izsoles
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isDashboard ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Mans profils
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition {{ $isFavorites ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Favorīti
                </a>

                <a href="{{ route('ads.create') }}"
                   class="flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-sm">
                    Pievienot sludinājumu
                </a>
            @endauth
        </div>

        @auth
            <div class="border-t border-slate-200 px-4 py-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                        Profils
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            Iziet
                        </a>
                    </form>
                </div>
            </div>
        @else
            <div class="border-t border-slate-200 px-4 py-4 space-y-2">
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50 transition">
                    Pieslēgties
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Reģistrēties
                    </a>
                @endif
            </div>
        @endauth
    </div>
</nav>