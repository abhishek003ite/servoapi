<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = 
        [
            [
                'is_customer' => 'no', 
                'is_partner' => 'no', 
                'is_admin' => 'yes', 
                'first_name' => 'ServoQuick', 
                'email'=> 'marketing@servoquick.com',
                'password' => Hash::make('123456'),
                'time_zone_id' => 1,
            ],
        ];        

        \App\Models\User::insert($admin);

    }
}
