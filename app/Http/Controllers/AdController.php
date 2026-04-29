<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdController extends Controller
{
    private array $brandModels = [
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

    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $type = $request->string('type')->toString();

        $brand = $request->string('brand')->toString();
        $model = $request->string('model')->toString();
        $location = $request->string('location')->toString();

        $priceFrom = $request->input('price_from');
        $priceTo = $request->input('price_to');

        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');

        $fuelType = $request->string('fuel_type')->toString();
        $gearboxType = $request->string('gearbox_type')->toString();

        $ads = Ad::query()
            ->with(['primaryImage', 'images', 'auction'])

            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%")
                        ->orWhere('model', 'like', "%{$q}%");
                });
            })

            ->when($type === 'auction', function ($query) {
                $query->whereHas('auction');
            })

            ->when($type === 'fixed', function ($query) {
                $query->whereDoesntHave('auction');
            })

            ->when($brand, function ($query) use ($brand) {
                $query->where('brand', $brand);
            })

            ->when($model, function ($query) use ($model) {
                $query->where('model', $model);
            })

            ->when($location, function ($query) use ($location) {
                $query->where('location', 'like', "%{$location}%");
            })

            ->when($yearFrom !== null && $yearFrom !== '', function ($query) use ($yearFrom) {
                $query->where('year', '>=', $yearFrom);
            })

            ->when($yearTo !== null && $yearTo !== '', function ($query) use ($yearTo) {
                $query->where('year', '<=', $yearTo);
            })

            ->when($fuelType, function ($query) use ($fuelType) {
                $query->where('fuel_type', $fuelType);
            })

            ->when($gearboxType, function ($query) use ($gearboxType) {
                $query->where('gearbox_type', $gearboxType);
            })

            ->when($priceFrom !== null && $priceFrom !== '', function ($query) use ($priceFrom) {
                $query->where(function ($qq) use ($priceFrom) {
                    $qq->where(function ($q1) use ($priceFrom) {
                        $q1->whereDoesntHave('auction')
                            ->where('price', '>=', $priceFrom);
                    })->orWhereHas('auction', function ($q2) use ($priceFrom) {
                        $q2->where(function ($auctionQuery) use ($priceFrom) {
                            $auctionQuery->where('current_bid', '>=', $priceFrom)
                                ->orWhere(function ($fallbackQuery) use ($priceFrom) {
                                    $fallbackQuery->whereNull('current_bid')
                                        ->where('starting_bid', '>=', $priceFrom);
                                });
                        });
                    });
                });
            })

            ->when($priceTo !== null && $priceTo !== '', function ($query) use ($priceTo) {
                $query->where(function ($qq) use ($priceTo) {
                    $qq->where(function ($q1) use ($priceTo) {
                        $q1->whereDoesntHave('auction')
                            ->where('price', '<=', $priceTo);
                    })->orWhereHas('auction', function ($q2) use ($priceTo) {
                        $q2->where(function ($auctionQuery) use ($priceTo) {
                            $auctionQuery->where('current_bid', '<=', $priceTo)
                                ->orWhere(function ($fallbackQuery) use ($priceTo) {
                                    $fallbackQuery->whereNull('current_bid')
                                        ->where('starting_bid', '<=', $priceTo);
                                });
                        });
                    });
                });
            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('sludinajumi', compact('ads'));
    }

    public function create()
    {
        return view('ads.create');
    }

    public function show(Ad $ad)
    {
        $ad->load([
            'primaryImage',
            'images',
            'auction.bids.user',
            'auction.highestBid.user',
            'favorites',
        ]);

        return view('ads.show', compact('ad'));
    }

    public function store(Request $request)
    {
        if (!$request->user()) {
            abort(403);
        }

        $allowedBrands = array_keys($this->brandModels);
        $selectedBrand = $request->input('brand');
        $allowedModels = $this->brandModels[$selectedBrand] ?? [];

        $isAuction = $request->boolean('is_auction');

        $rules = [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'brand' => ['required', 'string', Rule::in($allowedBrands)],
            'model' => ['required', 'string', Rule::in($allowedModels)],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'engine_cc' => ['required', 'integer', 'min:500', 'max:9000'],
            'mileage_km' => ['required', 'integer', 'min:0', 'max:2000000'],
            'color' => ['required', 'string', 'max:40'],
            'gearbox_type' => ['required', 'in:manual,automatic'],
            'gears_count' => ['required', 'integer', 'min:3', 'max:10'],
            'fuel_type' => ['required', 'in:diesel,petrol,petrol_lpg,hybrid,electric'],
            'doors_count' => ['required', 'integer', 'min:2', 'max:6'],
            'body_type' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'contacts' => ['nullable', 'string', 'max:255'],
            'is_auction' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        if ($isAuction) {
            $rules['price'] = ['nullable', 'numeric', 'min:0'];
            $rules['starting_bid'] = ['required', 'numeric', 'min:1'];
            $rules['auction_ends_at'] = ['required', 'date', 'after:now'];
        } else {
            $rules['price'] = ['required', 'numeric', 'min:1'];
            $rules['starting_bid'] = ['nullable', 'numeric', 'min:0'];
            $rules['auction_ends_at'] = ['nullable', 'date'];
        }

        $data = $request->validate($rules, [
            'brand.in' => 'Lūdzu izvēlies marku no saraksta.',
            'model.in' => 'Lūdzu izvēlies modeli, kas atbilst izvēlētajai markai.',
            'price.required' => 'Parastam sludinājumam cena ir obligāta.',
            'price.min' => 'Cenai jābūt vismaz 1 €.',
            'starting_bid.required' => 'Izsoles sludinājumam sākumcena ir obligāta.',
            'starting_bid.min' => 'Sākumcenai jābūt vismaz 1 €.',
            'auction_ends_at.required' => 'Izsoles beigu datums ir obligāts.',
            'auction_ends_at.after' => 'Izsoles beigu datumam jābūt nākotnē.',
            'images.max' => 'Var pievienot ne vairāk kā 10 attēlus.',
            'images.*.image' => 'Failam jābūt attēlam.',
            'images.*.mimes' => 'Attēlam jābūt jpg, jpeg, png vai webp formātā.',
            'images.*.max' => 'Attēls nedrīkst būt lielāks par 5 MB.',
        ]);

        return DB::transaction(function () use ($request, $data, $isAuction) {
            $ad = Ad::create([
                'user_id' => $request->user()->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'brand' => $data['brand'],
                'model' => $data['model'],
                'year' => $data['year'],
                'engine_cc' => $data['engine_cc'],
                'mileage_km' => $data['mileage_km'],
                'color' => $data['color'],
                'gearbox_type' => $data['gearbox_type'],
                'gears_count' => $data['gears_count'],
                'fuel_type' => $data['fuel_type'],
                'doors_count' => $data['doors_count'],
                'body_type' => $data['body_type'] ?? null,
                'location' => $data['location'] ?? null,
                'contacts' => $data['contacts'] ?? null,
                'price' => $isAuction ? null : $data['price'],
                'status' => 'active',
            ]);

            if ($isAuction) {
                $ad->auction()->create([
                    'starting_bid' => $data['starting_bid'],
                    'current_bid' => null,
                    'minimum_bid_step' => 1.00,
                    'starts_at' => now(),
                    'ends_at' => $data['auction_ends_at'],
                    'status' => 'active',
                    'winner_user_id' => null,
                ]);
            }

            foreach ($request->file('images', []) as $i => $file) {
                $path = $file->store("ads/{$ad->id}", 'public');

                $ad->images()->create([
                    'path' => $path,
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }

            return redirect()
                ->route('ads.show', $ad)
                ->with('status', 'Sludinājums pievienots!');
        });
    }

    public function edit(Request $request, Ad $ad)
    {
        if (!$request->user() || $request->user()->id !== $ad->user_id) {
            abort(403);
        }

        $ad->load(['images', 'auction']);

        return view('ads.edit', compact('ad'));
    }

    public function update(Request $request, Ad $ad)
    {
        if (!$request->user() || $request->user()->id !== $ad->user_id) {
            abort(403);
        }

        $allowedBrands = array_keys($this->brandModels);
        $selectedBrand = $request->input('brand');
        $allowedModels = $this->brandModels[$selectedBrand] ?? [];

        $hasAuction = $ad->auction()->exists();

        $rules = [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'brand' => ['required', 'string', Rule::in($allowedBrands)],
            'model' => ['required', 'string', Rule::in($allowedModels)],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'engine_cc' => ['required', 'integer', 'min:500', 'max:9000'],
            'mileage_km' => ['required', 'integer', 'min:0', 'max:2000000'],
            'color' => ['required', 'string', 'max:40'],
            'gearbox_type' => ['required', 'in:manual,automatic'],
            'gears_count' => ['required', 'integer', 'min:3', 'max:10'],
            'fuel_type' => ['required', 'in:diesel,petrol,petrol_lpg,hybrid,electric'],
            'doors_count' => ['required', 'integer', 'min:2', 'max:6'],
            'body_type' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'contacts' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        if ($hasAuction) {
            $rules['price'] = ['nullable', 'numeric', 'min:0'];
            $rules['starting_bid'] = ['required', 'numeric', 'min:1'];
            $rules['auction_ends_at'] = ['required', 'date'];
        } else {
            $rules['price'] = ['required', 'numeric', 'min:1'];
        }

        $data = $request->validate($rules, [
            'brand.in' => 'Lūdzu izvēlies marku no saraksta.',
            'model.in' => 'Lūdzu izvēlies modeli, kas atbilst izvēlētajai markai.',
            'price.required' => 'Cena ir obligāta.',
            'price.min' => 'Cenai jābūt vismaz 1 €.',
            'starting_bid.required' => 'Izsoles sākumcena ir obligāta.',
            'starting_bid.min' => 'Sākumcenai jābūt vismaz 1 €.',
            'auction_ends_at.required' => 'Izsoles beigu datums ir obligāts.',
            'images.max' => 'Var pievienot ne vairāk kā 10 attēlus.',
            'images.*.image' => 'Failam jābūt attēlam.',
            'images.*.mimes' => 'Attēlam jābūt jpg, jpeg, png vai webp formātā.',
            'images.*.max' => 'Attēls nedrīkst būt lielāks par 5 MB.',
        ]);

        return DB::transaction(function () use ($request, $ad, $data, $hasAuction) {
            $ad->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'brand' => $data['brand'],
                'model' => $data['model'],
                'year' => $data['year'],
                'engine_cc' => $data['engine_cc'],
                'mileage_km' => $data['mileage_km'],
                'color' => $data['color'],
                'gearbox_type' => $data['gearbox_type'],
                'gears_count' => $data['gears_count'],
                'fuel_type' => $data['fuel_type'],
                'doors_count' => $data['doors_count'],
                'body_type' => $data['body_type'] ?? null,
                'location' => $data['location'] ?? null,
                'contacts' => $data['contacts'] ?? null,
                'price' => $hasAuction ? null : $data['price'],
            ]);

            if ($hasAuction && $ad->auction) {
                $ad->auction->update([
                    'starting_bid' => $data['starting_bid'],
                    'ends_at' => $data['auction_ends_at'],
                ]);
            }

            $startOrder = $ad->images()->count();

            foreach ($request->file('images', []) as $i => $file) {
                $path = $file->store("ads/{$ad->id}", 'public');

                $ad->images()->create([
                    'path' => $path,
                    'sort_order' => $startOrder + $i,
                    'is_primary' => $ad->images()->count() === 0 && $i === 0,
                ]);
            }

            return redirect()
                ->route('ads.show', $ad)
                ->with('status', 'Sludinājums atjaunināts!');
        });
    }

    public function destroy(Request $request, Ad $ad)
    {
        if (!$request->user() || $request->user()->id !== $ad->user_id) {
            abort(403);
        }

        $ad->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Sludinājums izdzēsts!');
    }
}