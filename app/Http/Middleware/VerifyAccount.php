<?php

namespace App\Http\Middleware;

use Closure;
use App\Session_user;

class VerifyAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->token))
            $token = $request->header("token");
        else $token = $request->token;
        $checkTokenIsValid = Session_user::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Token không được gửi qua header'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Token không hợp lệ.'
            ], 401);
        }
        return $next($request);
    }
}
