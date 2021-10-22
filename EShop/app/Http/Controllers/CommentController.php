<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function get($id){
        try{
            $comment = Comment::query()
                ->where('id', $id)
                ->with('user')
                ->with('product')
                ->with('admin')
                ->get()
                ->map(function($item){
                    return [
                        'id'          => $item->id,
                        'user_id'     => $item->user_id,
                        'user_name'   => $item->user->f_name?? '' . ' ' . $item->user->l_name?? '',
                        'product_id'  => $item->product_id,
                        'product_name'=> $item->product->name?? '',
                        'title'       => $item->title,
                        'description' => $item->description,
                        'suggestion'  => $item->suggestion,
                        'status'      => $item->status,
                        'parent_id'   => $item->parent_id,
                        'admin_id'    => $item->admin_id,
                        'admin_name'  => $item->user->admin?? '' . ' ' . $item->user->admin,
                    ];

                })
            ;
            if($comment) return response()->json(["status" => "success", "message" => "Success! fined.", "data" => $comment], 200);
            else return response()->json(["status" => "fail", "message" => "not Exist!"], 200);
            DB::commit();
        }catch (\Exception $exception){
            return response()->json(["status" => "fail", "message" => $exception], 500);
        }
    }

    public function getAll(Request $request){
        $product_id = $request->product_id;
        $product_title = $request->product_title;
        $mobile = $request->mobile?? null;
        $user_id = null;
        try{
            if($product_title){
                $product = Product::query()->where('name', $product_title)->first();
                if($product) $product_id = $product->id;
            }

            if($mobile){
                $user = User::query()->where('mobile', $mobile)->first();
                if($user) $user_id = $user->id;
            }

            $comments = Comment::query()
                ->when($product_id, function ($query) use ($product_id){
                    return $query->where('product_id', $product_id);
                })
                ->when($user_id, function ($query) use ($user_id){
                    return $query->where('user_id', $user_id);
                })
                ->with('user')
                ->with('product')
                ->with('admin')
//                ->with('children')
                ->get()
                ->map(function($item){
                    return [
                        'id'          => $item->id,
                        'user_id'     => $item->user_id,
                        'user_name'   => $item->user->f_name?? '' . ' ' . $item->user->l_name?? '',
                        'product_id'  => $item->product_id,
                        'product_name'=> $item->product->name?? '',
                        'title'       => $item->title,
                        'description' => $item->description,
                        'suggestion'  => $item->suggestion,
                        'status'      => $item->status,
                        'parent_id'   => $item->parent_id,
                        'admin_id'    => $item->admin_id,
                        'admin_name'  => $item->user->admin?? '' . ' ' . $item->user->admin,
                    ];

                });
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
                'suggestion'  => $request->suggestion?? 1,
                'parent_id'   => $request->parent_id?? null,
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
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! delete completed", "data" => $comment], 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception, 500);
        }
    }

    public function update($id, Request $request){
        DB::beginTransaction();
        try{
            $comment = Comment::query()->find($id);

            if($request->user_id)
                $comment->user_id = $request->user_id;
            if($request->product_id)
                $comment->product_id = $request->product_id;
            if($request->status == '1' || $request->status == '0')
                $comment->status = $request->status;
            if($request->ssuggestion)
                $comment->suggestion = $request->suggestion;

            $comment->title         =   $request->title;
            $comment->description   =   $request->description;
            $comment->parent_id     =   $request->parent_id;
            $comment->admin_id      =   Auth::id();
            $comment->save();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! edit completed", "data" => $comment], 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception, 500);
        }
    }

}
