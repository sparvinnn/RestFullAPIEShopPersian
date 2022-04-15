<?php

namespace App\Helpers\giv;

class GetProductsList
{
    public function productsList(){
        
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('POST', env('API_REQUEST_URL').'v1/business-reports', [
                        'form_params' => [
                            "first_date"    => $this->first_date,
                            "last_date"     => $this->last_date,
                            "currentDate"   => $this->date,
                            "type"          => $type
                        ]
                ]);
        //decode string response to json format
        $data = json_decode($res->getBody());
    }
}