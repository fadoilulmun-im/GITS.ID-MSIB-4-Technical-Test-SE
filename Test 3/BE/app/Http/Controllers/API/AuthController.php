<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Validator;
use Auth;
use Hash;
use DB;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return setResponse(
                $validator->errors(),
                'Please fill in the form correctly',
                400,
            );
        }

        DB::transaction(function () use($req) {
            $data = new User;
            $data->name = $req->name;
            $data->email = $req->email;
            $data->password = Hash::make($req->password);
            $data->save();
        });

        return setResponse(null, 'Register successfully', 200);
    }

    public function login(Request $req)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return setResponse(
                $validator->errors(),
                'Please fill in the form correctly',
                400,
            );
        }

        $credentials = [
            'email' => $req->email,
            'password' => $req->password,
        ];

        $token = Auth::attempt($credentials);

        if (!empty($token)) {

            $user = User::find(auth()->user()->id);

            return setResponse(
                [
                    'user' => $user,
                    'token' => $this->respondWithToken($token)
                ],
                'Login successfully'
            );
        }

        return setResponse(null, 'Incorrect email or password', 404);
    }

    public function logout()
    {
        Auth::logout(true);
        return setResponse(null, 'Logout successfully', 200);
    }

    protected function respondWithToken($token) {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
