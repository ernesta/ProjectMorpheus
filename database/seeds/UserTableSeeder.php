<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'name' => env('ADMIN_STEAM_USERNAME', "Admin"),
            'email' => env('ADMIN_EMAIL', "admin@admin.com"),
            'password' => bcrypt(env('ADMIN_PASSWORD', "secret")),
			'steamID' => env('ADMIN_STEAM_ID', "1001"),
			 'steamUsername' => env('ADMIN_STEAM_USERNAME', "Admin"),
        ]);
    }
}
