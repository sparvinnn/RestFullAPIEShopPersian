<?php

namespace App\Http\Controllers;

use App\Models\CategoryField;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function create(Request $request){
        // return $request;
        $field = Field::create([
            'name' => $request->name,
            'name_en' => $request->name_en
        ]);
        return response()->json(["status" => true, "data" => $field, "message" => 'با موفقیت ثبت شد']);
    }

    public function list(){

        $fields = Field::select(['id', 'name', 'name_en'])->get();

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

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
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
                'category_fields.searchable'])
            // ->where('category_fields.category_id', $id)
            ->get();

        return response()->json(["status" => true, "data" => $data, "message" => 'اطلاعات با موفقیت ثبت شد']);
    }

    public function storeCatFields(Request $request){

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
