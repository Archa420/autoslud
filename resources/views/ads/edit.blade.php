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

    $fuelTypes = [
        'diesel' => 'Dīzelis',
        'petrol' => 'Benzīns',
        'petrol_lpg' => 'Benzīns + gāze',
        'hybrid' => 'Hibrīds',
        'electric' => 'Elektriskais',
    ];

    $gearboxTypes = [
        'manual' => 'Manuālā',
        'automatic' => 'Automāts',
    ];

    $bodyTypes = [
        'Sedans',
        'Universālis',
        'Hečbeks',
        'Kupeja',
        'Kabriolets',
        'SUV',
        'Minivens',
        'Pikaps',
        'Furgons',
    ];

    $auction = $ad->auction;
@endphp

<div
    class="mx-auto max-w-3xl"
    x-data="{
        selectedBrand: @js(old('brand', $ad->brand)),
        selectedModel: @js(old('model', $ad->model)),
        brandModels: @js($brandModels),

        files: [],
        previews: [],

        get models() {
            return this.selectedBrand && this.brandModels[this.selectedBrand]
                ? this.brandModels[this.selectedBrand]
                : [];
        },

        resetModel() {
            this.selectedModel = '';
        },

        addImages(event) {
            const incoming = Array.from(event.target.files);

            incoming.forEach((file) => {
                if (this.files.length >= 10) return;

                const exists = this.files.some(existing =>
                    existing.name === file.name &&
                    existing.size === file.size &&
                    existing.lastModified === file.lastModified
                );

                if (!exists) {
                    this.files.push(file);
                    this.previews.push({
                        name: file.name,
                        url: URL.createObjectURL(file)
                    });
                }
            });

            this.syncInput();
            event.target.value = '';
        },

        removeImage(index) {
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            const dataTransfer = new DataTransfer();

            this.files.forEach(file => {
                dataTransfer.items.add(file);
            });

            this.$refs.imagesInput.files = dataTransfer.files;
        }
    }"
>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Rediģēt sludinājumu
            </h1>

            <p class="mt-2 text-slate-600">
                Atjauno sludinājuma informāciju un, ja nepieciešams, pievieno jaunus attēlus.
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Atpakaļ
        </a>
    </div>

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('ads.update', $ad) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        Sludinājuma informācija
                    </h2>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Virsraksts</label>
                    <input
                        name="title"
                        value="{{ old('title', $ad->title) }}"
                        required
                        class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                    >
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Apraksts</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                    >{{ old('description', $ad->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-lg font-semibold text-slate-950">
                    Auto informācija
                </h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-800">Marka</label>
                        <select
                            name="brand"
                            x-model="selectedBrand"
                            @change="resetModel()"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                            <option value="">Izvēlies marku</option>
                            @foreach(array_keys($brandModels) as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                        @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Modelis</label>
                        <select
                            name="model"
                            x-model="selectedModel"
                            required
                            :disabled="!selectedBrand"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                        >
                            <option value="">Izvēlies modeli</option>
                            <template x-for="model in models" :key="model">
                                <option :value="model" x-text="model"></option>
                            </template>
                        </select>
                        @error('model') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Gads</label>
                        <input
                            type="number"
                            name="year"
                            value="{{ old('year', $ad->year) }}"
                            required
                            min="1950"
                            max="{{ date('Y') + 1 }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Nobraukums (km)</label>
                        <input
                            type="number"
                            name="mileage_km"
                            value="{{ old('mileage_km', $ad->mileage_km) }}"
                            required
                            min="0"
                            max="2000000"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('mileage_km') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Motora tilpums (cc)</label>
                        <input
                            type="number"
                            name="engine_cc"
                            value="{{ old('engine_cc', $ad->engine_cc) }}"
                            required
                            min="500"
                            max="9000"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('engine_cc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Krāsa</label>
                        <input
                            name="color"
                            value="{{ old('color', $ad->color) }}"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Degvielas tips</label>
                        <select
                            name="fuel_type"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                            <option value="">Izvēlies</option>
                            @foreach($fuelTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('fuel_type', $ad->fuel_type) === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('fuel_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Durvju skaits</label>
                        <input
                            type="number"
                            name="doors_count"
                            value="{{ old('doors_count', $ad->doors_count) }}"
                            required
                            min="2"
                            max="6"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('doors_count') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Kārbas tips</label>
                        <select
                            name="gearbox_type"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                            <option value="">Izvēlies</option>
                            @foreach($gearboxTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('gearbox_type', $ad->gearbox_type) === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('gearbox_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Ātrumu skaits</label>
                        <input
                            type="number"
                            name="gears_count"
                            value="{{ old('gears_count', $ad->gears_count) }}"
                            required
                            min="3"
                            max="10"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('gears_count') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Virsbūves tips</label>
                        <select
                            name="body_type"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                            <option value="">Izvēlies</option>
                            @foreach($bodyTypes as $bodyType)
                                <option value="{{ $bodyType }}" @selected(old('body_type', $ad->body_type) === $bodyType)>
                                    {{ $bodyType }}
                                </option>
                            @endforeach
                        </select>
                        @error('body_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Atrašanās vieta</label>
                        <input
                            name="location"
                            value="{{ old('location', $ad->location) }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-semibold text-slate-800">Kontakti</label>
                        <input
                            name="contacts"
                            value="{{ old('contacts', $ad->contacts) }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                        >
                        @error('contacts') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-950">
                    Esošie attēli
                </h2>

                @if($ad->images->count())
                    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
                        @foreach($ad->images as $image)
                            <div class="aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="{{ $ad->title }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-sm text-slate-500">
                        Attēli vēl nav pievienoti.
                    </p>
                @endif
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-950">
                    Pievienot jaunus attēlus
                </h2>

                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
                    <template x-for="(image, index) in previews" :key="index">
                        <div class="relative aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                            <img :src="image.url" :alt="image.name" class="h-full w-full object-cover">

                            <button
                                type="button"
                                @click="removeImage(index)"
                                class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/70 text-sm font-bold text-white hover:bg-red-600"
                            >
                                ×
                            </button>
                        </div>
                    </template>

                    <label
                        for="images"
                        x-show="files.length < 10"
                        class="group flex aspect-square cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 text-center transition hover:border-amber-400 hover:bg-amber-50"
                    >
                        <span class="text-3xl font-light text-slate-400 group-hover:text-amber-500">+</span>
                        <span class="mt-2 px-2 text-xs font-semibold text-slate-600">
                            Pievienot
                        </span>
                        <span class="mt-1 px-2 text-[11px] text-slate-400">
                            <span x-text="files.length"></span>/10
                        </span>
                    </label>
                </div>

                <input
                    id="images"
                    x-ref="imagesInput"
                    type="file"
                    name="images[]"
                    multiple
                    accept="image/*"
                    class="hidden"
                    @change="addImages($event)"
                >

                @error('images') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('images.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-950">
                    Pārdošanas informācija
                </h2>

                @if($auction)
                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">
                            Šis ir izsoles sludinājums.
                        </p>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-800">
                                    Sākumcena
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="starting_bid"
                                    value="{{ old('starting_bid', $auction->starting_bid) }}"
                                    class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                                >

                                @error('starting_bid') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-slate-800">
                                    Beigu datums / laiks
                                </label>

                                <input
                                    type="datetime-local"
                                    name="auction_ends_at"
                                    value="{{ old('auction_ends_at', optional($auction->ends_at)->format('Y-m-d\TH:i')) }}"
                                    class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                                >

                                @error('auction_ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4">
                        <label class="text-sm font-semibold text-slate-800">
                            Cena
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            value="{{ old('price', $ad->price) }}"
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                        >

                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif
            </section>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                <a href="{{ route('dashboard') }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Atcelt
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Saglabāt izmaiņas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection