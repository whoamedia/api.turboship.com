<?php

use Illuminate\Database\Seeder;


/**
 * @see https://www.easypost.com/service-levels-and-parcels.html
 * Class EasyPostServiceSeeder
 */
class EasyPostServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }


    public function getEasyPostServices ()
    {
        return [
            //  USPS
            ['carrier' => 'USPS', 'name'     => 'First'],
            ['carrier' => 'USPS', 'name'     => 'Priority'],
            ['carrier' => 'USPS', 'name'     => 'Express'],
            ['carrier' => 'USPS', 'name'     => 'ParcelSelect'],
            ['carrier' => 'USPS', 'name'     => 'LibraryMail'],
            ['carrier' => 'USPS', 'name'     => 'MediaMail'],
            ['carrier' => 'USPS', 'name'     => 'CriticalMail'],
            ['carrier' => 'USPS', 'name'     => 'FirstClassMailInternational'],
            ['carrier' => 'USPS', 'name'     => 'FirstClassPackageInternationalService'],
            ['carrier' => 'USPS', 'name'     => 'PriorityMailInternational'],
            ['carrier' => 'USPS', 'name'     => 'ExpressMailInternational'],


            //  UPS
            ['carrier' => 'UPS', 'name'     => 'Ground'],
            ['carrier' => 'UPS', 'name'     => 'UPSStandard'],
            ['carrier' => 'UPS', 'name'     => 'UPSSaver'],
            ['carrier' => 'UPS', 'name'     => 'Express'],
            ['carrier' => 'UPS', 'name'     => 'ExpressPlus'],
            ['carrier' => 'UPS', 'name'     => 'Expedited'],
            ['carrier' => 'UPS', 'name'     => 'NextDayAir'],
            ['carrier' => 'UPS', 'name'     => 'NextDayAirSaver'],
            ['carrier' => 'UPS', 'name'     => 'NextDayAirEarlyAM'],
            ['carrier' => 'UPS', 'name'     => '2ndDayAir'],
            ['carrier' => 'UPS', 'name'     => '2ndDayAirAM'],
            ['carrier' => 'UPS', 'name'     => '3DaySelect'],


            //  UPS Mail Innovations
            ['carrier' => 'UPS Mail Innovations', 'name'    => 'First'],
            ['carrier' => 'UPS Mail Innovations', 'name'    => 'Priority'],
            ['carrier' => 'UPS Mail Innovations', 'name'    => 'ExpeditedMailInnovations'],
            ['carrier' => 'UPS Mail Innovations', 'name'    => 'PriorityMailInnovations'],
            ['carrier' => 'UPS Mail Innovations', 'name'    => 'EconomyMailInnovations'],
        ];
    }
}
