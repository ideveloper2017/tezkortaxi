<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class City extends Model
{
    use SoftDeletes;
    protected $table = "city";
    protected $dates = ['deleted_at'];
    public function state(){
    	return $this->belongsTo('App\State','state_id');
    }
}
