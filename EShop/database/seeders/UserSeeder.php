<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     "mobile" => '09117158277',
        //     "password" => Hash::make('12345678')
        // ]);

        DB::table('users')->insert([
            'username' => 'zarjahan',
            'f_name' => 'jahani',
            'l_name' => 'jahani',
            'mobile' => 'zarjahan',
            'national_code' => '001*******',
            'mobile_verified_at' => Carbon::now(),
            'county' => 24,
            'city' => 10,
            'address' => 'خیابان مطهری',
            'postal_code' => '1254785963',
            'email' => 'zarjahan@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('j1a2h3a4n5'),
            'branch_id' => 1
        ]);
    }
}
