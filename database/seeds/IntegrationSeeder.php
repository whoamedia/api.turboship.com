<?php

use Illuminate\Database\Seeder;
use \App\Utilities\IntegrationUtility;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Integration')->insert(
            $this->getIntegrations()
        );
    }


    private function getIntegrations ()
    {
        return [
            [
                'id'    => IntegrationUtility::SHOPIFY_ID,
                'name'  => 'Shopify'
            ],
            [
                'id'    => IntegrationUtility::EASYPOST_ID,
                'name'  => 'EasyPost'
            ],

        ];
    }
}
