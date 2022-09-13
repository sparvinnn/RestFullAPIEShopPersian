<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
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
            ],
            
            [
                "name_en" => "design",
                "name" => "طرح",
            ],

            [
                "name_en" => "sleeve",
                "name" => "آستین",
            ],

            [
                "name_en" => "piece",
                "name" => "تعداد تکه",
            ],

            [
                "name_en" => "set_type",
                "name" => "نوع ست",
            ],

            [
                "name_en" => "maintenance",
                "name" => "نگهداری",
            ],

            [
                "name_en" => "made_in",
                "name" => "ساخت",
            ],

            [
                "name_en" => "origin",
                "name" => "کشور مبدا",
            ],

            [
                "name_en" => "type",
                "name" => "نوع",
            ],

            [
                "name_en" => "for_use",
                "name" => "استفاده برای",
            ],

            [
                "name_en" => "collar",
                "name" => "یقه",
            ],

            [
                "name_en" => "height",
                "name" => "قد",
            ],

            [
                "name_en" => "physical_feature",
                "name" => "ویژگی های فیزیکی",
            ],

            [
                "name_en" => "demension",
                "name" => "ابعاد",
            ],

            [
                "name_en" => "crotch",
                "name" => "فاق",
            ],

            [
                "name_en" => "close",
                "name" => "نوع بستن",
            ],

            [
                "name_en" => "drop",
                "name" => "دراپ",
            ],

            [
                "name_en" => "cumin_material",
                "name" => "جنس زیره",
            ],

            [
                "name_en" => "close_shoe",
                "name" => "نوع بستن کفش",
            ],

            [
                "name_en" => "typeـofـclothing",
                "name" => "نوع لباس",
            ],

            [
                "name_en" => "outerـpocket",
                "name" => "جیب بیرونی",
            ],

            [
                "name_en" => "inner_pocket",
                "name" => "جیب داخلی",
            ],

            [
                "name_en" => "bag_handle",
                "name" => "دسته کیف",
            ],

            [
                "name_en" => "shower_strap",
                "name" => "بند دوشی",
            ],

            [
                "name_en" => "top_material",
                "name" => "جنس رویه",
            ],

            [
                "name_en" => "heel",
                "name" => "پاشنه",
            ],

            [
                "name_en" => "bag_model",
                "name" => "مدل کیف",
            ],
          
        ];

        foreach($tables as $item){
            Field::create([
                'name' => $item['name'],
                'name_en' => $item['name_en']
            ]);
        }
    }
}
