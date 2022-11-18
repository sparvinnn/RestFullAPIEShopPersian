<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboradController extends Controller
{
    public function home(){
        $user = Auth::user();
        if($user->hasRole('SuperAdmin')){

        }

    }

    public function init_info(){
        try{
            $products = Product::count();
            $users = User::count();
            $categories = Category::count();
            $orders = Order::count();

            return response()->json([
                'status' => true,
                'users' =>$users,
                'products' => $products,
                'categories' => $categories,
                'orders' => $orders
            ]);

        }catch(\Exception $exception){
            return response()->json([
                'status' => false,
                'error' => $exception
            ]);
        }
    }
}
