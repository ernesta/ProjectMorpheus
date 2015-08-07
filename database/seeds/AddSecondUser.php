<?php

use Illuminate\Database\Seeder;

class AddSecondUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Aurimas",
            'email' => 'mail@aurimas.eu',
            'password' => bcrypt('password'),
			'steamID' => '76561198045821972',
			 'steamUsername' => 'kamykolas'
        ]);
    }
}
