<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wishlists extends Model
{
     protected $table="wishlists";
    public function user(){
        return $this->hasMany("App\Users","id_user","id");
    }
    public function product_type_details(){
        return $this->hasMany("App\product_type_details","id_product_details","id");
    }
}
