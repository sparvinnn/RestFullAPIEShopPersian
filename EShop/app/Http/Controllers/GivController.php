<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\giv\GetCategoriesList;
use App\Helpers\giv\GetProductsList;

class GivController extends Controller
{
    public function getCategoriesList(){
        $temp = new GetCategoriesList();
        $temp->categoriesList();
    }

    public function categoriesUpdate(){
        $temp = new GetCategoriesList();
        $temp->updateParentId();
    }

    public function getProductsList(){
        $temp = new GetProductsList();
        $temp->productsList();
    }
}
