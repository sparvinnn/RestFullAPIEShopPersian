<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
//        return Product::where('category_id', 'cat_id')
//            ->with(['properties'=>function($query) use ($request){
//                $query->whereIn('key', $request->)
//            }])->get();
    }
}
