<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\Product;
use App\Models\ProductProperty;
use Illuminate\Http\Request;

class FilterController extends Controller
{
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
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $cat_id = $request->cat_id;
        $available = $request->available;
        $ordering = $request->ordering;
        $cats = Category::query()->where('parent_id', $cat_id)->pluck('id');
        $cats->push($cat_id);
        if (!$cat_id) $cats = null;
        $brand_id = $request->brand_id;
        $id = $request->id;
        if(isset($request->properties[0])){
            $properties = $request->properties[0];
            $index = 0;
            foreach ($properties as $property){
                $property['value'] = explode(',', $property['value']);
                $properties[$index] = $property;
                $index++;
            }
        }

        $data = Product::query()
//            ->whereIn('category_id', $cats)
//            ->where('category_id', $cat_id)
            ->when($id, function($query) use ($id){
                return $query->where('id', $id);
            })
            ->when($min_price, function($query) use ($min_price){
                return $query->where('price', '>=', $min_price);
            })
            ->when($max_price, function($query) use ($max_price){
                return $query->where('price', '<=', $max_price);
            })
            ->when($cats, function($query) use ($cats){
                return $query->whereIn('category_id', $cats);
            })
            ->when($brand_id, function($query) use ($brand_id){
                return $query->whereIn('brand_id', $brand_id);
            })
            ->when($available, function($query) use ($available){
                if($available) return $query->where('inventory_number', '>', 0);
                else return $query->where('inventory_number', 0);
            })
            ->when($ordering, function($query) use ($ordering){
                if($ordering === 'BestSelling') return $query->orderBy('sales_number');
                else if($ordering === 'MostPopular') return $query->orderBy('rate');
            })
//            ->when($properties, function($query) use ($properties){
//                foreach ($properties as $property){
//                    $query->
//                }
//            })


//            ->with(['properties'=>function($query) use ($request){
//                $query->whereIn('key', $request->)
//            }])
            ->with('media', 'properties')
//            ->get();
            ->paginate(20);

        return $list = $data->map(function ($item) {
            $product = [
                "id"                => $item->id,
                "name"              => $item->name,
                "price"             => $item->price,
                "description"       => $item->description,
                "category_id"       => $item->category_id,
                "inventory_number"  => $item->inventory_number,
                "total_number"      => $item->total_number,
                "sales_number"      => $item->sales_number,
                "rate"              => $item->rate,
                "vote"              => $item->vote,
            ];
            $properties = [];
            $x = [
                'key' => '',
                'value' => ''
            ];

            foreach ($item['properties'] as $temp){
                if(CategoryMeta::query()->where('id', $temp['property_id'])->first()){
                    $x['id']    = $temp['id'];
                    $x['property_id']    = $temp['property_id'];
                    $x['key'] = CategoryMeta::query()->where('id', $temp['property_id'])->firstOrFail()['value'];
                    $x['value'] = $temp['value'];
                    array_push($properties, $x);
                }
            }

            $product['properties'] = $properties;

            $medias = [];
            $x = [
                'id' => '',
                'url' => ''
            ];
            foreach ($item['media'] as $temp){
                $x['id']    = $temp['id'];
                $x['url']   = $temp['url'];
                array_push($medias, $x);
            }
            if($item->brand_id)
                $product['brand'] = [
                    "id"         => $item->branch_id,
                    "name"       => Brands::find($item->brand_id)['name']
                ];


            $product['media'] = $medias;

            return $product;
        });
    }
}
