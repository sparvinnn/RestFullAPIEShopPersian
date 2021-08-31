<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function get($id){
        try{
            $comment = Comment::query()->find($id);
            if($comment) return response()->json(["status" => "success", "message" => "Success! fined.", "data" => $comment], 200);
            else return response()->json(["status" => "fail", "message" => "not Exist!"], 200);
            DB::commit();
        }catch (\Exception $exception){
            return response()->json(["status" => "fail", "message" => $exception], 500);
        }
    }

    public function getAll(){
        try{
            $comments = Comment::query()->with('children')->get();
            if($comments) return response()->json(["status" => "success", "message" => "Success! fined.", "data" => $comments], 200);
            else return response()->json(["status" => "empty"], 200);
            DB::commit();
        }catch (\Exception $exception){
            return response()->json(["status" => "fail", "message" => $exception], 500);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
            $comment = Comment::query()->create([
                'user_id'     => Auth::id(),
                'product_id'  => $request->product_id,
                'title'       => $request->title?? null,
                'description' => $request->description,
                'suggestion'  => $request->suggestion?? null,
                'parent_id'   => $request->parent_id?? null
            ]);

            if(!is_null($comment)) {
                DB::commit();
                return response()->json(["status" => "success", "message" => "Success! store completed", "data" => $comment], 200);
            }
            else {
                return response()->json(["status" => "failed", "message" => "Store failed!"], 500);
            }

        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception, 500);
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $comment = Comment::query()->find($id);
            if($comment) $comment->delete();
            else return response()->json(["status" => "success", "message" => "not Exist!"], 200);
            return response()->json(["status" => "success", "message" => "Success! delete completed", "data" => $comment], 200);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception, 500);
        }
    }

    public function update($id, Request $request){
        DB::beginTransaction();
        try{
            $comment = Comment::query()->find($id);

            if($request->user_id) $comment->user_id    = $request->user_id;
            if($request->product_id) $comment->product_id = $request->product_id;

            $comment->title         =   $request->title;
            $comment->description   =   $request->description;
            $comment->suggestion    =   $request->suggestion;
            $comment->parent_id     =   $request->parent_id;
            $comment->save();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! edit completed", "data" => $comment], 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception, 500);
        }
    }

}
