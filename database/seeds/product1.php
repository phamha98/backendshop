<?php

use Illuminate\Database\Seeder;
use App\Product;
class product1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ob=new Product;
           
            $ob->id_type_details="1";//ronado
            $ob->number=10;
            $ob->size="M";
            $ob->save();
            $ob=new Product;
            
            $ob->id_type_details="1";//ronado
            $ob->number=10;
            $ob->size="L";
            $ob->save();
            $ob=new Product;
           
            $ob->id_type_details="1";//ronado
            $ob->number=10;
            $ob->size="N";
            $ob->save();
            $ob=new Product;
           
            $ob->id_type_details="2";//ronado
            $ob->number=10;
            $ob->size="L";
            $ob->save();
    }
}
