<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    //
   
    protected $table="role_user";
    protected $fillable = [
        'id_user', 'id_role'
    ];
    public function roles(){
        return $this->belongsTo("App\Role","id","id_role");
    }
     public function user(){
        return $this->belongsTo("App\User","id","id_user");
    }
}
