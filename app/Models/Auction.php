<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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


    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
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
        return $this->status === 'active'
            && now()->between($this->start_date, $this->end_date);
    }


    public function highestBid()
    {
        return $this->current_highest_bid ?? $this->starting_price;
    }


    public function updateHighestBid($amount)
    {
        if ($this->current_highest_bid === null || $amount > $this->current_highest_bid) {
            $this->current_highest_bid = $amount;
            $this->save();
        }
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


    public function remainingTime()
    {
        return (int) now()->diffInSeconds($this->end_date, false);
    }


    public function placeBid(User $user, $amount)
    {
        return DB::transaction(function () use ($user, $amount) {

            // lock auction row
            $auction = Auction::where('id', $this->id)
                ->lockForUpdate()
                ->first();

            // check auction active
            if (!$auction->isActive()) {
                abort(422, 'Auction is not active.');
            }

            // check seller cannot bid
            if ($user->id === $auction->product->user_id) {
                abort(403, 'You cannot place a bid on your own product.');
            }

            // determine minimum bid
            if ($auction->current_highest_bid === null) {
                $minimumBid = $auction->starting_price;
            } else {
                $minimumBid = $auction->highestBid() + 2;
            }

            // check bid amount
            if ($amount < $minimumBid) {
                abort(422, "Bid must be at least {$minimumBid}$.");
            }

            // create bid
            $bid = $auction->bids()->create([
                'user_id' => $user->id,
                'amount' => $amount
            ]);

            // update highest bid
            $auction->updateHighestBid($amount);

            return $bid;
        });
    }
}
