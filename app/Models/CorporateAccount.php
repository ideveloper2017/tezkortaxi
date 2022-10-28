<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Notifications\CorporateResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CorporateAccount extends Authenticatable
{


   use Notifiable;

    protected $fillable = [
        'corporate_name',
        'name',
        'email',
        'password',
        'address',
        'phone',
        'account_number',
        'credit_card',
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


     /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CorporateResetPassword($token));
    }
}
