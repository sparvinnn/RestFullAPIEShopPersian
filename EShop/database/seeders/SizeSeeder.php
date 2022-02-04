<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $size = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        foreach($size as $item)
            Size::create([
                'name' => $item
            ]);

        for ($i=5; $i<100; $i++)
            Size::create([
                'name' => $item
            ]);
    }
}
