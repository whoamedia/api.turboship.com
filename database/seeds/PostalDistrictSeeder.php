<?php

use Illuminate\Database\Seeder;
use SoapBox\Formatter\Formatter;

class PostalDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }

        $filePath = database_path() . '/seeds/PostalDistrictFiles/PostalDistricts.csv';
        $string = file_get_contents($filePath);
        $string = str_replace("\n", "\r", $string);
        $formatter = Formatter::make($string, Formatter::CSV);
        $array = $formatter->toArray();

        DB::table('PostalDistrict')->insert(
            $array
        );
    }
}
