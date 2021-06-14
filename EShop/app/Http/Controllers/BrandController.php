<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{

    public function store(Request $request){
        DB::beginTransaction();
        try{
            Brands::create([
                'name' => $request->name
            ]);
            DB::commit();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['data'=>'error'], 500);
        }
    }

    public function update($id, Request $request){
        DB::beginTransaction();
        try{
            $brand = Brands::find($id);
            $brand->name = $request->name;
            $brand->save();
            DB::commit();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['data'=>'error'], 500);
        }
    }

}
