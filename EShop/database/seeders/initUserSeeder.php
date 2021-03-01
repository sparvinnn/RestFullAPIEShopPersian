<?php

namespace Database\Seeders;

use App\Models\User;
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
            'name' => 'SuperAdmin',
            'guard_name' => 'web'
        ]);
        DB::table('roles')->insert([
            'name' => 'BranchAdmin',
            'guard_name' => 'web'
        ]);
        DB::table('roles')->insert([
            'name' => 'SellCounter',
            'guard_name' => 'web'
        ]);
        DB::table('roles')->insert([
            'name' => 'Accountant',
            'guard_name' => 'web'
        ]);
        DB::table('roles')->insert([
            'name' => 'EndUser',
            'guard_name' => 'web'
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
            'branch_id' => 1
        ]);

        DB::table('users')->insert([
            'username' => 'PublicUser',
            'f_name' => 'Mahdi',
            'l_name' => 'Moradi',
            'mobile' => '09125146354',
            'password' => Hash::make('123456'),
        ]);

        DB::table('permissions')->insert([
            'name' => 'SuperAdmin',
            'guard_name' => 'web'
        ]);

        DB::table('permissions')->insert([
            'name' => 'Finance',
            'guard_name' => 'web'
        ]);

        DB::table('permissions')->insert([
            'name' => 'PublicUser',
            'guard_name' => 'web'
        ]);

        DB::table('role_has_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1
        ]);

        User::find(1)->assignRole('SuperAdmin');
        User::find(2)->assignRole('EndUser');

//        DB::table('model_has_roles')->insert([
//            'model_id' => 1,
//            'model_type' => 'App\User',
//            'role_id' => 1
//        ]);
//
//        DB::table('model_has_roles')->insert([
//            'model_id' => 2,
//            'model_type' => 'App\User',
//            'role_id' => 5
//        ]);
    }
}
