<?php

use Illuminate\Database\Seeder;
use App\product_type_details;
class product_type_details1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
            $ob=new product_type_details;
            $ob->id_type_main="1";//ao    
            $ob->name="Ronado";
            $ob->details="Ronado";
            $ob->price=129000;
            $ob->sale=120000;
            $ob->new=1;
            $ob->gender="Nam";
            $ob->img="https://media.bongda.com.vn/files/anh.vu/2018/07/05/watermarked-3b_2-2343.jpg";
            $ob->save();
            $ob=new product_type_details;
            $ob->id_type_main="1";//ao
            $ob->name="Messi";
            $ob->details="Ronado";
            $ob->price=129000;
            $ob->sale=120000;
            $ob->new=1;
            $ob->gender="Nam";
            $ob->img="https://aobongda24h.com/pic/product/1840_ao_messi_argentina_201_HasThumb.jpg";
            $ob->save();
            $ob=new product_type_details;
            $ob->id_type_main="2";//ao
            $ob->name="Thuy kieu";
            $ob->details="Ronado";
            $ob->price=129000;
            $ob->sale=120000;
            $ob->new=1;
            $ob->gender="Nu";
            $ob->img="https://media-cdn.laodong.vn/Storage/NewsPortal/2020/10/23/847873/Phim-KIEU_Poster-Nha-01.jpg";
            $ob->save();
            $ob=new product_type_details;
            $ob->id_type_main="4";//ao
            $ob->name="Thuy van";
            $ob->details="Ronado";
            $ob->price=129000;
            $ob->sale=120000;
            $ob->new=1;
            $ob->gender="Nu";
            $ob->img="https://ss-images.saostar.vn/2019/11/21/6484606/page.jpg";
            $ob->save();
    }
}
