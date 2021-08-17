<?php

namespace App\Http\Controllers\Api;

use App\product_type_details;
use App\ImageAlbum;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;
class MediaFunction extends Controller
{    
    public function saveImgBase64($param, $folder)
    {
        list($extension, $content) = explode(';', $param);
        $tmpExtension = explode('/', $extension);
        preg_match('/.([0-9]+) /', microtime(), $m);
        $fileName = sprintf('img%s%s.%s', date('YmdHis'), $m[1], $tmpExtension[1]);
        $content = explode(',', $content)[1];
        $storage = Storage::disk('public');

        $checkDirectory = $storage->exists($folder);

        if (!$checkDirectory) {
            $storage->makeDirectory($folder);
        }

        $storage->put($folder . '/' . $fileName, base64_decode($content), 'public');

        return $fileName;
    }
    public function checkUrlImage($url)
    {
        $imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
        $urlExt = pathinfo($url, PATHINFO_EXTENSION);
        if (in_array($urlExt, $imgExts)) {
            try {
                $file_name = "storage/imagetypemain/" . "img" . date('YmdHis') . basename($url);
                if (file_put_contents($file_name, file_get_contents($url))) {
                    $img = basename($url);
                    $warrning1 = "Ảnh đã lưu vào máy chủ";
                } else {
                    $img =  $url;
                    $warrning1 = "Ảnh không thể lưu vào máy chủ";
                }
            } catch (\Exception $e) {
                $img =  $url;
                $warrning1 = "Ảnh không thể lưu vào máy chủ";
            }
        } else {
            $img =  $url;
            $warrning = "Đường dẫn có thể không phải ảnh";
        }
        return $img;
    }
    
}
