<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function username()
    {
        return 'mobile';
    }

    // User Register
    public function register(Request $request) {

        $validator  =   Validator::make($request->all(), [
            'username' => 'min:6|max:12',
            'f_name' => 'min:6|max:12',
            'l_name' => 'min:6|max:12',
            'mobile' => 'required|min:9',
            'national_code' => 'min:10|max:10',
            'email' => 'email',
            'password' => 'required|min:6',
            'role_id' => 'required',
            'branch_id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);

        $user   =   User::create($inputs);

        if(!is_null($user)) {
            return response()->json(["status" => "success", "message" => "Success! registration completed", "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }
    }

    // User login
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "mobile" =>  "required",
            "password" =>  "required",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $user           =       User::where("mobile", $request->mobile)->first();

        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Failed! email not found"]);
        }

        if(Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password])){
//            return User::where('mobile', $request->mobile)->first();
            $user       =       Auth::user();

            $token      =       $user->createToken('token')->plainTextToken;
            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
        }
    }


    // User Detail
    public function user() {
        $user       =       Auth::user();
        if(!is_null($user)) {
            return response()->json(["status" => "success", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }

    //userList
    public function users(){
        $users       =       User::all();
        return response()->json(["status" => "success", "data" => $users]);
    }

    //userModify
    public function modify(Request $request){
        $user = User::find($request->id);
        if(!is_null($user)) {
            DB::beginTransaction();
            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->national_code = $request->national_code;
            $user->mobile_verified_at = $request->mobile_verified_at;
            $user->county = $request->county;
            $user->city = $request->city;
            $user->address = $request->address;
            $user->postal_code = $request->postal_code;
            $user->avatar = $request->avatar;
            $user->email = $request->email;
            if($request->password) $user->password = $request->password;
            $user->role_id = $request->role_id;
            $user->branch_id = $request->branch_id;
            $user->save();
            DB::commit();

            return response()->json(["status" => "success", "data" => $user]);
        }

        else {
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }
}
