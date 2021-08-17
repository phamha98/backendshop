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
use Illuminate\Support\Facades\DB;

require("MediaFunction.php");

class AcountAdmin extends Controller
{

    //list_user
    public function list_user(Request $request)
    {

        $users = User::all();
        foreach ($users as $user) {
            $img = $user->img;
            if (!filter_var($img, FILTER_VALIDATE_URL)) {
                $user->img = asset('storage/imagetypemain/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' =>  $users
        ], 200);
    }
    //search_user_id
    public function search_user_id(Request $request)
    {

        $users = User::find($request->id_user);
        $img = $users->img;
        if (!filter_var($img, FILTER_VALIDATE_URL)) {
            $users->img = asset('storage/imagetypemain/' . $img);
        }
        return response()->json([
            'code' => 200,
            'data' =>  $users
        ], 200);
    }
    public function search_user_name(Request $request)
    {

        $users = DB::table('users')
            ->where('name', 'like', "%{$request->search_name}%") //$request->search_name)
            ->get();
        foreach ($users as $user) {
            $img = $user->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        return response()->json([
            'code' => 200,
            'data' =>  $users
        ], 200);
    }

    public function filter_user_birthday(Request $request)
    {

        $user_children = DB::table('users')
            ->select('name', 'birthday')
            ->selectRaw("TIMESTAMPDIFF(YEAR, DATE(birthday), current_date) AS age")
            //->selectRaw('current_date AS age')
            ->havingRaw('age < 15')
            ->get();;
        foreach ($user_children as $user_children1) {
            $img = $user_children1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user_children1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        $user_youth = DB::table('users')
            ->select('name', 'birthday')
            ->selectRaw("TIMESTAMPDIFF(YEAR, DATE(birthday), current_date) AS age")
            ->havingRaw('age > 15')
            ->havingRaw('age < 30')
            ->get();;

        foreach ($user_youth as $user_youth1) {
            $img = $user_youth1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user_youth1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        $middle_age = DB::table('users')
            ->select('name', 'birthday')
            ->selectRaw("TIMESTAMPDIFF(YEAR, DATE(birthday), current_date) AS age")
            ->havingRaw('age > 30')
            ->havingRaw('age < 46')
            ->get();;

        foreach ($middle_age as $middle_age1) {
            $img = $middle_age1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $middle_age1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        $user_old = DB::table('users')
            ->select('name', 'birthday')
            ->selectRaw("TIMESTAMPDIFF(YEAR, DATE(birthday), current_date) AS age")
            ->havingRaw('age > 45')
            ->get();;

        foreach ($user_old as $user_old1) {
            $img = $user_old1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $user_old1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        return response()->json([
            'code' => 200,
            'user_children' =>  $user_children,
            'user_youth' =>  $user_youth,
            'user_middle_age' => $middle_age,
            'user_old' => $user_old
        ], 200);
    }

    public function filter_user_gender(Request $request)
    {

        $users_man = DB::table('users')
            ->where('gender', 'like', "nu")
            ->get();
        foreach ($users_man as $users_man1) {
            $img = $users_man1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $users_man1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        $users_woman = DB::table('users')
            ->where('gender', 'like', "nam")
            ->get();
        foreach ($users_woman as $users_woman1) {
            $img = $users_woman1->img;
            if ($img !== null) {
                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $users_woman1->img = asset('storage/imagetypemain/' . $img);
                }
            }
        }
        return response()->json([
            'code' => 200,
            'users_man' => $users_man,
            'users_woman' =>  $users_woman
        ], 200);
    }
    public function delete_user_id(Request $request)
    {

        $user = DB::table('users')->where('id', $request->id_user)->delete();
        $user_search = DB::table('users')->where('id', $request->id_user)->first();

        if ($user_search == null) {
            return response()->json([
                'code' => 200,
                'msg' => 'success',
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'msg' => 'false',
            ], 200);
        }
    }

    public function update_acount(Request $request)
    {
        $table = User::find($request->id_user);
        $table->name = $request->name;
        $table->phone = $request->phone;
        $table->address = $request->address;
        $table->gender = $request->gender;
        $table->birthday = $request->birthday;
        $url = $request->img;
        $image = "";
        if ($url !== null) {
            if (strpos($url, asset('')) === 0) {
                $image = basename($url);
            } else {
                $base64 = $url;
                $MediaFunction = new MediaFunction();
                $image = $MediaFunction->saveImgBase64($base64, 'userimage');
            }
        } else  $image = null;
        $table->img = $image;
        $table->save();
        if ($table) {
            return response()->json([
                'code' => 200,
                'data' => $table,
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'msg' => "error",
            ], 401);
        }
    }
    public function update_acount_pass(Request $request)
    {

        $dataCheckLogin = [
            'email' => $request->email,
            'password' => $request->oldpassword,
        ];

        if (!auth()->attempt($dataCheckLogin))
            return response()->json([
                'code' => 401,
                'msg' => "Mật khẩu cũ của bạn không đúng!",
            ], 401);
        else {
            $news = User::find($request->id_user);
            $news->password = bcrypt($request->newpassword);
            $news->save();
            if ($news) {
                return response()->json([
                    'code' => 200,
                    'msg' => "Bạn đã thay đổi mật khẩu thành công",
                ], 200);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg' => "Thay đổi mật khẩu thất bại!",
                ], 401);
            }
        }
    }
    public function show_acount(Request $request)
    {

        $table = User::find($request->id_user);
        $img = $table->img;
        if ($table->img !== null) {
            if (!filter_var($table->img, FILTER_VALIDATE_URL)) {
                $table->img = asset('storage/userimage/' . $img);
            }
        }
        return response()->json([
            'code' => 200,
            'data' => $table
        ], 200);
        ///************ */

    }
    public function create_staff(Request $request)
    {
        try {
            DB::beginTransaction();
            $userCreate =  User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => md5($request->password),

            ]);
            $userCreate->roles()->attach($request->roles);
            // $roles=$request->roles;
            // foreach($roles as $roleId){
            //     DB::table('role_user')->insert([
            //         'id_user'=>$userCreate->id,
            //         'id_role'=>$roleId  //*default register =>roleId=3::user
            //     ]);
            // }

            DB::commit();
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => "error",
            ]);
        }
    }
}
