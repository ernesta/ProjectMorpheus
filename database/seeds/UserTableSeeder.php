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
            'name' => "Ernesta",
            'email' => 'ernesta@ernes7a.lt',
            'password' => bcrypt('secret'),
			'steamID' => '76561198041112883',
			 'steamUsername' => 'ernes7a'
        ]);
    }
}
