<?php

use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->truncate();
        DB::table('accounts')->insert([
            'name' => 'Demo',
            'email' => 'demo@rommoz.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
