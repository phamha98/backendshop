<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\product_type_details;
use Illuminate\Support\Facades\DB;
use App\Session_user;
use App\Product;
use App\product_type_main;
use App\wishlists;

use App\ImageAlbum;
class ProductsCustomer extends Controller
{
    //
    public function list_product(Request $request)
    {
        $products = product_type_details::all();//where("type", "open")->get();
        foreach ($products as  $product) {
            $img = $product->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $product->img = asset('storage/imagetypemain/' . $img);
            }
            $images = DB::table("image_albums")
                ->where('id_type_details', 'like', $product->id)
                ->select("id", "name")
                ->get();
            foreach ($images as  $image) {
                $img = $image->name;
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $image->name = asset('storage/imagetypemain/' . $img);
                }
            }
            $product->image_albums = $images;
        }
        return response()->json([
            'code' => 200,
            'data' => $products
        ], 200);
    }
    public function new_product(Request $request)
    {

        $newproducts = DB::table('product_type_details')
            ->where('new', 'like', "1")
            ->where("type", "open")
            ->get();
        foreach ($newproducts as  $newproduct) {
            $img = $newproduct->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $newproduct->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $newproducts
        ], 200);
    }
    public function sale_product(Request $request)
    {

        $saleproducts = DB::table('product_type_details')
            ->where("type", "open")
            ->where('sale', '<>', "0")->get();
        foreach ($saleproducts as  $saleproduct) {
            $img = $saleproduct->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $saleproduct->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $saleproducts
        ], 200);
    }
    public function show_product(Request $request)
    {   
        $wish=new wishlists();
        $count=wishlists::where("id_product_details",$request->id_type_details)
        ->count();
        $wish->count=$count;

        if($request->id_user!=="") {
            $find=wishlists::where("id_user",$request->id_user)
            ->where("id_product_details",$request->id_type_details)
         ->first();
         if($find)$wish->state=true;
         else $wish->state=false;
          
        }
        $product = DB::table("products")
            ->where('id_type_details', 'like', $request->id_type_details)
            ->select("id", "number", "size")
            ->get();
        $images = DB::table("image_albums")
            ->where('id_type_details', 'like', $request->id_type_details)
            ->select("id", "name")
            ->get();
        foreach ($images as  $image) {
            $img = $image->name;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $image->name = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'product' => $product,
            "img" => $images,
            "wish"=>$wish
        ], 200);
    }
    public function filterproduct(Request $request)
    {
        //return $table=DB::table("product_type_details")->get();

        $token = $request->header('token');
        $checkTokenIsValid = Session_user::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Token khong duoc gui qua header'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Token khong hop le'
            ], 401);
        } else {
            $filterproducts = DB::table('product_type_details')->where('gender', 'like', $request->filter)->get();
            return response()->json([
                'code' => 200,
                'data' => $filterproducts
            ], 200);
        }
    }
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

    public function search(Request $request)
    {

        // $products = DB::table('product_type_details')
        //     ->where("name", 'like', "%{$request->search}%")
        //     ->get();
        // foreach ($products as $product) {
        //     $img = $product->img;
        //     if (!filter_var($img, FILTER_VALIDATE_URL)) {
        //         $product->img = asset('storage/imagetypemain/' . $img);
        //     }
        // }
        //if ($request->ajax()) {

        $products = DB::table('product_type_details')->where("type", "open")
            ->where("name", 'like', "%{$request->search}%")
            ->get();
        foreach ($products as $product) {
            $img = $product->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $product->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $products
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
        return response()->json([
            'code' => 200,
            'data' =>  $products_details
        ], 200);
    }
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
    public function  setup_permission(Request $request)
    {

        DB::beginTransaction();
        try {
            $users = $request->input('users');
            for ($i = 0; $i < count($users); $i++) {
                $string = "users." . (string)$i;
                $row = DB::table("users")->insert([
                    "id" => $request->input($string . ".id"),
                    "name" => $request->input($string . ".name"),
                    "email" => $request->input($string . ".email"),
                    "password" => bcrypt($request->input($string . ".password")),

                ]);
            }

            $roles = $request->input('roles');
            for ($i = 0; $i < count($roles); $i++) {
                $string = "roles." . (string)$i;
                $row = DB::table("roles")->insert([
                    "id" => $request->input($string . ".id"),
                    "name" => $request->input($string . ".name"),
                    "display_name" => $request->input($string . ".display_name")
                ]);
            }

            $permissions = $request->input('permissions');
            for ($i = 0; $i < count($permissions); $i++) {
                $string = "permissions." . (string)$i;
                $row = DB::table("permissions")->insert([
                    "id" => $request->input($string . ".id"),
                    "name" => $request->input($string . ".name"),
                    "display_name" => $request->input($string . ".display_name")
                ]);
            }

            $role_permission = $request->input('role_permission');
            for ($i = 0; $i < count($role_permission); $i++) {
                $string = "role_permission." . (string)$i;
                $row = DB::table("role_permission")->insert([
                    "id_role" => $request->input($string . ".id_role"),
                    "id_permission" => $request->input($string . ".id_permission"),
                ]);
            }

            $role_user = $request->input('role_user');
            for ($i = 0; $i < count($role_user); $i++) {
                $string = "role_user." . (string)$i;
                $row = DB::table("role_user")->insert([
                    "id_user" => $request->input($string . ".id_user"),
                    "id_role" => $request->input($string . ".id_role"),
                ]);
            }
            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => "SUCESS",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "ERROR",
            ], 401);
        }
    }
    public function setup_products(Request $request)
    {
        DB::beginTransaction();
        try {
            $productmains = $request->input('productmains');
            for ($i = 0; $i < count($productmains); $i++) {
                $string = "productmains." . (string)$i;
                $row = DB::table("product_type_mains")->insert([
                    "id" => $request->input($string . ".id"),
                    "name" => $request->input($string . ".name"),
                    "img" => $request->input($string . ".img"),
                ]);
            }
            //up product details
            $productdetails = $request->input('productdetails');
            //dd($productdetails);
            for ($i = 0; $i < count($productdetails); $i++) {
                $table = new product_type_details;
                $string = "productdetails." . (string)$i;;
                
                $table->id_type_main = $request->input($string . ".id_type_main");
                $table->name = $request->input($string . ".name");
                $table->details = $request->input($string . ".details");
                $table->price = $request->input($string . ".price");
                $table->sale = $request->input($string . ".sale");
                $table->new = $request->input($string . ".new");
                $table->img = $request->input($string . ".img");
                $table->gender = $request->input($string . ".gender");
                $table->type = $request->input($string . ".type");
                $table->save();


                $data_image = $request->input($string . ".image_albums");
                //dd(count($data_image));
                for ($j = 0; $j < count($data_image); $j++) {
                    $image_albums = new ImageAlbum;
                    $stringj = ".image_albums." . (string)$j;;

                    $image_albums->id_type_details = $table->id;
                    $image_albums->name = $request->input($string . $stringj . ".name"); //productdetails.0.image_album.0.name
                    $image_albums->save();
                }
                $data_product = $request->input($string . ".product");
                for ($j = 0; $j < count($data_product); $j++) {
                    $product = new Product();
                    $stringj = ".product." . (string)$j;;

                    $product->id_type_details = $table->id;
                    $product->size = $request->input($string . $stringj . ".size"); //productdetails.0.image_album.0.name
                    $product->number = $request->input($string . $stringj . ".number");
                    $product->save();
                }
            }
            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => "SUCESS",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 401,
                'message' => "ERROR".$e,
            ], 401);
        }
    }
}
 