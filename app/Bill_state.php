<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill_state extends Model
{
    //
    protected $table="bill_states";
    protected $fillable = [
        'state', 'id_user_confirm', 'id_user_transport'
    ];

    public function user_order(){
        return $this->belongsTo("App\User","id_user_order","id");
    }
    public function user_confirm(){
        return $this->belongsTo("App\User","id_user_confirm","id");
    }
    public function user_transport(){
        return $this->belongsTo("App\User","id_user_transport","id");
    }

    public function bills(){
        return $this->hasMany("App\Bills","id_bill","id");
    }
}
