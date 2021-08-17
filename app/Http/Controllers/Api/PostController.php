<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\post_tags;
use Illuminate\Http\Request;

require "MediaFunction.php";
class PostController extends Controller
{

    public function load()
    {

        $tables = post_tags::where("status", "active")->get();
        foreach ($tables as $table) {
            if ($table->img !== null) {
                if (!filter_var($table->img, FILTER_VALIDATE_URL)) {
                    $table->img = asset('storage/postimg/' . $table->img);
                }
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $tables,
        ], 200);

    }

    public function insert(Request $request)
    {
        $table = new post_tags();
        $table->title = $request->title;
        $table->content = $request->content;
        $url = $request->url;
        if ($url !== null) {
            if (strpos($url, asset('')) === 0) {
                $image = basename($url);
            } else {
                $base64 = $url;
                $MediaFunction = new MediaFunction();
                $image = $MediaFunction->saveImgBase64($base64, 'postimg');
            }
        } else {
            $image = null;
        }

        $table->img = $image;
        $table->save();
        if ($table) {
            return response()->json([
                'code' => 200,
                'msg' => "Thành công",
            ], 200);
        }

    }
public function update(Request $request)
    {
        $table = post_tags::find($request->id);
        $table->title = $request->title;
        $table->content = $request->content;
        $url = $request->url;
        if ($url !== null) {
            if (strpos($url, asset('')) === 0) {
                $image = basename($url);
            } else {
                $base64 = $url;
                $MediaFunction = new MediaFunction();
                $image = $MediaFunction->saveImgBase64($base64, 'postimg');
            }
        } else {
            $image = null;
        }

        $table->img = $image;
        $table->save();
        if ($table!==null) {
            return response()->json([
                'code' => 200,
                'msg' => $table//"Thành công",
            ], 200);
        }

    }

    public function delete(Request $request)
    {
        $item = post_tags::find($request->id)->delete();
        if ($item) {
            return response()->json([
                'code' => 200,
                'msg' => "Thành công",
            ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'msg' => "Thất bại",
            ], 200);
        }

    }
}
