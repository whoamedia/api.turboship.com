<?php

use Illuminate\Database\Seeder;
use \App\Utilities\CRMSourceUtility;

class CRMSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('CRMSource')->insert(
            $this->getSources()
        );
    }

    private function getSources ()
    {
        return [
            [
                'id'    => CRMSourceUtility::INTERNAL_ID,
                'name'  => 'Internal',
            ],
            [
                'id'    => CRMSourceUtility::SHOPIFY_ID,
                'name'  => 'Shopify',
            ],
        ];
    }
}
