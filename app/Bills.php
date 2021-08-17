<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    //
    protected $fillable = [
        'id_user', 'date', 'total_price'
    ];
    protected $table="bills";
    public function bill_details(){
        return $this->hasMany("App\Bill_details","id_bill","id");
    }
    public function user(){
        return $this->belongsTo("App\User","id_user","id");
    }
    public function bill_state(){
        return $this->belongsTo("App\Bill_state","id_bill","id");
    }
}
