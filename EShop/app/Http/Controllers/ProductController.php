<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        try{
            DB::beginTransaction();
            $inputs = $request->product[0];
//            return $inputs['category_id'];
            $product   =   Product::create($inputs);

            $inputs = $request->properties[0];

            foreach($inputs as $input)
                ProductProperty::create([
                    'product_id'=>$product->id,
                    'property_id'=>$input[0]['property_id'],
                    'value'=>$input[0]['value'],
                ]);
            $properties = ProductProperty::where('product_id', $product->id)
                ->get();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! registration completed", "product" => $product, "properties" => $properties]);

//        }catch (\Exception $e){
//            DB::rollBack();
//            return response()->json(["status" => "failed", "message" => $e]);
//        }


    }

    public function update(Request $request, $id){
        try{
            DB::beginTransaction();
            $inputs = $request->product[0];
            $product   =   Product::find($id);
            $product->title = $inputs['title'];
            $product->price = $inputs['price'];
            $product->description = $inputs['description'];
            $product->categories = $inputs['categories'];
            $product->branch_id = $inputs['branch_id'];
            $product->save();

            $inputs = $request->properties[0];
            ProductProperty::where('product_id', $product->id)->delete();
            foreach($inputs as $input){
                ProductProperty::create([
                    'product_id'=>$product->id,
                    'property_id'=>$input[0]['property_id'],
                    'value'=>$input[0]['value'],
                ]);
            }

            $properties = ProductProperty::where('product_id', $product->id)
                ->get();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! registration completed", "product" => $product, "properties" => $properties]);

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $e]);
        }
    }

}
