<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Upload list of files via elementUi
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        try{
            $uploadId = array();
            $old_files = Storage::disk('local')->files('public/product_images');
            if ($files = $request->file('file')) {
                foreach ($request->file('file') as $key => $file) {
                    $name = $file->getClientOriginalName();
                    if(in_array('public/product_images/'.$request['product_id'].'/'.$name, $old_files))
                        return 'repeat';
                    else{
                        $img = Media::create([
                            'package_id' => $request['product_id'],
                            'url' => 'public/product_images/'.$request['product_id'].'/'.$name,
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
            $img = Images::find($id);
            if (!(empty($img->image))) {
                if (file_exists(public_path() . 'storage/upload/product_images/' . $img['product_id'] . '/' . $img->image)) {
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
