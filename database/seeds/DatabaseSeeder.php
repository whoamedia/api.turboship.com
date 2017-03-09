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
         * ACL
         */
        $this->call(PermissionSeeder::class);


        /**
         * Locations
         */
        $this->call(ContinentSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(SubdivisionTypeSeeder::class);
        $this->call(SubdivisionSeeder::class);


        /**
         * Shipments
         */
        $this->call(CarrierSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ShipmentAlgorithmSeeder::class);
        $this->call(ShipmentStatusSeeder::class);
        $this->call(ShippingContainerTypeSeeder::class);
        $this->call(ShippingStationTypeSeeder::class);
        $this->call(PrinterTypeSeeder::class);


        /**
         * Integrations
         */
        $this->call(ShopifyShoppingCartIntegrationSeeder::class);
        $this->call(EasyPostShippingIntegrationSeeder::class);


        /**
         * Orders
         */
        $this->call(SourceSeeder::class);
        $this->call(OrderStatusSeeder::class);


        /**
         * Organizations
         */
        $this->call(WhoaMediaSeeder::class);
        $this->call(NicheLogisticsSeeder::class);
    }
}
