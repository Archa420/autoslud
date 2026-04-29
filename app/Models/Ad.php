<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'brand',
        'model',
        'year',
        'engine_cc',
        'mileage_km',
        'color',
        'gearbox_type',
        'gears_count',
        'fuel_type',
        'doors_count',
        'body_type',
        'location',
        'contacts',
        'price',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'engine_cc' => 'integer',
        'mileage_km' => 'integer',
        'gears_count' => 'integer',
        'doors_count' => 'integer',
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AdImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(AdImage::class)->where('is_primary', true);
    }

    public function auction(): HasOne
    {
        return $this->hasOne(Auction::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    protected static function booted(): void
    {
        static::deleting(function (Ad $ad) {
            $ad->loadMissing('images');

            foreach ($ad->images as $img) {
                Storage::disk('public')->delete($img->path);
            }
        });
    }
}