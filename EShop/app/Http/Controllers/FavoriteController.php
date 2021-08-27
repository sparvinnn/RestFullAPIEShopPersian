<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function addToFavorite(Request $request){
//        try{
            $old = Favorite::query()
                ->where('user_id', Auth()->user()['id'])
                ->where('product_id', $request->product_id)
                ->first();
            if(!$old){
                $cart = Favorite::query()->create([
                    'user_id' => Auth()->id(),
                    'product_id' => $request->product_id
                ]);
                return response()->json(['data'=>'ok'],200);
            }
            return response()->json(['data'=>'duplicate'],200);
//        }catch (\Exception $exception){
//            return response()->json(['data'=>$exception],500);
//        }
    }

    public function getFavorite(){
        try{
            $mycarts  = Favorite::query()->where('user_id', Auth()->user()['id'])->pluck('product_id');
            $products = Product::query()->whereIn('id', $mycarts)->with('media')->get();
            return response()->json(['data'=>$products],200);
        }catch (\Exception $exception){
            return response()->json(['data'=>$exception],500);
        }
    }

    public function delete($id){
        try{
            $cart  = Cart::query()->where('product_id', $id)->delete();
            return response()->json(['data'=>'ok'],200);
        }catch (\Exception $exception){
            return response()->json(['data'=>$exception],500);
        }
    }
}
