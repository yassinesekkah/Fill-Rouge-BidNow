<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{   
    protected $fillable = [
        'user_id',
        'auction_id',
        'rating',
        'comment',
        'reviewed_user_id'
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
