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

    }
}
