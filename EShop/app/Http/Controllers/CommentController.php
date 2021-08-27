<?php

namespace App\Http\Controllers;

use App\Models\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request){

        try{
            $comment = Comment::query()->create([
                'user_id'     => Auth::id(),
                'product_id'  => $request->product_id,
                'title'       => $request->title?? null,
                'description' => $request->description,
                'suggestion'  => $request->suggestion?? null,
                'parent_id'   => $request->parent_id?? null
            ]);

            return 'test';

            if(!is_null($comment)) {
                return response()->json(["status" => "success", "message" => "Success! store completed", "data" => $comment], 200);
            }
            else {
                return response()->json(["status" => "failed", "message" => "Store failed!"], 500);
            }

        }catch (\Exception $exception){
            return response()->json($exception, 500);
        }
    }

    public function delete(Request $request){}

    public function update(Request $request){}
}
