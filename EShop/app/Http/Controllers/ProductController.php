<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

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
        try{
            DB::beginTransaction();
            $inputs = $request->product[0];
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

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $e]);
        }


    }

    public function update(Request $request, $id){
        try{
            DB::beginTransaction();
            $inputs = $request->product[0];
            $product   =   Product::find($id);
            if($inputs['title']) $product->title = $inputs['title'];
            if($inputs['price']) $product->price = $inputs['price'];
            if($inputs['description']) $product->description = $inputs['description'];
            if($inputs['category_id']) $product->category_id = $inputs['category_id'];
            if($inputs['branch_id']) $product->branch_id = $inputs['branch_id'];
            if($inputs['count']) $product->count = $inputs['count'];
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

    public function search(Request $request){
        $name = $request->name;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        try{
            $list = Product::query()
                ->when($name, function ($q, $name) {
                    return $q->where('name', $name);
                })
                ->when($min_price, function ($q, $min_price) {
                    return $q->where('price', '>=', $min_price);
                })
                ->when($max_price, function ($q, $max_price) {
                    return $q->where('price', '<=', $max_price);
                })
                ->when($category_id, function ($q, $category_id) {
                    return $q->where('category_id', $category_id);
                })
                ->when($branch_id, function ($q, $branch_id) {
                    return $q->where('branch_id', $branch_id);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                // ->with('county', 'city')
//                ->with('properties.property')
//                ->with(['properties'=>function($q){
//                    $q->with('property.value');
//                }])

                ->orderBy('created_at')
                ->get();
            $data = array();
            $i = 0;
//            return $properties_filter;
            foreach ($list as $item){
                $properties = ProductProperty::query()
                    ->where('product_id', $item->id)
                    ->when($properties_filter, function ($q, $properties_filter) {
                        return $q->whereIn('product_properties.property_id', $properties_filter);
                    })
                    ->join('category_metas', 'category_metas.id', 'product_properties.property_id')
                    ->select([
                        'category_metas.value as key',
                        'product_properties.value as value',
                    ])
                    ->get();

                $images = Media::query()->where('product_id', $item->id)
                    ->pluck('url');

                $data[$i++] = array([
                    'product' => $item,
                    'properties' => $properties,
                    'images' => $images
                ]);
            }

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $data
            ];

            return response()->json($response);
        }catch(Exception $e){
            return response($e, 500);
        }
    }

}
