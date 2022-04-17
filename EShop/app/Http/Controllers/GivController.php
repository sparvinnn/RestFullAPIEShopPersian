<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\giv\GetCategoriesList;

class GivController extends Controller
{
    public function getCategoriesList(){
        $temp = new GetCategoriesList();
        $temp->categoriesList();
    }
}
