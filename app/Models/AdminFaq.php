<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminFaq extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question',
        'answer'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeFaqList($query)
    {
        return $query->select('admin_faqs.*');
    }
}

?>
