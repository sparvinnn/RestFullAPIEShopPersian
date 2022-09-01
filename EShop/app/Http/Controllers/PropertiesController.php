<?php

namespace App\Http\Controllers;

use App\Models\CategoryMeta;
use App\Models\CategoryProperty;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
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

    public function save(Request $request){
        // return $request;
        $table= $request->table;
        try{
            DB::table($table)->insert([
                'name' => $request->name
            ]);
            return response()->json([
                "success" => true, 
                "message" => "Success! update category_meta completed"
            ]);
        }catch(\Exception $exception){
            return response()->json([
                "success" => false, 
                "message" => "Success! update category_meta failed"]);
        }

    }

    public function list(){
        $data = [];
        foreach($this->tables as $item){
            $temp = [];
            $temp['en'] = $item['en'];
            $temp['fa'] = $item['fa'];
            $temp['table'] = $item['table'];
            $temp['data'] = DB::table($item['table'])
                            ->select(['id', 'name'])
                            ->get();
            array_push($data, $temp);
        }

        return response()->json(
            [
                "status" => "success", 
                "message" => "Success! registration completed", 
                "data" => $data
            ]);
    }

    public function listWithProductId($id){
        $data = [];
        $product = Product::where('id', $id)->first();
        if(!$product) 
            return response()->json(
                [
                    "success" => false, 
                    "message" => "failed! Product not find", 
                ],404);
                
        $category_id = Product::where('id', $id)->first()->category_id;
        $category_property_list = CategoryProperty::where('id', $category_id)->first();
        foreach($this->tables as $item){
            if($category_property_list[$item['en']]!=1) continue;
            $temp = [];
            $temp['en'] = $item['en'];
            $temp['fa'] = $item['fa'];
            $temp['table'] = $item['table'];
            $temp['data'] = DB::table($item['table'])
                            ->select(['id', 'name'])
                            ->get();
            array_push($data, $temp);
        }

        return response()->json(
            [
                "status" => true, 
                "message" => "Success! registration completed", 
                "data" => $data
            ], 200);
    }

    public function updateProperties(Request $request){
        // return 'test';
        $list = $request->properties;
        DB::beginTransaction();
        try{
            // $item = CategoryMeta::where('key', 'property')
            //     ->where('category_id', $request->id)
            //     ->first();
            $category_property= CategoryProperty::create([
                'category_id' => $request->id
            ]);
            for($i=0; $i<count($list); $i++){
                try{
                    
                    // $list[$i]['en'] => 1,
                    $category_property[$list[$i]['en']] = 1;
                    
                }catch (\Exception $exception){
                    return response()->json(["status" => "failed", "message" => $exception]);
                }
            }
            $category_property->save();
            DB::commit();
            return response()->json([
                "status" => "success", 
                "message" => "Success! update category_meta completed"]);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $exception]);
        }
        
        // $category = CategoryProperty::where('category_id', $request->category_id)->first();
        // $properties = $request->properties;
        // foreach($properties as $item){
        //     $category[$item] = 1;
        // }

        // return response()->json([
        //     'msg' => 'با موفقیت انجام شد'
        // ],200);

    }

    public function allProperties(){
        $data = [
            'size',
            'material',
            'color',
            'design',
            'sleeve',
            'piece',
            'set_type',
            'description',
            'maintenance',
            'made_in',
            'origin',
            'type',
            'for_use',
            'collar',
            'height',
            'physical_feature',
            'production_time',
            'demension',
            'crotch',
            'close',
            'drop',
            'cumin',
            'close_shoes',
            'specialized_features',
            'typeـofـclothing'
        ];
        $data_fa = [
            'سایز',
            'جنس',
            'رنگ',
            'طرح',
            'آستین',
            'تعداد تکه',
            'نوع ست',
            'نگهداری',
            'کشور تولید کننده',
            'کشور مبدا',
            'نوع',
            'مناسب برای استفاده',
            'یقه',
            'قد',
            'ویژگی های ظاهری',
            'زمان تولید',
            'ابعاد',
            'فاق',
            'نحوه بسته شدن',
            'دراپ',
            'زیره',
            'نحوه بسته شدن کفش',
            'ویژگی های تخصصی',
            'نوع لباس'
        ];

        $ressult = [];
        for($i=0 ; $i<23; $i++){
            $temp['en'] = $data[$i];
            $temp['fa'] = $data_fa[$i];
            array_push($ressult,$temp);
        }

        return response()->json([
            'data' => $ressult,
            'msg' => 'با موفقیت انجام شد'
        ],200);
    }
}
