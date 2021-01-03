<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CategoryController extends Controller
{
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            "name" =>  "required",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }
        try{
            $input = $request->all();
            $category = Category::create($input);
            return response()->json(["status" => "success", "message" => "Success! create category completed", "data" => $category]);
        }catch (\Exception $exception){
            return response()->json(["status" => "failed", "message" => $exception]);
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if(!is_null($category)) {
            DB::beginTransaction();
            try{
                $category->name = $request->name;
                $category->parent_id = $request->parent_id;
                $category->save();
                DB::commit();
                return response()->json(["status" => "success", "data" => $category]);
            }catch (Exception $exception){
                DB::rollBack();
                return response()->json(["status" => "failed", "message" => $exception]);
            }
        }
        else {
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => "Whoops! no category found"]);
        }
    }

    public function search(Request $request){
        $id = $request->id;
        $name = $request->name;
        $parent_id = $request->parent_id;
        try{
            $list = Category::
                when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->when($name, function ($q, $name) {
                    return $q->where('name', $name);
                })
                ->when($parent_id, function ($q, $parent_id) {
                    return $q->where('parent_id', $parent_id);
                })
                ->orderBy('created_at')
                ->with(['children', 'parent', 'meta'])
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

    public function addProperties(Request $request){
        $list = $request->properties;
        for($i=0; $i<count($list); $i++){
            try{
                $category_meta= CategoryMeta::create([
                    'category_id' => $request->id,
                    'key' => 'property',
                    'value' => $list[$i]
                ]);
            }catch (\Exception $exception){
                return response()->json(["status" => "failed", "message" => $exception]);
            }
        }
        return response()->json(["status" => "success", "message" => "Success! add category_meta completed"]);
    }

    //get properties for specific category
    public function getProperties($id){
        try{
            $properties = CategoryMeta::where('category_id', $id)->where('key', 'property')->select('id', 'value')->get();
            return response()->json(["status" => "success", "data" => $properties]);
        }catch (\Exception $exception){
            return response()->json(["status" => "failed", "message" => $exception]);
        }
    }
}
