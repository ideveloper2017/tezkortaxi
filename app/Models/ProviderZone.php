<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderZone extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id','zone_id'
    ];

    protected $table = "provider_zone";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
