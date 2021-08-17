<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageAlbum extends Model
{
    //
    protected $table = "image_albums";
    public function product_type_details()
    {
        return $this->belongsTo(
            'App\product_type_details', 'id_type', 'id');
    }
}
