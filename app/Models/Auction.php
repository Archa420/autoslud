<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auction extends Model
{
    protected $fillable = [
        'ad_id',
        'starting_bid',
        'current_bid',
        'minimum_bid_step',
        'starts_at',
        'ends_at',
        'status',
        'winner_user_id',
    ];

    protected $casts = [
        'starting_bid' => 'decimal:2',
        'current_bid' => 'decimal:2',
        'minimum_bid_step' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }
    public function highestBid(): HasOne
    {
        return $this->hasOne(Bid::class)->ofMany('amount', 'max');
    }
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }
}