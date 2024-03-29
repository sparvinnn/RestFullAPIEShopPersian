<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryField;
use App\Models\Field;
use App\Models\Product;
use App\Models\ProductCategoryField;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function create(Request $request){
        // return $request;
        $old = Field::where('name', $request->name)->first();
        if($old) return response()->json(["status" => false, "data" => $old, "message" => 'فیلد تکراری ثبت نمی شود']);
        $field = Field::create([
            'name' => $request->name,
            'name_en' => $request->name_en
        ]);
        return response()->json(["status" => true, "data" => $field, "message" => 'با موفقیت ثبت شد']);
    }

    public function list(){

        $fields = Field::select(['id', 'name', 'name_en', 'created_at'])->orderBy('created_at', 'Desc')->get();

        return response()->json(["status" => true, "data" => $fields, "message" => 'اطلاعات با موفقیت دریافت شد']);
    }

    public function delete($id){
        $field = Field::where('id', $id)->delete();
        return response()->json(["status" => true, "data" => $field, "message" => 'اطلاعات با موفقیت حذف شد']);
    }

    public function catFields($id){ 

        $data = CategoryField::join('fields','field_id', 'fields.id')
            ->select(['fields.id as field_id', 
                'fields.name', 
                'fields.name_en', 
                'category_fields.id as id', 
                'category_fields.category_id as cate_id',
                'category_fields.searchable'])
            ->where('category_fields.category_id', $id)
            ->get();

            // $result = [];
            // foreach($data as $item){
            //     $temp = [];
            //     $temp = $item;
            //     $item['value'] = '';
            //     array_push($temp, $result);
                
            // }

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function productFields($id){ 

        $product = Product::find($id);
        if($product && $product->category_id){
            // return 'test';
            $category = Category::where('id', $product->category_id)->first();
            if($category){
                $data = CategoryField::join('fields','field_id', 'fields.id')
                    ->select(['fields.id as field_id', 
                        'fields.name', 
                        // 'fields.name_en', 
                        'category_fields.id as id', 
                        // 'category_fields.category_id as cate_id',
                        // 'category_fields.searchable'
                        ])
                    ->where('category_fields.category_id', $category['id'])
                    ->get();
                $result = [];
                foreach($data as $item){
                    $temp = [];
                    $temp = $item;
                    $value_temp = ProductCategoryField::where('category_field_id', $item->id)->first();
                    $value = $value_temp? $value_temp['data']: null;
                    $item['value'] = $value;
                    array_push($result, $temp);
                }
                
            }
        }

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function saveProductFields(Request $request){ 
        foreach($request->fields as $item){
            $values = explode("-",$item['value']);
            foreach($values as $value){
                $old = ProductCategoryField::where('category_field_id', $item['id'])
                    ->where('product_id', $request->product_id)
                    ->delete();
                
                ProductCategoryField::create([
                    'category_field_id' => $item['id'],
                    'product_id' => $request->product_id,
                    'data' => $value
                ]);
            }
            
        }
        
        return response()->json(["status" => true, "data" => '', "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function catFieldsList(){

        $data = CategoryField::join('fields','category_fields.field_id', 'fields.id')
            ->join('categories', 'categories.id', 'category_fields.category_id')
            ->select(['fields.id as field_id', 
                'fields.name', 
                'fields.name_en', 
                'categories.name_fa as category',
                'category_fields.id as id', 
                'category_fields.category_id as cate_id',
                'category_fields.searchable',
                'category_fields.created_at'])
            // ->where('category_fields.category_id', $id)
            ->orderBy('category_fields.created_at', 'Desc')
            ->get();

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function storeCatFields(Request $request){
        // return $request;
        $data = CategoryField::create([
            'category_id' => $request->category_id,
            'field_id' => $request->field_id,
            'searchable' => $request->searchable
        ]);

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function deleteCatFields($id){

        $data = CategoryField::where('id', $id)->delete();

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }
}
