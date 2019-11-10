<?php

use Illuminate\Database\Seeder;

class LoginServiceScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('login_service_scopes')->insert([
            [
                'loginType_id' => '2',
                'scope' => 'user_birthday',
                'display_name' => 'Access to your brith date',
                'field_name' => 'birthday'
            ],
            [
                'loginType_id' => '2',
                'scope' => 'user_location',
                'display_name' => 'Access to your current Location Information',
                'field_name' => 'location',
            ],
            [
                'loginType_id' => '2',
                'scope' => 'user_age_range',
                'display_name' => 'Access to your Age Range',
                'field_name' => 'age_range',
            ],
            [
                'loginType_id' => '2',
                'scope' => 'user_gender',
                'display_name' => 'Gender Information',
                'field_name' => 'gender',
            ],
            [
                'loginType_id' => '2',
                'scope' => 'user_hometown',
                'display_name' => 'Access to your Hometown Location Information',
                'field_name' => 'hometown',
            ],
        ]);
    }
}
