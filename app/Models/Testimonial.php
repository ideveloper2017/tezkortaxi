<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class Testimonial extends Model

{

    use SoftDeletes;



     /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'en_name','ar_name','fr_name','ru_name','sp_name', 'name1', 'en_type','ar_type','fr_type','ru_type','sp_type', 'type1' ,'en_description','ar_description','fr_description','ru_description','sp_description', 'description1'  ,'image'

    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'created_at', 'updated_at'

    ];



    /**

     * The attributes that should be mutated to dates.

     *

     * @var array

     */

    protected $dates = ['deleted_at'];

}

