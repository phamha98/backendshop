<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $guarded=[];
    protected $table="products";
    public function product_type_details(){
        return $this->belongsTo("App\product_type_details","id_product_details","id");
    }
    public function product_type_main(){
        return $this->belongsTo("App\product_type_main","id_product_main","id");
    }
    public function bill_details(){
        return $this->hasMany("App\Bill_details","id_product","id");
    }
}
