<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\CategoryMeta;
use App\Models\CategoryProperty;
use App\Models\Color;
use App\Models\Design;
use App\Models\Material;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductCategoryField;
use App\Models\ProductProperty;
use App\Models\Size;
use Illuminate\Http\Request;

class FilterController extends Controller
{

    private $tables = [
        [
            "en" => "size",
            "fa" => 'اندازه',
            "table" => 'sizes'
        ],
        
        [
            "en" => "color",
            "fa" => "رنگ",
            "table" => 'colors'
        ]
      
    ];

    public function getProperties(Request $request)
    { 
        $result = [];
        $temp = [];
        $properties = ProductProperty::
            with('property')
            ->get()
            ->map(function ($item) use ($result, $temp) {
                array_push($temp, [
                    'key' => $item['property']['value'],
                    'value' => $item['value']
                ]);
                return $temp;
            });

        $temp = [];
        $keys = [];
        foreach ($properties as $item){
            array_push($temp, $item[0]);
        }
        foreach ($temp as $item){
            array_push($keys, $item['key']);
        }
        $keys = array_unique($keys);
        foreach ($keys as $key)
            array_push($result, [
                'key' => $key,
                'value' => ''
            ]);

        foreach ($temp as $item)
            for ($i = 0; $i < count($result); $i++)
                if ($item['key'] === $result[$i]['key'])
                   $result[$i]['value'] = explode(',', $item['value']);

        return $result;
    }

    public function filter(Request $request)
    {
        // return $request;
        $name = $request->name;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $category = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        $available = $request->available;
        $category_id = ($category!='new')? $category: null;
        $categories = $request->categories;
        $sizes = $request->sizes;
        $colors = $request->colors;
        try{
            $size_list = [];
            $color_list = [];
            
            $list = Product::query()
                ->when($name, function ($q, $name) {
                    return $q->where('name', 'LIKE', '%'.$name.'%');
                })
                ->when($min_price, function ($q, $min_price) {
                    return $q->where('sell_price', '>=', $min_price);
                })
                ->when($max_price, function ($q, $max_price) {
                    return $q->where('sell_price', '<=', $max_price);
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
                ->orderBy('updated_at', 'desc')
                ->limit($request->per_page)
                ->get();
            $count = Product::query()->count();
            if ($available == 1)
                $list->where('inventory_number', '>', 0);
            else if ($available === 0)
                $list->where('inventory_number', '=', 0);

            // return $list;
            $data = array();
            $i = 0;

            foreach ($list as $item){
                $fields = ProductCategoryField::where('product_id', $item['id'])
                    ->join('category_fields', 'category_fields.id', 'product_category_fields.category_field_id')
                    ->join('fields', 'fields.id', 'category_fields.field_id')
                    ->select(['product_category_fields.category_field_id', 'fields.id', 'fields.name'])
                    ->get();

                $category = Category::where('id', $item->category_id)->select('id','name_fa')->first();
                
                $field_data = [];
                if($fields){
                    foreach($fields as $field){
                        $temp = [];
                        $temp['key'] = $field['name'];
                        $value = ProductCategoryField::where('category_field_id', $field['category_field_id'])
                            ->where('product_id', $item['id'])
                            // ->whereNotNull('data')
                            ->first();
                        $temp['value'] = $value? $value['data']: null;
                        array_push($field_data, $temp);
                    }
                }

                $sizes = ProductProperty::where('product_id', $item['id'])
                    ->join('sizes', 'sizes.id', 'product_properties.size_id')
                    ->select(['sizes.id', 'sizes.name'])
                    ->get();

                $colors = ProductProperty::where('product_id', $item['id'])
                    ->join('colors', 'colors.id', 'product_properties.color_id')
                    ->select(['colors.id', 'colors.name'])
                    ->distinct()
                    ->get();

                $images = Media::query()->where('product_id', $item->id)
                    ->select('id','url')->get();

                $data[$i++] = array([
                    'product' => $item,
                    'fields' => $field_data,
                    'properties' => [
                        'size' => $sizes,
                        'color' => $colors,
                    ],
                    'category' => $category,
                    'images' => $images,
                    'discount' => []
                ]);
            }

            $response = [
                'status' => true,
                'msg'    => 'list successfully get.',
                'data'   => $data,
            ];

            return response()->json($response);
        }catch(\Exception $e){
            return response($e, 500);
        }
    }
}
