<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Namshi\JOSE\JWT;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $creds = $request->only(['email','password']);

        if(!$token=auth()->attempt($creds)){
            return response()->json([
                'success' => false,
                'message' => 'invalid credintials'
            ]);
        }
        $user = User::find(Auth::user()->id);
        $user->fcm_token = $request->fcm_token;
        $user->update();
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    public function register(Request $request){
        $encryptedPass = Hash::make($request->password);

        $user = new User;

        try {
            $user->email = $request->email;
            $user->password = $encryptedPass;
            $user->save();
            return $this->login($request);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }
    }

    public function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout berhasil'
           ]);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
           ]);

        }
    }
    //untuk nyimpen username, lastname dan foto
    public function saveUserInfo(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $photo = '';

        if($request->photo!=''){
            $photo = time().'.jpg';
            //decode foto string dan save ke storage/profiles
            file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
            $user->photo = $photo;
            
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo
        ]);
    }
}
