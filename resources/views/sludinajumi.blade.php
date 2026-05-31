@extends('layouts.app')

@section('content')
@php
    $brandModels = [
        'Audi' => ['A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'R8', 'TT'],
        'BMW' => ['116i', '118d', '120d', '318d', '320d', '328i', '330i', '330d', '520d', '530d', 'X1', 'X3', 'X5', 'X6', 'M3', 'M4', 'M5'],
        'Mercedes-Benz' => ['A-Class', 'C-Class', 'E-Class', 'S-Class', 'CLA', 'CLS', 'GLA', 'GLC', 'GLE', 'G-Class', 'Sprinter', 'Vito'],
        'Volkswagen' => ['Golf', 'Passat', 'Polo', 'Tiguan', 'Touareg', 'Touran', 'Arteon', 'Caddy', 'Transporter'],
        'Toyota' => ['Yaris', 'Auris', 'Corolla', 'Avensis', 'Camry', 'Prius', 'RAV4', 'Land Cruiser', 'Hilux'],
        'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'Jazz'],
        'Ford' => ['Fiesta', 'Focus', 'Mondeo', 'Kuga', 'S-Max', 'Galaxy', 'Transit', 'Mustang'],
        'Opel' => ['Astra', 'Corsa', 'Insignia', 'Zafira', 'Mokka', 'Vivaro'],
        'Volvo' => ['S40', 'S60', 'S80', 'S90', 'V40', 'V60', 'V70', 'V90', 'XC60', 'XC90'],
        'Škoda' => ['Fabia', 'Octavia', 'Superb', 'Rapid', 'Scala', 'Karoq', 'Kodiaq'],
        'Peugeot' => ['208', '308', '508', '2008', '3008', '5008', 'Partner', 'Boxer'],
        'Renault' => ['Clio', 'Megane', 'Laguna', 'Talisman', 'Captur', 'Kadjar', 'Kangoo', 'Trafic'],
        'Nissan' => ['Micra', 'Juke', 'Qashqai', 'X-Trail', 'Navara', 'Leaf'],
        'Hyundai' => ['i20', 'i30', 'i40', 'Tucson', 'Santa Fe', 'Kona', 'IONIQ'],
        'Kia' => ['Ceed', 'Rio', 'Sportage', 'Sorento', 'Optima', 'Stinger', 'Niro'],
        'Mazda' => ['Mazda 2', 'Mazda 3', 'Mazda 6', 'CX-3', 'CX-5', 'CX-7', 'MX-5'],
        'Subaru' => ['Impreza', 'Legacy', 'Forester', 'Outback', 'XV', 'WRX STI'],
        'Lexus' => ['IS', 'GS', 'LS', 'NX', 'RX', 'UX'],
        'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Boxster', 'Cayman', 'Taycan'],
        'Land Rover' => ['Range Rover', 'Range Rover Sport', 'Discovery', 'Discovery Sport', 'Defender', 'Freelander'],
        'Jaguar' => ['XE', 'XF', 'XJ', 'F-Pace', 'E-Pace', 'F-Type'],
        'Tesla' => ['Model S', 'Model 3', 'Model X', 'Model Y'],
        'MINI' => ['Cooper', 'Cooper S', 'Countryman', 'Clubman', 'Paceman'],
        'SEAT' => ['Ibiza', 'Leon', 'Ateca', 'Arona', 'Alhambra'],
        'Fiat' => ['500', 'Panda', 'Tipo', 'Punto', 'Doblo', 'Ducato'],
    ];

    $fuelMap = [
        'diesel' => 'Dīzelis',
        'petrol' => 'Benzīns',
        'petrol_lpg' => 'Benzīns + gāze',
        'hybrid' => 'Hibrīds',
        'electric' => 'Elektriskais',
    ];

    $gearboxMap = [
        'manual' => 'Manuālā',
        'automatic' => 'Automāts',
    ];
@endphp

<div
    class="flex flex-col gap-7 text-white"
    x-data="{
        selectedBrand: @js(request('brand', '')),
        selectedModel: @js(request('model', '')),
        brandModels: @js($brandModels),

        get models() {
            return this.selectedBrand && this.brandModels[this.selectedBrand]
                ? this.brandModels[this.selectedBrand]
                : [];
        },

        resetModel() {
            this.selectedModel = '';
        }
    }"
>
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200/80">
                    Auto katalogs
                </span>
            </div>

            <h1 class="text-4xl font-black uppercase tracking-tight text-white md:text-6xl" style="font-family:'Bebas Neue', sans-serif;">
                Sludinājumi
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                Meklē auto pēc markas, modeļa, cenas, gada, lokācijas un tehniskajiem parametriem.
            </p>
        </div>

        @auth
            <a href="{{ route('ads.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                + Pievienot sludinājumu
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-amber-400 px-5 py-3 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                + Pievienot sludinājumu
            </a>
        @endauth
    </div>

    <form method="GET" action="{{ route('ads.index') }}" class="rounded-3xl border border-white/10 bg-white/[.05] p-4 shadow-2xl shadow-black/20 backdrop-blur-xl md:p-5">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">

            <div class="md:col-span-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Meklēšana</label>
                <input name="q"
                       value="{{ request('q') }}"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="piem. BMW 320d, Audi, Toyota">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Tips</label>
                <select name="type"
                        class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20">
                    <option class="bg-slate-950" value="">Visi</option>
                    <option class="bg-slate-950" value="fixed" @selected(request('type') === 'fixed')>Pārdošana</option>
                    <option class="bg-slate-950" value="auction" @selected(request('type') === 'auction')>Izsole</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Marka</label>
                <select
                    name="brand"
                    x-model="selectedBrand"
                    @change="resetModel()"
                    class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                >
                    <option class="bg-slate-950" value="">Visas markas</option>
                    @foreach(array_keys($brandModels) as $brand)
                        <option class="bg-slate-950" value="{{ $brand }}">{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Modelis</label>
                <select
                    name="model"
                    x-model="selectedModel"
                    :disabled="!selectedBrand"
                    class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 disabled:cursor-not-allowed disabled:bg-slate-900 disabled:text-slate-600"
                >
                    <option class="bg-slate-950" value="">Visi modeļi</option>
                    <template x-for="model in models" :key="model">
                        <option class="bg-slate-950" :value="model" x-text="model"></option>
                    </template>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Atrašanās vieta</label>
                <input name="location"
                       value="{{ request('location') }}"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="piem. Rīga">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Cena no</label>
                <input name="price_from"
                       value="{{ request('price_from') }}"
                       type="number"
                       min="0"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="0">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Cena līdz</label>
                <input name="price_to"
                       value="{{ request('price_to') }}"
                       type="number"
                       min="0"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="20000">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Gads no</label>
                <input name="year_from"
                       value="{{ request('year_from') }}"
                       type="number"
                       min="1950"
                       max="{{ date('Y') + 1 }}"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="2010">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Gads līdz</label>
                <input name="year_to"
                       value="{{ request('year_to') }}"
                       type="number"
                       min="1950"
                       max="{{ date('Y') + 1 }}"
                       class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20"
                       placeholder="{{ date('Y') }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Degviela</label>
                <select name="fuel_type"
                        class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20">
                    <option class="bg-slate-950" value="">Visi</option>
                    <option class="bg-slate-950" value="diesel" @selected(request('fuel_type') === 'diesel')>Dīzelis</option>
                    <option class="bg-slate-950" value="petrol" @selected(request('fuel_type') === 'petrol')>Benzīns</option>
                    <option class="bg-slate-950" value="petrol_lpg" @selected(request('fuel_type') === 'petrol_lpg')>Benzīns + gāze</option>
                    <option class="bg-slate-950" value="hybrid" @selected(request('fuel_type') === 'hybrid')>Hibrīds</option>
                    <option class="bg-slate-950" value="electric" @selected(request('fuel_type') === 'electric')>Elektriskais</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Kārba</label>
                <select name="gearbox_type"
                        class="mt-1 w-full rounded-2xl border-white/10 bg-slate-950/70 text-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20">
                    <option class="bg-slate-950" value="">Visas</option>
                    <option class="bg-slate-950" value="manual" @selected(request('gearbox_type') === 'manual')>Manuālā</option>
                    <option class="bg-slate-950" value="automatic" @selected(request('gearbox_type') === 'automatic')>Automāts</option>
                </select>
            </div>

            <div class="md:col-span-4 flex items-end gap-2">
                <button
                    class="w-full rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400/30">
                    Filtrēt
                </button>

                <a href="{{ route('ads.index') }}"
                   class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-center text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                    Notīrīt
                </a>
            </div>
        </div>

        @php
            $filterLabels = [
                'q' => 'Meklēšana',
                'type' => 'Tips',
                'brand' => 'Marka',
                'model' => 'Modelis',
                'location' => 'Atrašanās vieta',
                'price_from' => 'Cena no',
                'price_to' => 'Cena līdz',
                'year_from' => 'Gads no',
                'year_to' => 'Gads līdz',
                'fuel_type' => 'Degviela',
                'gearbox_type' => 'Kārba',
            ];

            $active = collect($filterLabels)
                ->filter(fn ($label, $key) => request($key) !== null && request($key) !== '');
        @endphp

        @if($active->count())
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-white/10 pt-4">
                <span class="text-xs text-slate-500">Aktīvie filtri:</span>

                @foreach($active as $key => $label)
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-xs text-slate-300 ring-1 ring-white/10">
                        <span class="text-slate-500">{{ $label }}:</span>
                        <span class="font-bold text-white">{{ request($key) }}</span>
                    </span>
                @endforeach
            </div>
        @endif
    </form>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between border-b border-white/10 pb-4">
        <p class="text-sm text-slate-400">
            Atrasti:
            <span class="font-bold text-white">{{ $ads->total() }}</span>
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($ads as $ad)
            @php
                $img = $ad->primaryImage?->path ?? $ad->images->first()?->path;
                $auction = $ad->auction;
                $isAuction = $auction !== null;
                $displayPrice = $isAuction
                    ? ($auction->current_bid ?? $auction->starting_bid)
                    : $ad->price;
            @endphp

            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/[.05] shadow-xl shadow-black/20 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:border-amber-400/30">
                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="relative h-48 w-full overflow-hidden bg-slate-900">
                        @if($img)
                            <img
                                src="{{ asset('storage/' . $img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105 group-hover:opacity-100"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-500">
                                Nav bildes
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                        <div class="absolute left-3 top-3">
                            @if($isAuction)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-950"></span>
                                    Izsole
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-md">
                                    Pārdošana
                                </span>
                            @endif
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="line-clamp-1 text-xl font-black uppercase leading-tight text-white" style="font-family:'Bebas Neue', sans-serif;">
                        {{ $ad->title }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-400">
                        {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                    </p>

                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <div class="text-slate-500">Degviela</div>
                            <div class="font-bold text-slate-200">
                                {{ $fuelMap[$ad->fuel_type] ?? $ad->fuel_type }}
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <div class="text-slate-500">Kārba</div>
                            <div class="font-bold text-slate-200">
                                {{ $gearboxMap[$ad->gearbox_type] ?? $ad->gearbox_type }}
                            </div>
                        </div>

                        <div class="col-span-2 rounded-2xl border border-white/10 bg-slate-950/50 px-3 py-2">
                            <div class="text-slate-500">Atrašanās vieta</div>
                            <div class="font-bold text-slate-200">
                                {{ $ad->location ?: 'Nav norādīts' }}
                            </div>
                        </div>
                    </div>

                    @if($ad->description)
                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-400">
                            {{ $ad->description }}
                        </p>
                    @endif

                    <div class="mt-4 rounded-2xl border {{ $isAuction ? 'border-amber-400/20 bg-amber-400/10' : 'border-white/10 bg-slate-950/50' }} px-3 py-3">
                        <p class="text-xs font-bold uppercase tracking-wider {{ $isAuction ? 'text-amber-300' : 'text-slate-500' }}">
                            {{ $isAuction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                        </p>

                        <p class="mt-1 text-3xl font-black leading-none text-white" style="font-family:'Bebas Neue', sans-serif;">
                            {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                        </p>

                        @if($isAuction)
                            <div class="mt-2 text-xs text-slate-400">
                                <p>
                                    Sākumcena:
                                    <span class="font-bold text-white">
                                        {{ number_format($auction->starting_bid, 2, '.', ' ') }} €
                                    </span>
                                </p>

                                @if($auction->ends_at)
                                    <p>
                                        Beidzas:
                                        <span class="font-bold text-white">
                                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('ads.show', $ad) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-black uppercase tracking-wider text-slate-950 shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 active:scale-95">
                        Atvērt sludinājumu
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-white/10 bg-white/[.05] p-10 text-center text-slate-400 shadow-xl shadow-black/20 backdrop-blur-xl">
                Nav neviena sludinājuma ar šādiem filtriem.
            </div>
        @endforelse
    </div>

    <div class="mt-2 text-slate-300">
        {{ $ads->appends(request()->query())->links() }}
    </div>
</div>
@endsection