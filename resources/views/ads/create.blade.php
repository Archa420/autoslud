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
@endphp

<div
    class="mx-auto max-w-3xl"
    x-data="{
        isAuction: {{ old('is_auction') ? 'true' : 'false' }},
        selectedBrand: @js(old('brand', '')),
        selectedModel: @js(old('model', '')),
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
        },

        removeImage(index) {
            URL.revokeObjectURL(this.previews[index].url);
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
        },

        enableAuction() {
            this.isAuction = true;
        }
    }"
>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Pievienot sludinājumu
            </h1>

            <p class="mt-2 text-slate-600">
                Aizpildi auto informāciju, pievieno attēlus un izvēlies pārdošanas veidu.
            </p>
        </div>

        <a href="{{ route('ads.index') }}"
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
        <form method="POST" action="{{ route('ads.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        Sludinājuma informācija
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Šī informācija būs redzama sludinājuma kartītē un detalizētajā skatā.
                    </p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Virsraksts</label>
                    <input
                        name="title"
                        value="{{ old('title') }}"
                        required
                        class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                        placeholder="Piemēram: BMW 320d F30"
                    >
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Apraksts</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                        placeholder="Apraksti auto stāvokli, komplektāciju, remontus un citus svarīgus faktus..."
                    >{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-lg font-semibold text-slate-950">
                    Auto informācija
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Marka un modelis jāizvēlas no saraksta, lai dati sistēmā būtu korekti.
                </p>

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
                            value="{{ old('year') }}"
                            required
                            min="1950"
                            max="{{ date('Y') + 1 }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: 2016"
                        >
                        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Nobraukums (km)</label>
                        <input
                            type="number"
                            name="mileage_km"
                            value="{{ old('mileage_km') }}"
                            required
                            min="0"
                            max="2000000"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: 245000"
                        >
                        @error('mileage_km') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Motora tilpums (cc)</label>
                        <input
                            type="number"
                            name="engine_cc"
                            value="{{ old('engine_cc') }}"
                            required
                            min="500"
                            max="9000"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: 1998"
                        >
                        @error('engine_cc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-800">Krāsa</label>
                        <input
                            name="color"
                            value="{{ old('color') }}"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: Melna"
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
                                <option value="{{ $key }}" @selected(old('fuel_type') === $key)>
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
                            value="{{ old('doors_count') }}"
                            required
                            min="2"
                            max="6"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: 5"
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
                                <option value="{{ $key }}" @selected(old('gearbox_type') === $key)>
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
                            value="{{ old('gears_count') }}"
                            required
                            min="3"
                            max="10"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: 6"
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
                                <option value="{{ $bodyType }}" @selected(old('body_type') === $bodyType)>
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
                            value="{{ old('location') }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: Rīga"
                        >
                        @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-semibold text-slate-800">Kontakti</label>
                        <input
                            name="contacts"
                            value="{{ old('contacts') }}"
                            class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                            placeholder="Piemēram: +371 20000000 vai epasts"
                        >
                        @error('contacts') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section>
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">
                        Attēli
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Spied uz kvadrāta, lai pievienotu attēlus. Maksimums 10.
                    </p>
                </div>

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

                            <div class="absolute inset-x-0 bottom-0 bg-black/55 px-2 py-1">
                                <p class="truncate text-[11px] text-white" x-text="image.name"></p>
                            </div>

                            <div
                                x-show="index === 0"
                                class="absolute left-2 top-2 rounded-full bg-amber-300 px-2 py-1 text-[10px] font-bold text-slate-950"
                            >
                                Galvenais
                            </div>
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
                    Pārdošanas veids
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Izvēlies, vai auto tiks pārdots par fiksētu cenu vai izsolē.
                </p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label
                        class="cursor-pointer rounded-2xl border p-4 transition"
                        :class="!isAuction ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-200 bg-slate-50 text-slate-900'"
                    >
                        <input
                            type="radio"
                            name="is_auction"
                            value="0"
                            class="hidden"
                            :checked="!isAuction"
                            @change="isAuction = false"
                        >
                        <span class="block text-sm font-bold">Fiksēta cena</span>
                        <span class="mt-1 block text-xs opacity-70">
                            Parasts sludinājums ar noteiktu pārdošanas cenu.
                        </span>
                    </label>

                    <label
                        class="cursor-pointer rounded-2xl border p-4 transition"
                        :class="isAuction ? 'border-amber-300 bg-amber-50 text-slate-950' : 'border-slate-200 bg-slate-50 text-slate-900'"
                    >
                        <input
                            type="radio"
                            name="is_auction"
                            value="1"
                            class="hidden"
                            :checked="isAuction"
                            @change="isAuction = true"
                        >
                        <span class="block text-sm font-bold">Izsole</span>
                        <span class="mt-1 block text-xs opacity-70">
                            Lietotāji var solīt augstāku cenu līdz izsoles beigām.
                        </span>
                    </label>
                </div>

                <div x-show="!isAuction" x-cloak class="mt-5">
                    <label class="text-sm font-semibold text-slate-800">
                        Cena
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="{{ old('price') }}"
                        class="mt-2 w-full rounded-xl border-slate-300 focus:border-amber-400 focus:ring-amber-200"
                        placeholder="Piemēram: 8500.00"
                        :disabled="isAuction"
                    >

                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div
                    x-show="isAuction"
                    x-cloak
                    class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4"
                >
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-800">
                                Sākumcena
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="starting_bid"
                                value="{{ old('starting_bid') }}"
                                class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                                placeholder="Piemēram: 5000.00"
                                @input="enableAuction()"
                                :disabled="!isAuction"
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
                                value="{{ old('auction_ends_at') }}"
                                class="mt-2 w-full rounded-xl border-slate-300 bg-white focus:border-amber-400 focus:ring-amber-200"
                                @input="enableAuction()"
                                :disabled="!isAuction"
                            >

                            @error('auction_ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-amber-800">
                        Izsoles sludinājumam parastā cena netiek saglabāta. Tiek izmantota sākumcena un pašreizējā izsoles cena.
                    </p>
                </div>
            </section>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                <a href="{{ route('ads.index') }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Atcelt
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Saglabāt sludinājumu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection