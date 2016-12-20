<?php

use Illuminate\Database\Seeder;
use App\Utilities\CarrierUtility;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Service')->insert(
            $this->getUSPSServices()
        );

        DB::table('Service')->insert(
            $this->getUPSServices()
        );

        DB::table('Service')->insert(
            $this->getDHLServices()
        );
    }

    /**
     * @return array
     */
    private function getUSPSServices ()
    {
        return [
            [
                'carrierId'     => CarrierUtility::USPS_ID,
                'name'          => 'First Class',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::USPS_ID,
                'name'          => 'Priority Mail',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::USPS_ID,
                'name'          => 'First Class International',
                'isDomestic'    => false,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getUPSServices ()
    {
        return [
            [
                'carrierId'     => CarrierUtility::UPS_ID,
                'name'          => '2nd Day Air',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::UPS_ID,
                'name'          => 'SurePost Pound Plus',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::UPS_ID,
                'name'          => 'SurePost Light',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::UPS_ID,
                'name'          => 'Ground',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::UPS_ID,
                'name'          => 'Next Day',
                'isDomestic'    => true,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getDHLServices ()
    {
        return [
            [
                'carrierId'     => CarrierUtility::DHL_ID,
                'name'          => 'Parcel Expedited',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::DHL_ID,
                'name'          => 'Parcel Plus Ground',
                'isDomestic'    => true,
            ],
            [
                'carrierId'     => CarrierUtility::DHL_ID,
                'name'          => 'International',
                'isDomestic'    => false,
            ],
            [
                'carrierId'     => CarrierUtility::DHL_ID,
                'name'          => 'Parcel Plus Expedited',
                'isDomestic'    => true,
            ],
        ];
    }
}
