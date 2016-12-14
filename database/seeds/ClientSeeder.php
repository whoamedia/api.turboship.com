<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->whoaMedia();
    }


    private function whoaMedia ()
    {
        DB::table('Client')->insert(
            [
                'name'              => 'Whoa Media',
                'organizationId'    => 1
            ]
        );
    }

}
