<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'name' => 'PinTaxi',
            'email' => 'admin@PinTaxi.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
