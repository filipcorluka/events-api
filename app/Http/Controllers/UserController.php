<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if($user && Hash::check($request->input('password'), $user->password)){
            $api_token = base64_encode(Str::random(40));
            User::where('email', $request->input('email'))->update(['api_token' => $api_token]);;
            return response()->json(['status' => 'success', 'api_token' => $api_token]);
        } else {
            return response()->json(['status' => 'fail' ], 401);
        }
    }
}