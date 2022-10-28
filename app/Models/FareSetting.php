<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FareSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

	'id','from_km','upto_km','price_per_km','waiting_price_per_min','peak_hour','late_night','extra_on_base_price','convenience_fee','status','fare_plan_name','peak_night_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];
     public function peakNight()
    {
        return $this->hasMany('App\PeakAndNight','fare_setting_id');
    }
}
