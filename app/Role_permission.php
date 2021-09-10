<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_permission extends Model
{
    //
   
    protected $table="role_permission";
    protected $fillable = [
        'id_permission', 'id_role'
    ];
    public function permission(){
        return $this->belongsTo("App\Permission","id","id_permision");
    }
    public function roles(){
        return $this->belongsTo("App\Role","id","id_role");
    }
}
