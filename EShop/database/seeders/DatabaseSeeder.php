<?php

namespace Database\Seeders;

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
        $this->call(initUserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(SizeSeeder::class);
    }
}
