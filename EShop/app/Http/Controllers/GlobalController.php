<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\City;
use App\Models\County;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use Mockery\Exception;
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

        return response()->json($list);
    }

    //get parent categories
    public function getParents(){
        try{
            $list = Category::whereNull('parent_id')
                ->orderBy('created_at')
                ->get([
                    'id',
                    'name',
                    'parent_id'
                ]);

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

    //get and filter products
    public function getProducts(Request $request){
        $title = $request->title;
        $price = $request->price;
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        try{
            $list = Product::
                when($title, function ($q, $title) {
                    return $q->where('title', $title);
                })
                ->when($price, function ($q, $price) {
                    return $q->where('price', $price);
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
                return $properties = ProductProperty::
                    where('product_id', $item->id)
                    ->when($properties_filter, function ($q, $properties_filter) {
                        return $q->whereIn('product_properties.property_id', $properties_filter);
                    })
                    ->join('category_metas', 'category_metas.id', 'product_properties.property_id')
//                    ->select([
//                        'product_properties.id',
//                        'product_properties.property_id',
//                        'category_metas.value as property',
//                        'product_properties.value as value',
//                    ])
                    ->get();

                $data[$i++] = array([
                    'product' => $item,
                    'properties' => $properties
                ]);
            }

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $data
            ];

            return response()->json($response);
        }catch(Exception $e){
            return response($e, 202);
        }
    }
}
