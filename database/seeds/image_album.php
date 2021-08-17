<?php

use Illuminate\Database\Seeder;
use App\ImageAlbum;
class image_album extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $ob=new ImageAlbum;
        $ob->id_type_details="1";
        $ob->name="https://media.bongda.com.vn/files/anh.vu/2018/07/05/watermarked-3b_2-2343.jpg";
        $ob->save();
        $ob=new ImageAlbum;
        $ob->id_type_details="1";
        $ob->name="https://vcdn-thethao.vnecdn.net/2021/05/16/ronaldo-jpeg-1621133979-4477-1621133999.jpg";
        $ob->save();
        $ob=new ImageAlbum;
        $ob->id_type_details="1";
        $ob->name="https://vcdn-thethao.vnecdn.net/2021/05/15/Ronaldo-2-3051-1621042304.jpg";
        $ob->save();
        $ob=new ImageAlbum;
        $ob->id_type_details="2";
        $ob->name="https://cdn-img.thethao247.vn/origin_414x0/storage/files/cuongnm/2021/05/04/chuyen-nhuong-bong-da-5-5-ronaldo-ra-di-gia-0-dong-messi-ky-2-nam-42073.jpg";
        $ob->save();
        $ob=new ImageAlbum;
        $ob->id_type_details="2";
        $ob->name="https://vnn-imgs-f.vgcloud.vn/2021/05/16/12/man-city-messi-2.jpg";
        $ob->save();
        $ob=new ImageAlbum;
        $ob->id_type_details="2";
        $ob->name="https://img.nhandan.com.vn/Files/Images/2020/08/26/bac-1598421336266.jpg";
        $ob->save();
        
    }
}
