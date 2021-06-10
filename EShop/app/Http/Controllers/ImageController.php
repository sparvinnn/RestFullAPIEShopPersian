<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
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
            $old_files = Storage::disk('local')->files('public/product_images');
            if ($files = $request->file('file')) {
                foreach ($request->file('file') as $key => $file) {
                    $name = $file->getClientOriginalName();
                    if(in_array('/storage/upload/product_images/'.$request['product_id'].'/'.$name, $old_files))
                        return 'repeat';
                    else{
                        $img = Media::create([
                            'product_id' => $request['product_id'],
                            'url' => 'http://jahanistyle.ir/backend/storage/upload/product_images/'.$request['product_id'].'/'.$name,
                        ]);
                        $filename = $file->move('storage/upload/product_images/' . $request['product_id'] . '/', $name);
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
            $img = Media::find($id);
            if (!(empty($img->image))) {
                if (file_exists(public_path() . '/storage/upload/product_images/' . $img['product_id'] . '/' . $img->image)) {
                    unlink(public_path() . 'storage/upload/product_images/' . $img['product_id'] . '/' . $img['image']);
                }
            }
            $img->delete();
            return 'ok';
        }catch (\Exception $e){
            return 'no';
        }

    }
}
