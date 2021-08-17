<?php

use Illuminate\Database\Seeder;
use App\product_type_main;

class product_type_main1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        
            $ob=new product_type_main;       
            $ob->name="Áo";
            $ob->img="https://www.shophaiyen.com/wp-content/uploads/2018/09/M%E1%BA%B7t-sau-%C3%81o-thun-3D-Th%C3%A1i-Lan-1.jpg";
            $ob->save();
            $ob=new product_type_main;       
            $ob->name="Đầm";
            $ob->img="https://sagasilk.com/wp-content/uploads/dam-to-tam-cao-cap.jpg";
            $ob->save();
            $ob=new product_type_main;          
            $ob->name="Váy";
            $ob->img="https://media3.scdn.vn/img3/2019/4_5/WZVc0y_simg_de2fe0_500x500_maxb.jpg";
            $ob->save();
            $ob=new product_type_main;          
            $ob->name="Quần";
            $ob->img="https://agiare.vn/wp-content/uploads/2018/12/quan-ong-rong-dep.jpg";
            $ob->save();
            $ob=new product_type_main;          
            $ob->name="Giầy";
            $ob->img="https://vn-test-11.slatic.net/p/e77f141f54d2eeba3b569cdd352ef885.png_720x720q80.jpg_.webp";
            $ob->save();
            $ob=new product_type_main;          
            $ob->name="Dép";
            $ob->img="https://media3.scdn.vn/img4/2020/10_30/KU2PY2GXNUKSI9EgYsVO_simg_de2fe0_500x500_maxb.jpg";
            $ob->save();
        
    }
}
