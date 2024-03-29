<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Brands;
use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\City;
use App\Models\Color;
use App\Models\County;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\Role;
use App\Models\Size;
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
                    'name_fa',
                    'slug',
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

    //get first level children of categories
    public function getChildren($id){
        try{
            $list = Category::where('parent_id', $id)
                ->orderBy('created_at')
                ->get([
                    'id',
                    'name_fa',
                    'slug'
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

    //get first level children of categories
    public function getAllCategory(){
        try{
            $list = Category::query()
                ->get([
                    'id',
                    'name_fa',
                    'slug',
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
            $list = Product::query()
                ->when($title, function ($q, $title) {
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

    public function getBrands(Request $request){
        $cat_id = $request->cat_id;
        $filter = Product::query()
            ->when($cat_id, function($query) use ($cat_id){
                return $query->where('category_id', $cat_id);
            })->pluck('brand_id');

        return $data = Brands::whereIn('id', $filter)->select('id','name')->get();
    }

    public function getAllBrands(Request $request){
        $id = $request->id;
        $name = $request->name;
        return Brands::query()
            ->when($id, function ($q, $id) {
                return $q->where('id', $id);
            })
            ->when($name, function ($q, $name) {
                return $q->where('name', $name);
            })
            ->select(['id', 'name'])->get();
    }

    public function search_product(Request $request){
        $title = $request->title;
        $price = $request->price;
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        try{
            $list = Product::query()
                ->when($title, function ($q, $title) {
                    return $q->where('title', 'LIKE' , '%' . $title .'%');
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

    public function home_search_box(Request $request){
        $search_txt = $request->search_txt;
        $categories = Category::where('name_fa', 'LIKE', '%'. $search_txt . '%')
            ->limit(5)
            ->select(['id', 'name_fa', 'slug'])
            ->get();

        $products_list = Product::where('name', 'LIKE', '%'. $search_txt . '%')
            ->limit(5)
            ->select([
                'id', 'name'
            ])
            ->get();

        $i = 0;
        $products = array();

        foreach($products_list as $item){
            $image = Media::query()->where('product_id', $item->id)
                    ->select('id','url')->first();
            $temp = [
                'id'=> $item->id,
                'name' => $item->name,
                'img' => $image? $image->url: null
            ];

            array_push($products, $temp);
        }
        

        $brands = Brand::where('name', 'LIKE', '%'. $search_txt . '%')
            ->limit(5)
            ->get();

        $response = [
            'status' => true,
            'msg' => 'list successfully get.',
            'categories' => $categories,
            'products' => $products,
            'brands' => $brands
        ];

        return response()->json($response);
    }

    public function filter_info(){
        $brands = Brand::select(['id', 'name'])->get()->unique();
        $categories = Category::select(['id', 'name_fa'])->get()->unique();
        $sizes = Size::select(['id', 'name'])->get()->unique();
        $colors = Color::select(['id', 'name', 'code'])->orderBy('name')->get()->unique();

        return response()->json([
            'brands' => $brands,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'status' =>true
        ], 200);
    }

}