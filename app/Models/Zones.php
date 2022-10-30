<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  Zones extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'zones';
    protected $fillable = [
        'name',
		'coordinate',
		'background',
		'draw_lines',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function scopeOrigin($query, $array)
    {
        // return $query;
        return $query
        ->whereIn('city', $array)
        ->whereIn('country', $array)
        ->where('status', 'active');
    }
    public function scopeDestination($query, $array)
    {
        return $query->whereIn('city', $array)
        ->whereIn('country', $array)
        ->where('status', '!=' ,'blocked');
    }


}
