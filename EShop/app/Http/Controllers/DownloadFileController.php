<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    $file = Storage::disk('public')->get($file_name);
  
        return (new Response($file, 200))
              ->header('Content-Type', 'image/jpeg');
}
