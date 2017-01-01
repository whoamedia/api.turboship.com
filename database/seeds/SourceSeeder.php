<?php

use Illuminate\Database\Seeder;
use \App\Utilities\SourceUtility;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Source')->insert(
            $this->getSources()
        );
    }

    private function getSources ()
    {
        return [
            [
                'id'    => SourceUtility::INTERNAL_ID,
                'name'  => 'Internal',
            ],
            [
                'id'    => SourceUtility::SHOPIFY_ID,
                'name'  => 'Shopify',
            ],
        ];
    }
}
