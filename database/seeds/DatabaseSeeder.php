<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ServicesSeeder::class);
        $this->call(TimeZoneSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AdminSeeder::class);
    }
}
