<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeakAndNight extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

	'fare_setting_id','day','start_time','end_time','peak_night_type','status','fare_in_percentage','peak_night_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];
      public function fare_setting()
    {
        return $this->belongsTo('App\FareSetting','fare_setting_id');
    }
}
