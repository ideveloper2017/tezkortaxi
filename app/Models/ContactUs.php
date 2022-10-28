<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
     protected $table = 'contact_us';
     protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'attachment'
    ];


    protected $hidden = [
         'created_at', 'updated_at'
    ];

}
