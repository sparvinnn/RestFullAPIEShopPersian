<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        Category::factory()->create()->each(function ($category){
//            Product::factory(Product::class, rand(6, 20))->make()->each(function ($product, $key) use($category){
//                $product->number = $key + 1;
//                $category->products()->save($product);
//            });
//        });

    }
}
