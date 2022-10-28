<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{

     protected $fillable = [
        'name',
        'email',
        'phone',
        'lost_item'
    ];


    protected $hidden = [
         'created_at', 'updated_at'
    ];

}
