<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('OrderStatus')->insert(
            $this->getSources()
        );
    }

    private function getSources ()
    {
        return [
            /**
             * Error Statuses
             */
            ['id'    => 100,       'name'  => 'Back Ordered',           'isError' => true],


            /**
             * Fulfillment Operations
             */
            ['id'    => 200,       'name'  => 'Pending Fulfillment',    'isError' => false],
            ['id'    => 201,       'name'  => 'Pulled',                 'isError' => false],
            ['id'    => 202,       'name'  => 'Picked',                 'isError' => false],


            /**
             * Shipping Operations
             */
            ['id'    => 300,       'name'  => 'Partially Shipped',      'isError' => false],
            ['id'    => 301,       'name'  => 'Fully Shipped',          'isError' => false],
        ];
    }
}
