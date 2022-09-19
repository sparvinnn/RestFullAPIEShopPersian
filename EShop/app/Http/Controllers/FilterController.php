<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\CategoryProperty;
use App\Models\Color;
use App\Models\Design;
use App\Models\Material;
use App\Models\Media;
use App\Models\Product;
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
        $category_id = $request->category_id;
        $branch_id = $request->branch_id;
        $id = $request->id;
        $properties_filter = $request->properties_filter;
        $available = $request->available;
        // try{
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
                ->limit(20)
                ->get();
            if ($available == 1)
                $list->where('inventory_number', '>', 0);
            else if ($available === 0)
                $list->where('inventory_number', '=', 0);

            $data = array();
            $i = 0;

            foreach ($list as $item){
                $property_keys = CategoryProperty::
                    when($category_id, function ($q, $category_id) {
                        return $q->where('category_id', $category_id);
                    })
                // where('category_id', 9)
                    ->first();

                $category = Category::where('id', $item->category_id)->select('id','name_fa')->first();
                    
                if($property_keys){
                    $size = $property_keys->size? ProductProperty::query()
                    ->where('product_id', $item->id)
                    ->whereNotNull('size_id')
                    ->pluck('size_id'): null;
                    if($size) $size_list = Size::whereIn('id', $size)->pluck('name');

                    $material = $property_keys->material? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('material_id')
                        ->pluck('material_id'): null;
                    if($material) $material_list = Material::whereIn('id', $material)->pluck('name');

                    $color = $property_keys->color? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('color_id')
                        ->pluck('color_id'): null;
                    if($color) $color_list = Color::whereIn('id', $color)->pluck('name');

                    $design = $property_keys->design? ProductProperty::query()
                        ->where('product_id', $item->id)
                        ->whereNotNull('design_id')
                        ->pluck('design_id'): null;
                    if($design) $design_list = Design::whereIn('id', $design)->pluck('name');
                }else{
                    $size_list = null;
                    $design_list = null;    
                    $color_list = null;     
                }
                


                // $properties = ProductProperty::query()
                //     ->where('product_id', $item->id)
                    // ->when($properties_filter, function ($q, $properties_filter) {
                    //     return $q->whereIn('product_properties.property_id', $properties_filter);
                    // })
                    // ->join('category_metas', 'category_metas.id', 'product_properties.property_id')
                    // ->select([
                    //     'category_metas.value as key',
                    //     'product_properties.value as value',
                    // ])
                    // ->get();

                $images = Media::query()->where('product_id', $item->id)
                    ->select('id','url')->get();

                $data[$i++] = array([
                    'product' => $item,
                    'properties' => [
                        'size' => $size_list,
                        'color' => $color_list,
                    ],
                    'category' => $category,
                    'images' => $images,
                    'discount' => []
                ]);
            }

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $data
            ];

            return response()->json($response);
        // }catch(Exception $e){
        //     return response($e, 500);
        // }
    }
}
