<?php

namespace App\Helpers\giv;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\Size;

class GetProductsList
{
    public function productsList(){
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('GET', env('API_REQUEST_URL').
        'itemparent?count=2068',  [
            'headers' => [
                'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
            ],
        ]);
    
        //decode string response to json format
        $data = json_decode($res->getBody())->Value;

        foreach($data as $item){
            print('test | ');
            print($item->ItemCurrentSelPrice);
            $product = Product::create([
                'name'                   => $item->ItemName, 
                'sell_price'             => $item->ItemCurrentSelPrice?? 10,
                'description'            => null,
                'category_id'            => Category::where('category_code_giv', $item->ItemCategory->CategoryCode)->first()['id'],
                'branch_id'              => 1,   
                'inventory_number'       => 1,    
                'last_date_giv'          => $item->LastDate,
                'item_code_giv'          => $item->ItemCode,
                'is_active'              => $item->IsActive,
                'item_group_giv'         => $item->ItemGroup,
                'item_parent_id_giv'     => $item->ItemParentID
            ]);

            

            $res    = $client->request('GET', env('API_REQUEST_URL').
                'itemqoh?inputcode='.$product['item_code_giv'],  [
                    'headers' => [
                        'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
                    ],
                ]);
            
            //decode string response to json format
            $data = json_decode($res->getBody())->Value->Table->TableData->Items;

            $sum_qoh = 0;
            foreach($data as $value){

                $color = Color::whfirstOrNew([
                    'name' => $value->ItemColorName
                ]);

                $size = Size::whfirstOrNew([
                    'name' => $value->ItemSizeDesc
                ]);

                ProductProperty::create([
                    'product_id' => $product->id,
                    'size'  => $size->id,//اندازه
                    'color' => $color->id,//رنگ
                ]);
                $sum_qoh += $item->QOH;
            }

            $product['inventory_number'] = $sum_qoh;
            $product['sell_price'] = $data->SellPrice;
            $product->save();
        
        }
    }
}