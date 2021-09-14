<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\wishlists;
use App\product_type_details;
class Wish extends Controller
{
    public function insert_wish(Request $request){
        $find=wishlists::where("id_user",$request->id_user)
        ->where("id_product_details",$request->id_product_details)
        ->first();
        if($find){
            $find->delete();
            return response()->json([
                'code' => 200,
                "type"=>"delete",
                'msg' =>  "Đã xóa khỏi danh sách yêu thích",
            ], 200);
        }

        $table=new wishlists();
        $table->id_user=$request->id_user;
        $table->id_product_details=$request->id_product_details;
        $table->save();

          return response()->json([
            'code' => 200,
            "type"=>"add",
            'msg' =>  "Đã thêm vào danh sách yêu thích",
        ], 200);
    }
    ///**delete */
    public function load_user_wish(Request $request){
        $tables=wishlists::where("id_user",$request->id_user)
        ->select('id',"id_product_details","id_user")
        ->get();
        foreach($tables as $table){
         $product_details=DB::table("product_type_details")->where("id", $table->id_product_details)
         ->first();
        
            $table->product_details= $product_details;
        }
        return response()->json([
                'code' => 200,
                'data' =>   $tables
            ], 200);
    }
    public function load_number_product_wish(Request $request){
        $wish=new wishlists();
        $count=wishlists::where("id_product_details",$request->id_product_details)
        ->count();
        $wish->count=$count;

        if($request->id_user) {
            $find=wishlists::where("id_user",$request->id_user)
            ->where("id_product_details",$request->id_product_details)
         ->first();
         if($find)$wish->state=true;
         else $wish->state=false;
          
        }
          return response()->json([
                'code' => 200,
                'data' =>    $wish
            ], 200);
    }
    ///**delete */

    public function get_wish_user(Request $request){
        $tables=wishlists::where("id_user",$request->id_user)
        ->select('id',"id_product_details","id_user")
        ->get();
         return response()->json([
                'code' => 200,
                'data' =>   $tables
            ], 200);
    }
    public function get_wish_product(Request $request){
        $tables=wishlists::where("id_product_details",$request->id_product_details)
        ->select('id',"id_product_details","id_user")
        ->get();
         return response()->json([
                'code' => 200,
                'data' =>   $tables
            ], 200);
    }
    public function change_wish_user(Request $request){
        if($request->type=="0"){
            $find=wishlists::where("id_user",$request->id_user)
            ->where("id_product_details",$request->id_product_details)
            ->first();
            if($find){
                $find->delete();
                return response()->json([
                    'code' => 200,
                    "type"=>"delete",
                    'msg' =>  "Đã xóa khỏi danh sách yêu thích",
                ], 200);
            }
        }
        if($request->type=="1"){
            $find=wishlists::where("id_user",$request->id_user)
            ->where("id_product_details",$request->id_product_details)
            ->first();

            $table=new wishlists();
            $table->id_user=$request->id_user;
            $table->id_product_details=$request->id_product_details;
            if(!$find) $table->save();
             if(!$find) return response()->json([
                'code' => 200,
                'msg' => "success"
            ], 200);
        }
          return response()->json([
            'code' => 401,
            'msg' =>  "error",
        ], 401);
    }

    //  public function load_user_product_wish(Request $request){
    //     $table=wishlists::where("id_product_details",$request->id_product_details)
    //     ->get();
    //         if($table) return response()->json([
    //                 'code' => 200,
    //                 'data' =>   $table
    //             ], 200);
    // }
    //  public function list_post(Request $request){
    //     $posts=DB::('post_tags')->get();
    //     return response()->json([
    //         'code'=>200,
    //         'data'=>$post
    //     ])
    // }
}
