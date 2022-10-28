<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Blog extends Model

{

     protected $fillable = [

        'id',

        'en_title','ar_title','fr_title','ru_title','sp_title',

        'en_description','ar_description','fr_description','ru_description','sp_description',

        'image',



    ];

}

