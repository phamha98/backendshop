<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\product_type_main;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

require "MediaFunction.php";
class PermissionAdmin extends Controller
{
    //setup_permission

    public function list_staff(Request $request)
    {

        $users = User::all();
        $staffs = DB::table("users")
            ->join("role_user", "role_user.id_user", "=", "users.id")
            ->join("roles", "roles.id", "=", "role_user.id_role")
            ->where("roles.id", "=", "2")
            ->orWhere("roles.id", "=", "3")
            ->groupBy(
                "users.id",
                "users.email",
                "users.name",
                'users.phone',
                "users.gender",
                "users.birthday",
                "users.img",
                "users.address"
            )
            ->select(
                "users.id",
                "users.email",
                "users.name",
                'users.phone',
                "users.gender",
                "users.birthday",
                "users.img",
                "users.address"
            )
            ->get();

        foreach ($staffs as $staff) {
            $staff->role = DB::table("role_user")
                ->where("id_user", $staff->id)
                ->join("roles", "roles.id", "=", "role_user.id_role")
                ->select("roles.id", "roles.name", "roles.display_name")
                ->get();
        }
        return response()->json([
            'code' => 200,
            'data' => $staffs,
        ], 200);
    }
    public function listuser_group_role(Request $request)
    {

        $users = User::all();
        $staffs = DB::table("users")
            ->join("role_user", "role_user.id_user", "=", "users.id")
            ->join("roles", "roles.id", "=", "role_user.id_role")
            ->where("roles.id", "=", $request->id_role)
            ->select(
                "users.id",
                "users.email",
                "users.name",
                'users.phone',
                "users.gender",
                "users.birthday",
                "users.img",
                "users.address"
            )
            ->get();
        foreach ($staffs as $staff) {
            $img = $staff->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $staff->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $staffs,
        ], 200);
    }
    public function list_group_user(Request $request)
    {

        $table = DB::table('roles')->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function list_permission_role(Request $request)
    {

        $table = DB::table('role_permission')
            ->where("role_permission.id_role", $request->id_role)
            ->join("permissions", "permissions.id", "=", "role_permission.id_permission")
            ->select("permissions.id", "permissions.name", "permissions.display_name")
            ->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function test1(Request $request)
    {

        $table = DB::table('role_user')->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function test2(Request $request)
    {

        $table = DB::table('role_permission')->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function list_role(Request $request)
    {

        $table = DB::table('roles')->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function list_permission(Request $request)
    {

        $table = DB::table('permissions')
            ->get();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function insert_role_permission(Request $request)
    {

        $tables = DB::table('role_permission')
            ->where("id_role", $request->id_role)
            ->where("id_permission", $request->id_permission)
            ->get();
        if (count($tables) == 0) {
            $table = DB::table('role_permission')->insert(
                [
                    'id_role' => $request->id_role,
                    "id_permission" => $request->id_permission,
                ]
            );
        } else {
            $table = "Đã tồn tại quyền";
        }

        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function delete_role_permission(Request $request)
    {

        $table = DB::table('role_permission')
            ->where("id_role", $request->id_role)
            ->where("id_permission", $request->id_permission)
            ->delete();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function insert_role(Request $request)
    {

        $tables = DB::table('roles')
            ->where("name", $request->name)
            ->get();
        if (count($tables) == 0) {
            $table = DB::table('roles')->insert(
                [
                    'name' => $request->name,
                    "display_name" => $request->display_name,
                ]
            );
            $msg = true;
        } else {
            $msg = false;
        }

        return response()->json([
            'code' => 200,
            'msg' => $msg,
        ], 200);
    }
    public function insert_role_user(Request $request)
    {
        //error check account
        $tables = DB::table('role_user')
            ->where("id_role", $request->id_role)
            ->where("id_user", $request->id_user)
            ->get();
        if (count($tables) == 0) {
            $table = DB::table('role_user')->insert(
                [
                    'id_role' => $request->id_role,
                    "id_user" => $request->id_user,
                ]
            );
        } else {
            $table = "Đã tồn tại tài khoản";
        }

        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function delete_user_role(Request $request)
    {

        $table = DB::table('role_user')
            ->where("id_role", $request->id_role)
            ->where("id_user", $request->id_user)
            ->delete();
        return response()->json([
            'code' => 200,
            'data' => $table,
        ], 200);
    }
    public function test_img(Request $request)
    {
        //dd("dad");
        //move_uploaded_file($request->file, './image/' . "dasd.jpg");
        // src="anhcuatui/<?php echo $bv['anh']
        //    return response()->json([
        //             'code' => 200,
        //             'data' =>  $request
        //         ], 200);
        // dd($request->files);
        // if ($request->hasFile('file')) {

        //     $file = $request->file('file');
        //     if ($file->getClientOriginalExtension('file') == "jpg" || $file->getClientOriginalExtension('file') == "png") {
        //         $filename = $file->getClientOriginalName('file');
        //         $file->move('image', $filename);
        //         return response()->json([
        //             'code' => 200,
        //             'msg' => "ok",
        //             'data' => $file,
        //             "filename"=> $filename,
        //             "text" =>$request->name2
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             'code' => 400,
        //             'msg' => "no file jpg png",
        //             'data' => $file
        //         ], 400);
        //     }
        // } else {
        //     return response()->json([
        //         'code' => 400,
        //         'data' => "no find"
        //     ], 400);
        // }
        //
        // if($request->photo){
        //     $name="tenanh";
        //     \Image::make($request->photo)->save(public_path("image").$name);
        // }
        // return response()->json([
        //     'code' => 200,
        //     'data' => $request->name
        // ], 200);
        $file = "storage/imagetypemain/" . "img2021061115202229602700.png";
        //return asset($file);
        return response()->json([
            'code' => 200,
            'data' => asset($file),
        ], 200);
    }
    public function insert_img(Request $request)
    {
        $url = "1storage/imagetypemain/img2021061218562278663900.jpeg";
        $a = (string) asset('');
        //echo $a;
        // return  strpos($url,  $local);
        if (strpos($url, asset('')) === 0) {
            echo "ok";
            //dd(strpos($url, $a));
        }
    }
    public function load_img(Request $request)
    {

        // return response()->json([
        //     'code' => 200,
        //     'msg' => "local",
        //     'data' => $name
        // ], 200);
        $tables = DB::table('product_type_mains')->get();
        foreach ($tables as $table) {
            $img = $table->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                //kiểm tra xem phải url hay không
                $table->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return view("test")->with('list', $tables);
    }

    public function hello()
    {
        $getdata = DB::table('product_type_mains')->get();
        return view("test")->with('list', $getdata);
    }
    public function upload(Request $request)
    {

        $base64 = $request->input('base64');
        $product_type_main = new product_type_main();
        $product_type_main->name = $request->input('title');
        if ($request->post) {
            $MediaFunction = new MediaFunction();
            $product_type_main->img = $MediaFunction->saveImgBase64($base64, 'imagetypemain');
        } else {
            $url = $request->uriImage;
            if (@file_get_contents($url, false, null, 0, 1)) {
                $MediaFunction = new MediaFunction();
                $product_type_main->img = $MediaFunction->checkUrlImage($url);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg' => "Đường dẫn không tồn tại",
                ], 401);
            }

        }
        if ($product_type_main->save()) {
            return response()->json([
                'code' => 200,
                'msg' => "Thành công",
            ], 200);
        }
        return response()->json([
            'code' => 401,
            'msg' => "Thất bại",
        ], 401);
    }
    protected function saveImgBase64($param, $folder)
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
}
