<?php

namespace App\Helpers\giv;

class GetCategoriesList
{
    public function categoriesList(){
        
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('POST', env('API_REQUEST_URL').'v1/business-reports', [
                        'form_params' => [
                            
                        ],
                        ['headers' => 
                            [
                                'WEB_TOKEN' => "727c8e6b-e34f-49fe-9abe-59d5e4301e74"
                            ]
                        ]
                ]);
        //decode string response to json format
        $data = json_decode($res->getBody());
    }
}