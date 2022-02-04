<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('categories')->insert([
            "name_fa" => 'منوی راست',
            "name_en" => 'right menu',
            "slug" => 'right_menu',
        ]);

        DB::table('categories')->insert([
            "name_fa" => 'منوی چپ',
            "name_en" => 'left menu',
            "slug" => 'left_menu',
        ]);

        $level1_fa = [
            'زنانه',
            'مردانه',
            'بچگانه',
            'زیبایی و سلامت',
        ];
        $level1_en = [
            'women',
            'men',
            'kids',
            'health and care',
        ];
        $level1_slag = [
            'women',
            'men',
            'kids',
            'healthـandـcare',
        ];

        for($i=0;$i<4;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level1_fa[$i],
                "name_en" => $level1_en[$i],
                "slug" => $level1_slag[$i],
                "parent_id" => 1
            ]);



        $level2_fa = [
            'لباس',
            'کیف',
            'کفش',
            'اکسسوری',
            'ورزشی'
        ];
        $level2_en = [
            'dress',
            'bag',
            'shoe',
            'accessory',
            'sport'
        ];
        $level2_slag = [
            'woman_dress',
            'woman_bag',
            'woman_shoe',
            'woman_accessory',
            'woman_sport'
        ];

        for($i=0;$i<5;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level2_fa[$i],
                "name_en" => $level2_en[$i],
                "slug" => $level2_slag[$i],
                "parent_id" => 3
            ]);

        $level2_slag = [
            'man_dress',
            'man_bag',
            'man_shoe',
            'man_accessory',
            'man_sport'
        ];

        for($i=0;$i<5;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level2_fa[$i],
                "name_en" => $level2_en[$i],
                "slug" => $level2_slag[$i],
                "parent_id" => 4
            ]);

        $level2_slag = [
            'kid_dress',
            'kid_bag',
            'kid_shoe',
            'kid_accessory',
            'kid_sport'
        ];

        for($i=0;$i<5;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level2_fa[$i],
                "name_en" => $level2_en[$i],
                "slug" => $level2_slag[$i],
                "parent_id" => 5
            ]);
        
        $level2_fa = [
            'عطر و آدکلن',
            'آرایش و گریم',
            'مراقبت پوست',
            'آرایش و مراقبت مو',
            'بهداشت و مراقبت شخصی'
        ];
        $level2_en = [
            'Perfume and cologne',
            'Makeup',
            'Skin care',
            'Hairdressing and hair care',
            'Personal health and care'
        ];
        $level2_slag = [
            'perfumeـandـcologne',
            'makeup',
            'skinـcare',
            'hairdressingـandـhairـcare',
            'personalـhealthـandـcare'
        ];

        for($i=0;$i<5;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level2_fa[$i],
                "name_en" => $level2_en[$i],
                "slug" => $level2_slag[$i],
                "parent_id" => 6
            ]);

        
        $level3_fa = [
            'سویشرت و هودی',
            'ژاکت و پلیور',
            'تی شرت و پلوشرت',
            'لباس راحتی و خواب',
            'مانتوُ پانچ و رویه',
            'شومیز',
            'بلوز',
            'تاپ',
            'تونیک',
            'شلوار و سرهمی',
            'جین',
            'دامن',
            'لباس زیر',
            'جوراب، ساق و جوراب شلواری',
            'شلوارک',
            'لباس بارداری',
            'پیراهن و لباس مجلسی',
            'کت و جلیقه',
            'لگینگ',
            'بادی',
            'پالتو، بارانی و کاپشن'
        ];
        $level3_en = [
            'Sweater and hoodie',
             'Sweater and sweater',
             'T-shirts and sweatshirts',
             'Comfort and sleepwear',
             'Punch mantle and procedure',
             'Paperback',
             'Blouse',
             'Top',
             'Tonic',
             'Pants and trousers',
             'Jean',
             'Skirt',
             'under wear',
             'Socks, stockings and tights',
             'Shorts',
             'Pregnancy Clothes',
             'Shirts and dresses',
             'Jackets and vests',
             'Laging',
             'Windy',
             'Coats, raincoats and jackets'
        ];
        $level3_slag = [
            'Sweater_and_hoodie',
             'Sweater_and_sweater',
             'T-shirts_and_sweatshirts',
             'Comfort_and_sleepwear',
             'Punch_mantle_and_procedure',
             'Paperback',
             'Blouse',
             'Top',
             'Tonic',
             'Pants_and_trousers',
             'Jean',
             'Skirt',
             'under_wear',
             'Socks_stockings_tights',
             'Shorts',
             'Pregnancy_Clothes',
             'Shirts_dresses',
             'Jackets_vests',
             'Laging',
             'Windy',
             'Coats_raincoats_jackets'
        ];

        for($i=0;$i<20;$i++)
            DB::table('categories')->insert([
                "name_fa" => $level3_fa[$i],
                "name_en" => $level3_en[$i],
                "slug" => $level3_slag[$i],
                "parent_id" => 7
            ]);
        
    

    }
}
