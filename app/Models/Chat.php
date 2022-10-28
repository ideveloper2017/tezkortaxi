<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['request_id', 'user_id', 'provider_id', 'message', 'type', 'delivered'];
}
