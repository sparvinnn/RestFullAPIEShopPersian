<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request){
        //Validation Data
//        $validData = $this->validate($request, [
//            'email' => 'required',
//            'password' => 'required',
//        ]);

//        //Check Login User
//        if(! auth()->attempt($validData)){
//
//            return response([
//                'data' => "اطلاعات صحیح نیست",
//                'status' => 'error'
//            ], 403);
//        }

        return new UserResource(auth()->user());
    }

    public function register(Request $request){

        $validData = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => $validData['password'],
            'api_token' => Str::random(100),
        ]);

        return new UserResource($user);

    }
}
