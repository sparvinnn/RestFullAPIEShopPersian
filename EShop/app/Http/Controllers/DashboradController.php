<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboradController extends Controller
{
    public function home(){
        $user = Auth::user();
        if($user->hasRole('SuperAdmin')){

        }

    }
}
