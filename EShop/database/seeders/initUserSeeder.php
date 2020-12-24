<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class initUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'name' => 'شعبه مرکزی',
            'phones' => '013-3333',
            'county' => 24,
            'city' => 10,
            'address' => 'خیابان مطهری',
            'postal_code' => '1742548745',
            'fax' => '013-5412',
        ]);

        DB::table('roles')->insert([
            'name' => 'سوپرادمین',
        ]);
        DB::table('roles')->insert([
            'name' => 'ادمین شعبه',
        ]);
        DB::table('roles')->insert([
            'name' => 'کانتر فروش',
        ]);
        DB::table('roles')->insert([
            'name' => 'حسابدار',
        ]);
        DB::table('roles')->insert([
            'name' => 'کاربر',
        ]);

        DB::table('users')->insert([
            'username' => 'superAdmin',
            'f_name' => 'Samira',
            'l_name' => 'Parvin',
            'mobile' => '09117158276',
            'national_code' => '001*******',
            'mobile_verified_at' => Carbon::now(),
            'county' => 24,
            'city' => 10,
            'address' => 'خیابان مطهری',
            'postal_code' => '1254785963',
            'email' => 'sparvinnn@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'branch_id' => 1
        ]);
    }
}
