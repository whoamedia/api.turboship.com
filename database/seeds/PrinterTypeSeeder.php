<?php

use Illuminate\Database\Seeder;
use App\Utilities\PrinterTypeUtility;

class PrinterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('PrinterType')->insert(
            $this->getPrinterTypes()
        );
    }


    private function getPrinterTypes ()
    {
        return [
            [
                'id'    => PrinterTypeUtility::CUPS_SERVER,
                'name'  => 'CUPS Server'
            ],

        ];
    }
}
