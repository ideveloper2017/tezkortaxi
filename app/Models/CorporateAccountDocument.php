<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateAccountDocument extends Model
{

    protected $fillable = [
        'corporate_id',
        'document_id',
        'url',
    ];

     /**
     * The services that belong to the user.
     */
    public function corporate()
    {
        return $this->belongsTo('App\CorporateAccount');
    }

}
