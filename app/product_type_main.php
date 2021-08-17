<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_type_main extends Model
{
    //
    protected $table="product_type_mains";
    public function productdetails(){
        return $this->hasMany('App\product_type_details','id_type_main','id');
    }
}
