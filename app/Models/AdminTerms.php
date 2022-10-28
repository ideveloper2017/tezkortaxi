<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminTerms extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeTermsList($query)
    {
        return $query->select('admin_terms.*');
    }
}    

?>    