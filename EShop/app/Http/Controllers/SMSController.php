<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class SMSController extends Controller
{
    public function sendsms(Request $request){
        $mobile = $request->mobile;
        $status = $request->status;
        $user = User::where('mobile', '=', $mobile)->first();
        $code = rand(1234, 9999);
        $verify = 'login';
        if($status !== 'login') $verify = 'verify';
        if ($user) {
            $user->auth_code = $code;
            $user->save();
        }
        else {
            $user = $this->create($mobile, $code);
        }
        return $this->verification($mobile, $code, $verify);
    }

    public function verification($receptor, $token, $status)
    {
        $verify = 'login';
        if($status !== 'login') $verify = 'verify';
        try{
            $result = Kavenegar::VerifyLookup($receptor,$token,'','',$verify,'sms');
//            $this->format($result);
        }
        catch(ApiException $e){
            echo $e->errorMessage();
        }
        catch(HttpException $e){
            echo $e->errorMessage();
        }
    }
}
