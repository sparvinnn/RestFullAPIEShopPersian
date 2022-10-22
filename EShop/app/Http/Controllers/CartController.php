<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCard(Request $request){
        try{
            $old = Cart::query()
                ->where('user_id', Auth()->user()['id'])
                ->where('product_id', $request->product_id)
                ->first();
            if(!$old){
                $cart = Cart::query()->create([
                    'user_id' => Auth()->user()['id'],
                    'product_id' => $request->product_id,
                    'number'   => $request->number?? 1
                ]);
            }
            else{
                $old->number = $old->number + 1;
                $old->save();
            }
            return response()->json(['data'=>'ok'],200);
        }catch (\Exception $exception){
            return response()->json(['data'=>$exception],500);
        }
    }

    public function addAllToCard(Request $request){
        try{
            $carts = $request->carts;
            foreach ($carts as $cart) {
                $old = Cart::query()
                    ->where('user_id', Auth()->user()['id'])
                    ->where('product_id', $cart['product_id'])
                    ->first();
                if (!$old) {
                    $cart = Cart::query()->create([
                        'user_id'    => Auth()->user()['id'],
                        'product_id' => $cart['product_id'],
                        'number'     => $cart['number']
                    ]);
                }
            }
            return response()->json(['data'=>'ok'],200);
        }catch (\Exception $exception){
            return response()->json(['data'=>$exception],500);
        }
    }

    public function getCart(){
        try{
            $mycarts  = Cart::query()->where('user_id', Auth()->user()['id'])->pluck('product_id');
            $products = Product::query()->whereIn('id', $mycarts)->with('media')->get();
            $data = $products->map(function ($item) {
                return [
                    'product' => $item,
                    'number'  => Cart::query()->where('product_id', $item['id'])->first()['number']
                ];
            });
            return response()->json(['data'=>$data],200);
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

    public function update($id, Request $request){
        try{
            $cart  = Cart::query()->where('product_id', $id)->first();
            $cart->number = $request->number;
            $cart->save();
            return response()->json(['data'=>'ok'],200);
        }catch (\Exception $exception){
            return response()->json(['data'=>$exception],500);
        }
    }
}
