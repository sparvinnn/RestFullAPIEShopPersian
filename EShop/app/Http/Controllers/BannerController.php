<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index(Request $request){
        $category_id = $request->category_id;
        $use_for = $request->use_for;
        $per_page = $request->per_page ?? 10;
        $banners = Banner::when($category_id, function($query) use($category_id){
            return $query->where('category_id', $category_id);
        })
        ->when($use_for, function($query) use($use_for){
            return $query->where('use_for', $use_for);
        })
        ->paginate($per_page);
        ;

        return response()->json([
            'success' => true,
            'data' => $banners,
            'msg' => 'اطلاعات با موفقیت دریافت شد'
        ]);
    }

    public function store(Request $request){
        // try{
            $validator  =   Validator::make($request->all(), [
                'use_for' => 'required',
                'location' => 'required',
            ]);
    
            if($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "validation_errors" => $validator->errors(),
                    "msg" => 'در وارد کردن اطلاعات دقت کنید'
                ]);
            }

            $banner = Banner::create([
                'use_for' => $request->use_for,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'url' => $request->url,
            ]);
    
            return response()->json([
                'success' => true,
                'data' => $banner,
                'msg' => 'اطلاعات با موفقیت دریافت شد'
            ]);
        // }catch(\Exception $exception){
        //     return response()->json([
        //         'success' => false,
        //         'data' => $exception,
        //         'msg' => 'مشکلی به وجود آمده با پشتیبانی تماس بگیرید'
        //     ]);
        // }
        
    }

    public function update(Request $request){

    }

    public function show($id){

    }

    public function delete($id){

    }

    /**
     * Upload list of files via elementUi
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // // $file = $request->file('file')[0];
        // if($request->hasFile('file')){
        //     $upload_path = public_path('upload');
        //     $file_name = $request->file('file')[0]->getClientOriginalName();
        //     $generated_new_name = time() . '.' . $request->file('file')[0]->getClientOriginalExtension();
        //     $request->file('file')[0]->move($upload_path, $generated_new_name);
        // }
        
        
        // $insert['title'] = $file_name;
        // $check = Photo::insertGetId($insert);
        // return response()->json(['success' => 'we have successfully uploaded "' . $file_name . '"']);

        // return $request;
        try{
        //     // $file = $request->file('file');
            if($request->hasFile('file')) {

                $file = $request->file('file')[0];
            
                $name = $file->getClientOriginalName();
                
                $img = Image::create([
                    'use_for' => 'banner',
                    'url' => env('APP_URL').'/storage/upload/banners/'.$name,
                ]);
                $filename = $file->move('storage/upload/banners/', $name);        
                
            }
            return $img;
        }catch(\Exception $e){
            return $e;
        }
    }
}
