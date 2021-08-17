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
use Illuminate\Support\Str;

class AcountCustomer extends Controller
{
    //
    //acount_register
    public function register(Request $request)
    {
        $dataCheckRegister = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (User::where("email", $request->email)===1) return response()->json([
            'code' => 401,
            'message' => "Email đã được đăng ký, vui lòng đăng ký bằng email khác!"
        ], 401);
        if (auth()->attempt($dataCheckRegister)) {
            return response()->json([
                'code' => 401,
                'message' => "Tài khoản đã tồn tại"
            ], 401);
        } else {
            $userCreate =  User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            if ($userCreate)
                return response()->json([
                    'code' => 200,
                    'message' => "Bạn đã đăng ký thành công"
                ], 200);
            else return response()->json([
                'code' => 200,
                'message' => "Đăng ký thất bại"
            ], 200);
        }
    }
    //login
    public function login(Request $request)
    {
        $dataCheckLogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (auth()->attempt($dataCheckLogin)) {
            $checkTokenExit = Session_user::where('id_user', auth()->id())->first();
            if (empty($checkTokenExit)) {
                $userSession = Session_user::create([
                    'token' => Str::random(40),
                    'refresh_token' => Str::random(40),
                    'token_expried' => date('Y-m-d H:i:s', strtotime(" +30 day")), //thoi han
                    'refresh_token_expried' => date('Y-m-d H:i:s', strtotime(" +360 day")),
                    'id_user' => auth()->id()
                ]);
            } else {
                $userSession = $checkTokenExit;
            }
            return response()->json([
                'code' => 200,
                'msg' => "Đăng nhập thành công",
                'data' => $userSession
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'msg' => "Email hoặc mật khẩu không sai"
            ], 401);
        }
    }
    //refreshToken
    public function refresh_token(Request $request)
    {   //khi het han::
        // Gui lien tuc khi ..product-->check->out login
        $token = $request->header('token');

        $checkTokenIsValid = Session_user::where('token', $token)->first();
        // dd($checkTokenIsValid);
        if (!empty($checkTokenIsValid)) {
            if ($checkTokenIsValid->token_expried < date('Y-m-d H:i:s')) { //Het Han:
                $checkTokenIsValid->update([
                    'token' => Str::random(40),
                    'refresh_token' => Str::random(40),
                    'token_expried' => date('Y-m-d H:i:s', strtotime(" +30 day")),
                    'refresh_token_expried' => date('Y-m-d H:i:s', strtotime(" +360 day")),
                ]);
                $dataSession = Session_user::find($checkTokenIsValid->id);
                return response()->json([
                    'code' => 200,
                    'data' => $dataSession,
                    "time" => time(),
                    'message' => "Refresh token sucess"
                ], 200);
            } else {

                return response()->json([
                    'code' => 200,
                    'message' => "Token chua het han",
                    "expried" => $checkTokenIsValid->token_expried,
                    "date" => date('Y-m-d H:i:s')
                ], 200);
            }
        } else {

            return response()->json([
                'code' => 200,
                'message' => "Token sai",
            ], 200);
        }
    }
    //deleteToken
    public function delete_token(Request $request)
    {
        $token = $request->header('token');
        $checkTokenIsValid = Session_user::where('token', $token)->first();
        if (!empty($checkTokenIsValid)) {
            $checkTokenIsValid->delete();
        }

        return response()->json([
            'code' => 200,
            'message' => "Delete token sucess"
        ], 200);
    }
    //login
    public function login_permision(Request $request)
    {

        //return '{"token":"sasd"}';
        $dataCheckLogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        //dd($dataCheckLogin);
        //B1:Xac thuc user co tai khoan hay chua
        if (auth()->attempt($dataCheckLogin)) {
            $checkTokenExit = Session_user::where('id_user', auth()->id())->first();
            if (empty($checkTokenExit)) {
                $userSession = Session_user::create([
                    'token' => Str::random(40),
                    'refresh_token' => Str::random(40),
                    'token_expried' => date('Y-m-d H:i:s', strtotime(" +30 day")), //thoi han
                    'refresh_token_expried' => date('Y-m-d H:i:s', strtotime(" +360 day")),
                    'id_user' => auth()->id()
                ]);
            } else {
                $userSession = $checkTokenExit;
            }
            $permission = DB::table('role_user')
                ->where("id_user",   $userSession->id_user)
                ->join("roles", "roles.id", "=", "role_user.id_role")
                ->join("role_permission", "role_permission.id_role", "=", "roles.id")
                ->join("permissions", "permissions.id", "=", "role_permission.id_permission")
                ->select("permissions.id", "permissions.name")
                ->groupBy("permissions.id", "permissions.name")
                ->get();

            return response()->json([
                'code' => 200,
                'message' => "success",
                'data' => $userSession,
                "permission" => $permission
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'message' => "user name or password false store"
            ], 401);
        }
    }
}