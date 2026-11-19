<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\relations\BelongsTo;

#[Fillable(['driver_id', 'pickup_location', 'drop_location', 'status'])]

class Ride extends Model
{
    //
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
