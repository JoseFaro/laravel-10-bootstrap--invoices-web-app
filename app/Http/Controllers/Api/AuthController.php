<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

// use App\Mail\PasswordReset;
// use App\Mail\PasswordUpdated;
// use App\Models\User;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function checkLoginStatus(){
        $res = ['success' => false];

        if(Auth::check()){
            $user = Auth::user();
            $user->token()->revoke();

            $res['token'] = $user->createToken('passportToken')->accessToken;
            $res['success'] = true;
        }

        return $res;
    }

    public function login(Request $request)
    {
        $res = ['success' => false];

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $res['success'] = true;
            $res['token'] = $user->createToken('passportToken')->accessToken;
            $res['user'] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ];
        }

        return $res;
    }

    public function logout(){
        if(Auth::check()){
            Auth::user()->token()->revoke();
        }

        return ['success' => true];
    }

    /*
    public function requestRecovery(Request $request)
    {
        $res = ['success' => false];
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $res['success'] = true;
            $user->recovery_key = str_random(60);
            $user->save();

            Mail::to($user->email)->send(new PasswordReset($user));
        }

        return $res;
    }

    public function verifyRecoveryKey($recovery_key){
        $res = ['success' => false];

        $user = User::where('recovery_key', $recovery_key)->first();
        if($user){
            $res['success'] = true;
            $res['user'] = $user;
        }

        return $res;
    }

    public function resetPassword(Request $request)
    {
        $rules['password'] = 'required|min:6|max:30';
        $validator = Validator::make( $request->all(), $rules );
        if($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $res = ['success' => false];
        $user = User::where('id', $request->id)->where('recovery_key', $request->recovery_key)->first();
        if ($user) {
            $res['success'] = true;
            $user->password = bcrypt($request->password);
            $user->recovery_key = '';
            $user->save();

            Mail::to($user->email)->send(new PasswordUpdated($user));
        }

        return $res;
    }
    */
}
