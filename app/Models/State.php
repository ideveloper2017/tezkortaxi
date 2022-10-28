<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class State extends Model
{
    use SoftDeletes;
	protected $table = "states";
	protected $dates = ['deleted_at'];
    public function country(){
    	return $this->belongsTo('App\Models\Country','country_id');
    }
    public function city(){
    	return $this->hasMany('App\Models\City','state_id');
    }
}
