<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\v1\Category as CategoryResource;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function single(Category $category){
        return new CategoryResource($category);
    }

    public function store(Request $request){
        $category = new Category();

        $category->title = $request->title;
        $category->image = $request->image;
        $category->save();
    }
}
