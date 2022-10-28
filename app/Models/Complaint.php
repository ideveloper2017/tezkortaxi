<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{

     protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'attachment',
        'type'
    ];


    protected $hidden = [
         'created_at', 'updated_at'
    ];

}
