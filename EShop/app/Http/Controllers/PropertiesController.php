<?php

namespace App\Http\Controllers;

use App\Models\CategoryMeta;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
{
    public function create(Request $request){
        
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
            return response()->json(["status" => "success", "message" => "Success! update category_meta completed"]);
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
