<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('name', 'User')->first();
        $role_author = Role::where('name', 'Author')->first();
        $role_admin = Role::where('name', 'Admin')->first();

        $user = new User();
        $user->name = 'Victor';
        $user->email = 'victor@gmail.com';
        $user->password = bcrypt('victor');
        $user->api_token = Str::random(100);
        $user->save();
        $user->roles()->attach($role_user);

        $user = new User();
        $user->name = 'Alex';
        $user->email = 'alex@gmail.com';
        $user->password = bcrypt('alex');
        $user->api_token = Str::random(100);
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Andy';
        $user->email = 'andy@gmail.com';
        $user->password = bcrypt('andy');
        $user->api_token = Str::random(100);
        $user->save();
        $user->roles()->attach($role_author);
    }
}
