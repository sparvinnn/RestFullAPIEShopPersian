<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){
        $ordering = $request->ordering;
        $best_selling = Product::query()->orderBy('sales_number')->take(20)->get();
        $most_popular = Product::query()->orderBy('rate')->take(20)->get();
        $slides = Slide::query()->get();
        $banners = Banner::query()->get();
        return response()->json([
            'ordering'      => $ordering,
            'best_selling'  => $best_selling,
            'most_popular'  => $most_popular,
            'slides'        => $slides,
            'banners'       => $banners,
        ]);
    }
}
