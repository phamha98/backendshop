<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\ImageAlbum;
use App\Product;
use App\product_type_details;
use App\product_type_main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


require "MediaFunction.php";
class Libary extends Controller
{
    public function insert(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $name = $image->getClientOriginalName();
            $destinationPath = public_path('storage/imagetypemain/');
            $image->move($destinationPath, "img" . date('YmdHis') . $name);
            return response()->json([
                'code' => 200,
                'message' => "success",
                'image' => "img" . date('YmdHis'). $name
            ], 200);
        }
        return response()->json([
            'code' => 401,
            'message' => "error",
        ], 200);
    }
    public function insert_multiple(Request $request)
    {
        
    }
    public function delete(Request $request)
    {
        if ($request->image) {
            $name = $request->image;
            $r = File::delete('storage/libary/' . $name);

            if ($r) return response()->json([
                'code' => 200,
                'message' =>  'success',
            ], 200);
            else {
                return response()->json([
                    'code' => 401,
                    'message' =>  "error",
                ], 200);
            }
        }
        return response()->json([
            'code' => 401,
            'message' =>  'No find image !'
        ], 401);
    }

    public function delete_arr(Request $request)
    {
        if ($request->albums) {
            $images = $request->albums;
            foreach ($images as  $image) {
                # code...

                $r = File::delete('storage/libary/' . $image);
            }
        }
        //// code
        // $name = 'img20211017192259images.jpeg';
        // $r = File::delete('storage/libary/' . $name);
        // return response()->json([
        //     'code' => 200,
        //     'message' => 'hihihihi', //"error",
        //     'r' => $r
        // ], 200);
    }
    public function update(Request $request)
    {
    }
}
