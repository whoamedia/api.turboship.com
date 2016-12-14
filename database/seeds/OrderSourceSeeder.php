<?php

use Illuminate\Database\Seeder;

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
            ['id'    => 1,       'name'  => 'Internal',],
            ['id'    => 2,       'name'  => 'Shopify',],
        ];
    }
}
