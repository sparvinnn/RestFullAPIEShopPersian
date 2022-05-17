<?php

namespace App\Helpers\giv;

use App\Models\Category;

class GetCategoriesList
{
    public function categoriesList(){
        print(env('API_REQUEST_URL'));
        print(env('test'));
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('GET', 'http://2.187.6.22:8201/api/'.
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
        $res    = $client->request('GET', 'http://2.187.6.22:8201/api/'.
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
                'category_is_active_giv'    => $item->CategoryIsActive,   
                'level_giv'                 => 2,    
                'last_date_giv'             => $item->LastDate,
            ]);

            $client = new \GuzzleHttp\Client(); 
            $res    = $client->request('GET', 'http://2.187.6.22:8201/api/'.
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
                    'category_is_active_giv'    => $item->CategoryIsActive,   
                    'level_giv'                 => 3,    
                    'last_date_giv'             => $item->LastDate,
                ]);
    }

    public function updateParentId(){
        $list = Category::all();

        foreach($list as $item){
            $temp = Category::find($item->id);
            $temp->parent_id = Category::
                where('category_code_giv', $temp['parent_category_code_giv'])
                ->first()['id']?? null;
            $temp->save();
        }
    }

    
}