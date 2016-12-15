<?php

use Illuminate\Database\Seeder;
use \App\Utilities\OrderSourceUtility;

class OrderSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('OrderSource')->insert(
            $this->getSources()
        );
    }

    private function getSources ()
    {
        return [
            [
                'id'    => OrderSourceUtility::INTERNAL_ID,
                'name'  => 'Internal',
            ],
            [
                'id'    => OrderSourceUtility::SHOPIFY_ID,
                'name'  => 'Shopify',
            ],
        ];
    }
}
