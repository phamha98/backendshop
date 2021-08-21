<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill_details extends Model
{
    //
    protected $table="bill_details"; 
    public function product (){
        return $this->belongsTo("App\Product","id_product","id");
    }
    public function bill(){
        return $this->belongsTo("App\Bills","id_bill","id");
    }
}
