<?php

namespace App\Helpers\giv;

use App\Models\Category;

class GetCategoriesList
{
    public function categoriesList(){
        
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('GET', env('API_REQUEST_URL').
        'itemcategoryl1?count=2000',  [
            'headers' => [
                'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
            ],
        ]);
    
        //decode string response to json format
        $data = json_decode($res->getBody())->Value;

        foreach($data as $item)
            Category::create([
                'name_fa'                   => $item->CategoryName, 
                'category_id_giv'           => $item->CategoryID,
                'category_code_giv'         => $item->CategoryCode,
                'parent_category_code_giv'  => $item->CategoryID,
                'category_is_active_giv'    => $item->CategoryIsActive,   
                'level_giv'                 => 1,    
                'last_date_giv'             => $item->LastDate,
            ]);


        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('GET', env('API_REQUEST_URL').
        'itemcategoryl2?count=2000',  [
            'headers' => [
                'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
            ],
        ]);
    
        //decode string response to json format
        $data = json_decode($res->getBody())->Value;

        foreach($data as $item)
            Category::create([
                'name_fa'                   => $item->CategoryName, 
                'category_id_giv'           => $item->CategoryID,
                'category_code_giv'         => $item->CategoryCode,
                'parent_category_code_giv'  => $item->ParentCategoryCode,
                'parent_category_code_giv'  => $item->CategoryID,
                'category_is_active_giv'    => $item->CategoryIsActive,   
                'level_giv'                 => 2,    
                'last_date_giv'             => $item->LastDate,
            ]);

            $client = new \GuzzleHttp\Client(); 
            $res    = $client->request('GET', env('API_REQUEST_URL').
            'itemcategoryl3?count=2000',  [
                'headers' => [
                    'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
                ],
            ]);
        
            //decode string response to json format
            $data = json_decode($res->getBody())->Value;
    
            foreach($data as $item)
                Category::create([
                    'name_fa'                   => $item->CategoryName, 
                    'category_id_giv'           => $item->CategoryID,
                    'category_code_giv'         => $item->CategoryCode,
                    'parent_category_code_giv'  => $item->ParentCategoryCode,
                    'parent_category_code_giv'  => $item->CategoryID,
                    'category_is_active_giv'    => $item->CategoryIsActive,   
                    'level_giv'                 => 3,    
                    'last_date_giv'             => $item->LastDate,
                ]);
    }

    
}