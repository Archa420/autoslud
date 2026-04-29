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
    class="flex flex-col gap-6"
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
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold tracking-tight text-slate-900">
                Sludinājumi
            </h1>
            <p class="mt-2 text-slate-600">
                Meklē auto pēc markas, modeļa, cenas, gada, lokācijas un tehniskajiem parametriem.
            </p>
        </div>

        @auth
            <a href="{{ route('ads.create') }}"
               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                + Pievienot sludinājumu
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                + Pievienot sludinājumu
            </a>
        @endauth
    </div>

    <form method="GET" action="{{ route('ads.index') }}" class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm md:p-5">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">

            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-700">Meklēšana</label>
                <input name="q"
                       value="{{ request('q') }}"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="piem. BMW 320d, Audi, Toyota">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Tips</label>
                <select name="type"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200">
                    <option value="">Visi</option>
                    <option value="fixed" @selected(request('type') === 'fixed')>Pārdošana</option>
                    <option value="auction" @selected(request('type') === 'auction')>Izsole</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-700">Marka</label>
                <select
                    name="brand"
                    x-model="selectedBrand"
                    @change="resetModel()"
                    class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200"
                >
                    <option value="">Visas markas</option>
                    @foreach(array_keys($brandModels) as $brand)
                        <option value="{{ $brand }}">{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-700">Modelis</label>
                <select
                    name="model"
                    x-model="selectedModel"
                    :disabled="!selectedBrand"
                    class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                >
                    <option value="">Visi modeļi</option>
                    <template x-for="model in models" :key="model">
                        <option :value="model" x-text="model"></option>
                    </template>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-700">Atrašanās vieta</label>
                <input name="location"
                       value="{{ request('location') }}"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="piem. Rīga">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Cena no</label>
                <input name="price_from"
                       value="{{ request('price_from') }}"
                       type="number"
                       min="0"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="0">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Cena līdz</label>
                <input name="price_to"
                       value="{{ request('price_to') }}"
                       type="number"
                       min="0"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="20000">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Gads no</label>
                <input name="year_from"
                       value="{{ request('year_from') }}"
                       type="number"
                       min="1950"
                       max="{{ date('Y') + 1 }}"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="2010">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Gads līdz</label>
                <input name="year_to"
                       value="{{ request('year_to') }}"
                       type="number"
                       min="1950"
                       max="{{ date('Y') + 1 }}"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200 placeholder:text-slate-400"
                       placeholder="{{ date('Y') }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Degviela</label>
                <select name="fuel_type"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200">
                    <option value="">Visi</option>
                    <option value="diesel" @selected(request('fuel_type') === 'diesel')>Dīzelis</option>
                    <option value="petrol" @selected(request('fuel_type') === 'petrol')>Benzīns</option>
                    <option value="petrol_lpg" @selected(request('fuel_type') === 'petrol_lpg')>Benzīns + gāze</option>
                    <option value="hybrid" @selected(request('fuel_type') === 'hybrid')>Hibrīds</option>
                    <option value="electric" @selected(request('fuel_type') === 'electric')>Elektriskais</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Kārba</label>
                <select name="gearbox_type"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-200">
                    <option value="">Visas</option>
                    <option value="manual" @selected(request('gearbox_type') === 'manual')>Manuālā</option>
                    <option value="automatic" @selected(request('gearbox_type') === 'automatic')>Automāts</option>
                </select>
            </div>

            <div class="md:col-span-4 flex items-end gap-2">
                <button
                    class="w-full rounded-xl bg-gradient-to-b from-amber-300 to-amber-200 px-4 py-2 text-sm font-semibold text-slate-950 shadow-sm ring-1 ring-amber-300/70 transition hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-amber-200">
                    Filtrēt
                </button>

                <a href="{{ route('ads.index') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
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
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-500">Aktīvie filtri:</span>

                @foreach($active as $key => $label)
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1 text-xs text-slate-700 ring-1 ring-slate-200">
                        <span class="text-slate-400">{{ $label }}:</span>
                        <span class="font-semibold">{{ request($key) }}</span>
                    </span>
                @endforeach
            </div>
        @endif
    </form>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-600">
            Atrasti:
            <span class="font-semibold text-slate-900">{{ $ads->total() }}</span>
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

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <a href="{{ route('ads.show', $ad) }}" class="block">
                    <div class="relative h-44 w-full bg-slate-100">
                        @if($img)
                            <img
                                src="{{ asset('storage/' . $img) }}"
                                alt="{{ $ad->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                Nav bildes
                            </div>
                        @endif

                        <div class="absolute left-3 top-3">
                            @if($isAuction)
                                <span class="rounded-full border border-amber-300 bg-amber-100/95 px-3 py-1 text-xs font-bold text-amber-800 shadow-sm">
                                    Izsole
                                </span>
                            @else
                                <span class="rounded-full border border-emerald-200 bg-emerald-50/95 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                    Pārdošana
                                </span>
                            @endif
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="line-clamp-1 text-base font-semibold text-slate-900">
                        {{ $ad->title }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $ad->brand }} {{ $ad->model }} · {{ $ad->year }} · {{ number_format($ad->mileage_km ?? 0, 0, '.', ' ') }} km
                    </p>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-100">
                            <div class="text-slate-400">Degviela</div>
                            <div class="font-semibold text-slate-800">
                                {{ $fuelMap[$ad->fuel_type] ?? $ad->fuel_type }}
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-100">
                            <div class="text-slate-400">Kārba</div>
                            <div class="font-semibold text-slate-800">
                                {{ $gearboxMap[$ad->gearbox_type] ?? $ad->gearbox_type }}
                            </div>
                        </div>

                        <div class="col-span-2 rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-100">
                            <div class="text-slate-400">Atrašanās vieta</div>
                            <div class="font-semibold text-slate-800">
                                {{ $ad->location ?: 'Nav norādīts' }}
                            </div>
                        </div>
                    </div>

                    @if($ad->description)
                        <p class="mt-3 line-clamp-2 text-sm text-slate-600">
                            {{ $ad->description }}
                        </p>
                    @endif

                    <div class="mt-4 rounded-xl border {{ $isAuction ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50' }} px-3 py-3">
                        <p class="text-xs font-semibold {{ $isAuction ? 'text-amber-700' : 'text-slate-500' }}">
                            {{ $isAuction ? 'Pašreizējā izsoles cena' : 'Cena' }}
                        </p>

                        <p class="mt-1 text-xl font-bold text-slate-900">
                            {{ $displayPrice !== null ? number_format($displayPrice, 2, '.', ' ') . ' €' : '—' }}
                        </p>

                        @if($isAuction)
                            <div class="mt-2 text-xs text-slate-600">
                                <p>
                                    Sākumcena:
                                    <span class="font-semibold text-slate-900">
                                        {{ number_format($auction->starting_bid, 2, '.', ' ') }} €
                                    </span>
                                </p>

                                @if($auction->ends_at)
                                    <p>
                                        Beidzas:
                                        <span class="font-semibold text-slate-900">
                                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('ads.show', $ad) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Atvērt sludinājumu
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500 shadow-sm">
                Nav neviena sludinājuma ar šādiem filtriem.
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $ads->appends(request()->query())->links() }}
    </div>
</div>
@endsection