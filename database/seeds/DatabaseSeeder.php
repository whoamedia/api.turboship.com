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
        $this->call(ContinentSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(SubdivisionTypeSeeder::class);
        $this->call(SubdivisionSeeder::class);
        $this->call(SubdivisionAltNameSeeder::class);
        $this->call(PostalDistrictSeeder::class);
        $this->call(PostalDistrictSubdivisionSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(ClientSeeder::class);
    }
}
