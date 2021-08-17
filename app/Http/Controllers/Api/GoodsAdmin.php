<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bills;
use App\Bill_details;
use App\Bill_state;
use App\Session_user;
use App\Product;
use App\User;
use App\product_type_details;
use App\product_type_main;
use Illuminate\Support\Facades\DB;

use App\ImageAlbum;
use Illuminate\Support\Facades\Storage;

require("MediaFunction.php");
class GoodsAdmin extends Controller
{

    //list_type_goods
    public function list_type_goods(Request $request)
    {
        $tables = product_type_main::all();
        foreach ($tables as $table) {
            $img = $table->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $table->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' =>  $tables
        ], 200);
    }
    //insert_type_main
    public function insert_type_main(Request $request)
    {


        $insert = new product_type_main();
        $insert->name = $request->input('name');

        if ($request->input('post')) {
            $base64 = $request->input("base64");
            $MediaFunction = new MediaFunction();
            $insert->img = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
        } else {
            $url = $request->input("url");
            if (strpos($url, asset('')) === 0) {
                return response()->json([
                    'code' => 401,
                    'message' => "Đường dẫn Sai",
                ], 401);
            }
            if (@file_get_contents($url, false, NULL, 0, 1)) {
                $MediaFunction = new MediaFunction();
                $insert->img = $MediaFunction->checkUrlImage($url);
            } else return response()->json([
                'code' => 401,
                'message' => "Đường dẫn không tồn tại",
            ], 401);
        }
        //________
        if ($insert->save()) {
            return response()->json([
                'code' => 200,
                'message' => "Thành công"
            ], 200);
        }
        return response()->json([
            'code' => 401,
            'message' => "Thất bại",
        ], 401);
    }
    //update_type_main
    public function update_type_main(Request $request)
    {

        if ($request->input('post')) {
            $base64 = $request->input("base64");
            $MediaFunction = new MediaFunction();
            $img = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
        } else {
            $url = $request->input("url");
            if (strpos($url, asset('')) === 0) {

                $update = DB::table("product_type_mains")
                    ->where('id', $request->input("id"))
                    ->update([
                        "name" => $request->input("name"),
                    ]);
                // $update = product_type_main::find($request->input("id"));
                // $update->name = $request->input("name");
                // $update->save();
                return response()->json([
                    'code' => 200,
                    'message' => "SUCESS1",
                    "url" => $url,
                    "update" => $update,
                    "id" => $request->input("id"),
                    "name" => $request->name
                ], 200);
            }
            if (@file_get_contents($url, false, NULL, 0, 1)) {
                $url = $request->input("url");
                $MediaFunction = new MediaFunction();
                $img = $MediaFunction->checkUrlImage($url);
            } else return response()->json([
                'code' => 401,
                'message' => "Đường dẫn không tồn tại",
            ], 401);
        }

        $update = DB::table("product_type_mains")
            ->where('id', $request->input("id"))
            ->update([
                "name" => $request->input("name"),
                "img" => $img
            ]);

        return response()->json([
            'code' => 200,
            'message' => "SUCESS"
        ], 200);
    }
    //all for product type detail
    public function main_goods_details(Request $request)
    {

        $products_details = product_type_details::where("id_type_main", $request->id_type_main)
            ->select("id", "name", "id_type_main", "price", "sale", "new", "img", "gender", "type")
            ->get();
        foreach ($products_details as $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        $array_product_details = array();
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
            array_push($array_product_details, $products_detail);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $array_product_details
        ], 200);
    }
    //
    public function goods_details(Request $request)
    {

        $product_details = product_type_details::find($request->id_type_details);
        $img = $product_details->img;
        if (!filter_var($img, FILTER_VALIDATE_URL)) {
            $product_details->img = asset('storage/imagetypemain/' . $img);
        }
        $product_details->products = Product::where("id_type_details", $request->id_type_details)
            ->select("id", "number", "size")
            ->get();

        $images = ImageAlbum::where('id_type_details', $request->id_type_details)
            ->select("id", "name")
            ->get();
        foreach ($images as  $image) {
            $img = $image->name;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $image->name = asset('storage/imagetypemain/' . $img);
            }
        }
        $product_details->array_img = $images;
        return response()->json([
            'code' => 200,
            'data' =>  $product_details
        ], 200);
    }
    public function list_goods(Request $request)
    {
        $product_type_details = DB::table("product_type_details")
            ->select("id", "id_type_main", "name", "price", "sale", "new", "img", "gender", "type")
            ->get();
        foreach ($product_type_details as  $product_type_detail) {
            $img = $product_type_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $product_type_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        $array_product_details = array();
        foreach ($product_type_details as $product_type_detail) {
            $array_size = Product::where("id_type_details", $product_type_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $product_type_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $product_type_detail->product_type_main = product_type_main::where("id", $product_type_detail->id_type_main)
                ->select("name")->first();
            $product_type_detail->total_size = $total_size;
            $product_type_detail->array_size  = $array_size;
            array_push($array_product_details, $product_type_detail);
        }
        //ren so luong san pham cho moi size
        return response()->json([
            'code' => 200,
            'data' =>  $array_product_details
        ], 200);
    }
    // public function search_goods_name
    public function search_goods_name(Request $request)
    {

        $products_details = product_type_details::where("name", 'like', "%{$request->search_name}%")
            ->get();
        foreach ($products_details as  $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        //___________________________________________
        $array_product_details = array();
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
            array_push($array_product_details, $products_detail);
        }
        //___________________________________________
        return response()->json([
            'code' => 200,
            'data' =>  $array_product_details
        ], 200);
    }

    // public function sort_goods_new
    public function sort_goods_new(Request $request)
    {

        $products_details = DB::table("product_type_details")->where("new", '=', $request->new)
            ->get();
        foreach ($products_details as  $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        //___________________________________________
        $array_product_details = array();
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
            array_push($array_product_details, $products_detail);
        }
        //___________________________________________
        return response()->json([
            'code' => 200,
            'data' => $products_details
        ], 200);
    }
    // public function search_goods_sale
    public function sort_goods_sale(Request $request)
    {

        $products_details = DB::table("product_type_details")->where("sale", $request->type, $request->sale)
            ->get();
        foreach ($products_details as  $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        //___________________________________________
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
        }
        //___________________________________________

        //ren so luong san pham cho moi size
        return response()->json([
            'code' => 200,
            'data' =>  $products_details
        ], 200);
    }
    //public function sort_goods_gender
    public function sort_goods_gender(Request $request)
    {
        if ($request->gender === "tat") {
            $products_details = DB::table("product_type_details")
                ->get();
            foreach ($products_details as  $products_detail) {
                $img = $products_detail->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $products_detail->img = asset('storage/imagetypemain/' . $img);
                }
            }
        } else {
            $products_details = DB::table("product_type_details")->where("gender", "=", $request->gender)
                ->get();
            foreach ($products_details as  $products_detail) {
                $img = $products_detail->img;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $products_detail->img = asset('storage/imagetypemain/' . $img);
                }
            }
            //___________________________________________
            foreach ($products_details as $products_detail) {
                $array_size = Product::where("id_type_details", $products_detail->id)
                    ->select("size")
                    ->get();
                $total_size = Product::where("id_type_details", $products_detail->id)
                    ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                    ->groupBy('products.id_type_details')
                    ->first();
                $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                    ->select("name")->first();
                $products_detail->total_size = $total_size;
                $products_detail->array_size = $array_size;
            }
        }

        //ren so luong san pham cho moi size
        return response()->json([
            'code' => 200,
            'data' =>  $products_details
        ], 200);
    }
    // public function sort_goods_totalnumber
    public function sort_goods_totalnumber(Request $request)
    {

        $products_details = DB::table('product_type_details')
            ->join('products', 'products.id_type_details', '=', 'product_type_details.id')
            ->select(
                "product_type_details.id_type_main",
                "product_type_details.name",
                "product_type_details.details",
                'products.id_type_details',
                "product_type_details.gender",
                "product_type_details.price",
                "product_type_details.sale",
                "product_type_details.new",
                "product_type_details.img",

                DB::raw('sum(number) as total_number')
            )
            ->groupBy(
                'products.id_type_details',
                "product_type_details.id_type_main",
                "product_type_details.name",
                "product_type_details.details",
                "product_type_details.gender",
                "product_type_details.price",
                "product_type_details.sale",
                "product_type_details.new",
                "product_type_details.img"
            )
            ->orderBy('total_number', $request->type)
            ->get();;
        foreach ($products_details as  $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        //___________________________________________
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id_type_details)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id_type_details)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
        }
        //ren so luong san pham cho moi size
        return response()->json([
            'code' => 200,
            'data' =>  $products_details
        ], 200);
    }
    // public function sort_goods_price
    public function sort_goods_price(Request $request)
    {

        $products_details = DB::table("product_type_details")
            ->select(
                "id",
                "id_type_main",
                "name",
                "details",
                "gender",
                "price",
                "sale",
                "new",
                "img",
                DB::raw('(price-sale*price*0.01) as money')
            )
            ->orderBy('money', 'desc')
            ->get();
        foreach ($products_details as  $products_detail) {
            $img = $products_detail->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $products_detail->img = asset('storage/imagetypemain/' . $img);
            }
        }
        //___________________________________________
        foreach ($products_details as $products_detail) {
            $array_size = Product::where("id_type_details", $products_detail->id)
                ->select("size")
                ->get();
            $total_size = Product::where("id_type_details", $products_detail->id)
                ->select('products.id_type_details', DB::raw('sum(number) as total_number'))
                ->groupBy('products.id_type_details')
                ->first();
            $products_detail->product_type_main = product_type_main::where("id", $products_detail->id_type_main)
                ->select("name")->first();
            $products_detail->total_size = $total_size;
            $products_detail->array_size = $array_size;
        }

        //ren so luong san pham cho moi size
        return response()->json([
            'code' => 200,
            'data' =>  $products_details
        ], 200);
    }
    //END SEARCH
    public function update_goods(Request $request)
    {

        DB::beginTransaction();
        try {
            $url = $request->input("img");
            if (strpos($url, asset('')) === 0) {
                $image = basename($url);
            } else {
                $base64 = $request->input("img");
                $MediaFunction = new MediaFunction();
                $image = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
            }

            $product_detail = product_type_details::where("id", $request->id_type_details)
                ->update(
                    [
                        'id_type_main' => $request->id_type_main,
                        'name' => $request->name,
                        'details' => $request->details,
                        'price' => $request->price,
                        'sale' => $request->sale,
                        'new' => $request->new,
                        'img' => $image,
                        'gender' => $request->gender,
                        'type' => "open"

                    ]
                );
            //insert imgs album
            // $where_image_albums = ImageAlbum::where("id_type_details", $request->id_type_details)->get();

            DB::table('image_albums')->where('id_type_details', 'like', $request->id_type_details)->delete();
            $image_albums = $request->input('image_albums');
            for ($i = 0; $i < count($image_albums); $i++) {
                $string = "image_albums." . (string)$i;
                $url =  $request->input($string . ".name");
                if (strpos($url, asset('')) === 0) {
                    $nameImage = basename($url);
                } else {
                    $base64 = $request->input($string . ".base64");
                    $MediaFunction = new MediaFunction();
                    $nameImage = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
                }
                DB::table('image_albums')->insert(
                    [
                        'id_type_details' => $request->id_type_details,
                        'name' => $nameImage
                    ]
                );
            }


            $where_products = Product::where("id_type_details", $request->id_type_details)->get();

            for ($i = 0; $i < count($where_products); $i++) {
                $string = "products." . (string)$i;
                Product::where("id", $where_products[$i]->id)
                    ->update(
                        [
                            'size' => $request->input($string . ".size"),
                            'number' => $request->input($string . ".number"),
                        ]
                    );
            }

            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => "success"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "khong chen dc",
            ], 401);
        }
    }

    public function insert_goods(Request $request)
    {

        DB::beginTransaction();
        try {
            $base64 = $request->input("img");
            $MediaFunction = new MediaFunction();
            $img1 = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
            $product_detail = product_type_details::create(
                [
                    'id_type_main' => $request->id_type_main,
                    'name' => $request->name,
                    'details' => $request->details,
                    'price' => $request->price,
                    'sale' => $request->sale,
                    'new' => $request->new,
                    'img' => $img1,
                    'gender' => $request->gender,
                    'type' => "open"

                ]
            );
            $image_albums = $request->input('image_albums');
            for ($i = 0; $i < count($image_albums); $i++) {
                $string = "image_albums." . (string)$i;

                $base64 = $request->input($string . ".base64");
                $MediaFunction = new MediaFunction();
                $img2 = $MediaFunction->saveImgBase64($base64, 'imagetypemain');

                DB::table('image_albums')->insert(
                    [
                        'id_type_details' => $product_detail->id,
                        'name' => $img2
                    ]
                );
            }
            $products = $request->input('products');
            for ($i = 0; $i < count($products); $i++) {
                $string = "products." . (string)$i;
                DB::table('products')->insert(
                    [
                        'id_type_details' => $product_detail->id,
                        'size' => $request->input($string . ".size"),
                        'number' => $request->input($string . ".number"),
                    ]
                );
            }


            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => $product_detail
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "khong chen dc",
            ], 401);
        }
    }


    public function delete_type_main(Request $request)
    {

        DB::beginTransaction();
        try {

            DB::commit();
            return response()->json([
                'code' => 200,
                'insert' => ""
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "ERROR",
            ], 401);
        }
    }
    public function delete_product(Request $request)
    {

        DB::beginTransaction();
        try {
            $id_product_detail = $request->input("id");
            $table = DB::table("product_type_details")
                ->where("id", $id_product_detail)
                ->update([
                    "type" => "close"
                ]);
            DB::commit();
            return response()->json([
                'code' => 200,
                'table' => $table
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "ERROR",
            ], 401);
        }
    }
    
}