<?php

namespace App\Http\Controllers;

use App\Models\CategoryProperty;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function create(Request $request){
        
    }

    public function categoryProperty(Request $request){
        $category = CategoryProperty::where('category_id', $request->category_id)->first();
        $properties = $request->properties;
        foreach($properties as $item){
            $category[$item] = 1;
        }

    }
}