<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CategoryController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name_fa" =>  "required",
            "name_en" =>  "required",
            "slug" =>  "required",
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
                $category->name_fa = $request->name_fa;
                $category->name_en = $request->name_en;
                $category->slug = $request->slug;
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
        $with_childeren = $request->with_childeren;
        $right_menu = $request->right_menu;
        $all = $request->all?? true;
        try{
            $list = Category::
                when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->when($name, function ($q, $name) {
                    return $q->where('name_fa', $name);
                })
                ->when($parent_id, function ($q, $parent_id) {
                    return $q->where('parent_id', $parent_id);
                })
                ->when($right_menu, function ($q) {
                    return $q->whereNull('parent_id');
                })
                ->orderBy('created_at')
                ->with(['children', 'parent', 'meta'])
                ->select([
                    'id',
                    'name_fa',
                    'name_en',
                    'slug',
                    'parent_id'
                ])
                ;

            if($all) $result = $list->get();
            else $result = $list->paginate(20);

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $result
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

    public function updateProperties(Request $request){
        
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
    }

    public function delete($id){
        try{
            $category = Category::find($id);
            $category->delete();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            return response()->json(['data'=>'error'], 500);
        }
    }

    /**
     * Upload list of files via elementUi
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
//        return $request;
        try{
            $uploadId = array();
            $old_files = Storage::disk('local')->files('public/category_images');
            if ($files = $request->file('file')) {
                foreach ($request->file('file') as $key => $file) {
                    $name = $file->getClientOriginalName();
                    if(in_array('/storage/upload/category_images/'.$request['category_id'].'/'.$name, $old_files))
                        return 'repeat';
                    else{
                        $img = CategoryMeta::create([
                            'category_id' => $request['category_id'],
                            'key' => $request['type']?? 'image',
                            'value' => 'http://localhost:8000/storage/upload/category_images/'.$request['category_id'].'/'.$name,
                        ]);
                        $filename = $file->move('storage/upload/category_images/' . $request['category_id'] . '/', $name);
                        $uploadId[] = [ $img['id'] ];
                    }
                }
            }
            return $uploadId;
        }catch(\Exception $e){
            return $e;
        }
    }

    /**
     * Upload list of files via elementUi
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $img = CategoryMeta::find($id);
            if (!(empty($img->image))) {
                if (file_exists(public_path() . '/storage/upload/category_images/' . $img['category_id'] . '/' . $img->image)) {
                    unlink(public_path() . 'storage/upload/category_images/' . $img['category_id'] . '/' . $img['image']);
                }
            }
            $img->delete();
            return 'ok';
        }catch (\Exception $e){
            return 'no';
        }

    }
}
