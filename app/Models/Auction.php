<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auction extends Model
{
    protected $fillable = [
        'product_id',
        'starting_price',
        'reserve_price',
        'start_date',
        'end_date',
        'status'
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }


    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }


    public function isActive()
    {
        return now()->between($this->start_date, $this->end_date);
    }


    public function highestBid()
    {
        return $this->bids()->max('amount') ?? $this->starting_price;
    }


    public function hasEnded()
    {
        return now()->greaterThan($this->end_date);
    }


    public function canReceiveBid($amount)
    {
        if (!$this->isActive()) {
            return false;
        }

        return $amount > $this->highestBid();
    }


    public function isPending()
    {
        return $this->status === 'pending';
    }

    
    public function isEnded()
    {
        return $this->status === 'ended';
    }
}
