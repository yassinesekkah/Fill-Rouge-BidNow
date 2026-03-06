<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User:: class);
    }

    public function auction(): HasOne
    {
        return $this->hasOne(Auction::class);
    }
}
