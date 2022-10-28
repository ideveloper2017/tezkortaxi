<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHelps extends Model
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

    public function scopeHelpsList($query)
    {
        return $query->select('admin_helps.*');
    }
}

?>
