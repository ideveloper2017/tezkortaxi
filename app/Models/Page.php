<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Page extends Model

{

     protected $fillable = [

        'id',

        'en_title','ar_title','fr_title','ru_title','sp_title',

        'slug',

        'en_meta_keys','ar_meta_keys','fr_meta_keys','ru_meta_keys','sp_meta_keys',

        'en_meta_description','ar_meta_description','fr_meta_description','ru_meta_description','sp_meta_description',

        'en_description','ar_description','fr_description','ru_description','sp_description',

        'image',
        'en_question','ar_question','fr_question','ru_question','sp_question',


    ];

}

