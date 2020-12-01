<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request){
        $product = new Product();
        $product->title = $request->title;
        $product->brand = $request->brand;
        $product->describe = $request->describe;
        $product->price = $request->price;
        $product->image = $request->image;
        $product->category_id = $request->route('id');
        $product->save();


    }
}
