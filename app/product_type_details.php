<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_type_details extends Model
{
    //
    protected $guarded=[];//khong chua gi tuc laf tat ca co the insert trong create
    protected $table = "product_type_details";
    public function image_albums()
    {
        return $this->hasMany(
            'App\ImageAlbum', 'id_type', 'id');
    }
    public function productmain(){
        return $this->belongsTo('App\product_type_mains','id_type_details','id');
    }
}
