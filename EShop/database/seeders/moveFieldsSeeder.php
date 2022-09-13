<?php

namespace Database\Seeders;

use App\Models\CategoryField;
use App\Models\CategoryProperty;
use App\Models\Field;
use App\Models\ProductProperty;
use Illuminate\Database\Seeder;

class moveFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = [
            [
                "name_en" => "material",
                "name" => 'جنس',
                "table" => 'materials'
            ],
            
            [
                "name_en" => "design",
                "name" => "طرح",
                "table" => 'materials'
            ],

            [
                "name_en" => "sleeve",
                "name" => "آستین",
                "table" => 'materials'
            ],

            [
                "name_en" => "piece",
                "name" => "تعداد تکه",
                "table" => 'materials'
            ],

            [
                "name_en" => "set_type",
                "name" => "نوع ست",
                "table" => 'materials'
            ],

            [
                "name_en" => "maintenance",
                "name" => "نگهداری",
                "table" => 'materials'
            ],

            [
                "name_en" => "made_in",
                "name" => "ساخت",
                "table" => 'materials'
            ],

            [
                "name_en" => "origin",
                "name" => "کشور مبدا",
                "table" => 'materials'
            ],

            [
                "name_en" => "type",
                "name" => "نوع",
                "table" => 'materials'
            ],

            [
                "name_en" => "for_use",
                "name" => "استفاده برای",
                "table" => 'materials'
            ],

            [
                "name_en" => "collar",
                "name" => "یقه",
                "table" => 'materials'
            ],

            [
                "name_en" => "height",
                "name" => "قد",
                "table" => 'materials'
            ],

            [
                "name_en" => "physical_feature",
                "name" => "ویژگی های فیزیکی",
                "table" => 'materials'
            ],

            [
                "name_en" => "demension",
                "name" => "ابعاد",
                "table" => 'materials'
            ],

            [
                "name_en" => "crotch",
                "name" => "فاق",
                "table" => 'materials'
            ],

            [
                "name_en" => "close",
                "name" => "نوع بستن",
                "table" => 'materials'
            ],

            [
                "name_en" => "drop",
                "name" => "دراپ",
                "table" => 'materials'
            ],

            [
                "name_en" => "cumin_material",
                "name" => "جنس زیره",
                "table" => 'materials'
            ],

            [
                "name_en" => "close_shoe",
                "name" => "نوع بستن کفش",
                "table" => 'materials'
            ],

            [
                "name_en" => "typeـofـclothing",
                "name" => "نوع لباس",
                "table" => 'materials'
            ],

            [
                "name_en" => "outerـpocket",
                "name" => "جیب بیرونی",
                "table" => 'materials'
            ],

            [
                "name_en" => "inner_pocket",
                "name" => "جیب داخلی",
                "table" => 'materials'
            ],

            [
                "name_en" => "bag_handle",
                "name" => "دسته کیف",
                "table" => 'materials'
            ],

            [
                "name_en" => "shower_strap",
                "name" => "بند دوشی",
                "table" => 'materials'
            ],

            [
                "name_en" => "top_material",
                "name" => "جنس رویه",
                "table" => 'materials'
            ],

            [
                "name_en" => "heel",
                "name" => "پاشنه",
                "table" => 'materials'
            ],

            [
                "name_en" => "bag_model",
                "name" => "مدل کیف",
                "table" => 'materials'
            ],
          
        ];

        foreach($tables as $item){
            Field::create([
                'name' => $item['name'],
                'name_en' => $item['name_en']
            ]);
        }


        $CategoryProperty = CategoryProperty::all();

        // foreach($CategoryProperty as $cp){
        //     foreach($tables as $item){
        //         if($cp[$item['name_en']]){
        //             echo $cp['category_id']. '  ';
        //             if(!Field::where('name_en', $item['name_en'])->first()) continue;
        //             CategoryField::create([
        //                 'category_id' => $cp['category_id'],
        //                 'field_id' => Field::where('name_en', $item['name_en'])->first()['id']
        //             ]);
        //         }
        //     }
        // }

        // $data = ProductProperty::all();

        // foreach($data as $product){

        // }

        // foreach($tables as $item){

        // }
    }
}
