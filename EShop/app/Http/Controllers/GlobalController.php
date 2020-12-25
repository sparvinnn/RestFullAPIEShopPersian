<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\County;
use App\Models\Role;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class GlobalController extends Controller
{
    //get counties list
    public function counties(){
        $list = County::orderBy('id')->get(['id', 'name']);

        $response = [
            'status' => true,
            'msg' => 'list successfully get.',
            'data' => $list
        ];

        return response()->json($response);
    }

    //get cities list
    public function cities(Request $request){
        $list = City::where('county_id', $request->county_id)->orderBy('id')->get(['id', 'name']);

        $response = [
            'status' => true,
            'msg' => 'list successfully get.',
            'data' => $list
        ];

        return response()->json($response);
    }

    //get roles list
    public function roles(){
        $list = Role::orderBy('id')->get(['id', 'name']);

        $response = [
            'status' => true,
            'msg' => 'list successfully get.',
            'data' => $list
        ];

        return response()->json($response);
    }
}
