<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function login(Request $request){
        $user= User::where('email', $request->email)->first();
        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
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
            'password' => Hash::make($validData['password']),
            'api_token' => Str::random(100),
        ]);

        return new UserResource($user);

    }

    public function users(){
        return User::all();
    }
}
