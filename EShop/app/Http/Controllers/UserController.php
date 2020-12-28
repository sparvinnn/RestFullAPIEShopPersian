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
            'f_name' => 'max:12',
            'l_name' => 'max:12',
            'mobile' => 'required|min:10',
            'national_code' => 'min:10|max:10',
            'email' => 'email',
            'password' => 'required|min:6',
            'role_id' => 'required',
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
            $user->status = $request->status;
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

    //search user
    public function search(Request $request){
        $status = $request->status;
        $mobile = $request->mobile;
        $email = $request->email;
        $id = $request->id;
        $role = $request->role;
        $branch = $request->branch;
        $username = $request->username;
        $national_code = $request->national_code;
        $county = $request->county;
        $city = $request->city;
        $l_name = $request->l_name;

        try{
            $list = User::
                when($status, function ($q, $status) {
                    return $q->where('status', $status);
                })
                ->when($mobile, function ($q, $mobile) {
                    return $q->where('mobile', $mobile);
                })
                ->when($email, function ($q, $email) {
                    return $q->where('email', $email);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->when($role, function ($q, $role) {
                    return $q->where('role_id', $role);
                })
                ->when($branch, function ($q, $branch) {
                    return $q->where('branch_id', $branch);
                })
                ->when($username, function ($q, $username) {
                    return $q->where('username', $username);
                })
                ->when($national_code, function ($q, $national_code) {
                    return $q->where('national_code', $national_code);
                })
                ->when($county, function ($q, $county) {
                    return $q->where('county', $county);
                })
                ->when($city, function ($q, $city) {
                    return $q->where('city', $city);
                })
                ->when($l_name, function ($q, $l_name) {
                    return $q->where('l_name', $l_name);
                })
                ->orderBy('created_at')
                ->get();

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $list
            ];
            return response()->json($response);

        }catch(Exception $e){
            return response($e, 202);
        }
    }
}
