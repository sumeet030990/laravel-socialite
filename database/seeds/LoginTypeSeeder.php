<?php

use Illuminate\Database\Seeder;

class LoginTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('login_types')->insert([
            ['name' => 'normal'],
            ['name' => 'facebook'],
            ['name' => 'google']
        ]);
    }
}
