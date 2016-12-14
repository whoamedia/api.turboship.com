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
        /**
         * Locations
         */
        $this->call(ContinentSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(SubdivisionTypeSeeder::class);
        $this->call(SubdivisionSeeder::class);
        $this->call(SubdivisionAltNameSeeder::class);
        $this->call(PostalDistrictSeeder::class);
        $this->call(PostalDistrictSubdivisionSeeder::class);


        /**
         * Orders
         */
        $this->call(OrderSourceSeeder::class);
        $this->call(OrderStatusSeeder::class);


        /**
         * Organizations
         */
        $this->call(WhoaMediaSeeder::class);
        $this->call(NicheLogisticsSeeder::class);
    }
}
