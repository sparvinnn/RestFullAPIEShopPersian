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
        ->get()
        // ->paginate($per_page);
        ;

        return response()->json([
            'success' => true,
            'data' => $banners,
            'msg' => 'اطلاعات با موفقیت دریافت شد'
        ]);
    }

    public function store(Request $request){
        try{
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
                'link' => $request->link
            ]);
    
            return response()->json([
                'success' => true,
                'data' => $banner,
                'msg' => 'اطلاعات با موفقیت دریافت شد'
            ]);
        }catch(\Exception $exception){
            return response()->json([
                'success' => false,
                'data' => $exception,
                'msg' => 'مشکلی به وجود آمده با پشتیبانی تماس بگیرید'
            ]);
        }
    }

    public function delete($id){
        try{
            $banner = Banner::find($id)->delete();
            return response()->json([
                'success' => true,
                'data' => $banner,
                'msg' => ' با موفقیت حذف شد'
            ]);
        }catch(\Exception $exception){
            return response()->json([
                'success' => false,
                'data' => $exception,
                'msg' => 'مشکلی به وجود آمده با پشتیبانی تماس بگیرید'
            ]);
        }
    }

    /**
     * Upload list of files via elementUi
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        try{
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

    public function filter(Request $request){ 
        $location    = $request->location;
        $use_for     = $request->use_for;
        $category_id = $request->category_id;

        try{
            $banners = Banner::when($location, function($query) use($location){
                return $query->where('location', $location);
            })
            ->when($use_for, function($query) use($use_for){
                return $query->where('use_for', $use_for);
            })
            ->when($category_id, function($query) use($category_id){
                return $query->where('category_id', $category_id);
            })
            ->leftJoin('categories', 'categories.id', 'banners.category_id')
            ->select([
                'banners.id',
                'banners.url as imageSrc',
                'banners.link',
                'banners.location',
                'banners.use_for',
                'banners.category_id',
                'categories.name_en'
            ])
            ->get()
            ;

            return response()->json([
                'success' => true,
                'data' => $banners,
                'msg' => 'اطلاعات با موفقیت دریافت شد'
            ]);
            
        }catch(\Exception $exception){
            return response()->json([
                'success' => false,
                'data' => $exception,
                'msg' => 'مشکلی به وجود آمده با پشتیبانی تماس بگیرید'
            ]);
        }
    }
}
