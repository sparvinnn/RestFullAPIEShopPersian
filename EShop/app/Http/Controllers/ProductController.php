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
                ProductProperty::query()->create([
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
//        try{
            DB::beginTransaction();
            $inputs = $request->product[0];
            $product   =   Product::find($id);
            if($inputs['name']) $product->name = $inputs['name'];
            if($inputs['price']) $product->price = $inputs['price'];
            if($inputs['description']) $product->description = $inputs['description'];
            if($inputs['category_id']) $product->category_id = $inputs['category_id'];
            if($inputs['branch_id']) $product->branch_id = $inputs['branch_id'];
            if($inputs['inventory_number']) $product->inventory_number = $inputs['inventory_number'];
            if($inputs['total_number']) $product->total_number = $inputs['total_number'];
            if($inputs['sales_number']) $product->sales_number = $inputs['sales_number'];
            if($inputs['brand_id']) $product->brand_id = $inputs['brand_id'];
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

//        }catch (\Exception $e){
//            DB::rollBack();
//            return response()->json(["status" => "failed", "message" => $e]);
//        }
    }

    public function search(Request $request){
        $name = $request->name;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        $available = $request->available;
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
                ->orderBy('created_at');
            if ($available == 1)
                $list->where('inventory_number', '>', 0);
            else if ($available === 0)
                $list->where('inventory_number', '=', 0);

            $data = array();
            $i = 0;

            foreach ($list->get() as $item){
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
                    ->select('id','url')->get();

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

    public function delete($id){
        try{
            $product = Product::find($id);
            $product->delete();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            return response()->json(['data'=>'error'], 500);
        }
    }
}
