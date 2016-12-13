<?php

use Illuminate\Database\Seeder;


class OrganizationSeeder extends Seeder
{

    public function run()
    {
        $this->createWhoaMedia();
        $this->createNicheLogistics();
    }
    
    private function createWhoaMedia()
    {
        DB::table('Organization')->insert(
            [
                'id'                =>  1,
                'name'              => 'Whoa Media',
            ]
        );
    }

    private function createNicheLogistics()
    {
        DB::table('Organization')->insert(
            [
                'id'                =>  2,
                'name'              => 'Niche Logistics',
            ]
        );
    }
}
