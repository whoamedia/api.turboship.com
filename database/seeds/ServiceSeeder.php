<?php

use Illuminate\Database\Seeder;
use App\Utilities\CarrierUtility;
use App\Utilities\ServiceUtility;

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
            $this->getUPSMailInnovationsServices()
        );

        DB::table('Service')->insert(
            $this->getDHLGlobalMailServices()
        );

        DB::table('Service')->insert(
            $this->getFedExServices()
        );
    }

    /**
     * @return array
     */
    private function getUSPSServices ()
    {
        return [
            [
                'id'            => ServiceUtility::USPS_FIRST_CLASS,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'First Class',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_PRIORITY_MAIL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Priority Mail',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_PRIORITY_MAIL_EXPRESS,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Priority Mail Express',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_PARCEL_SELECT,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Parcel Select',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_LIBRARY_MAIL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Library Mail',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_MEDIA_MAIL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Media Mail',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_CRITICAL_MAIL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Critical Mail',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::USPS_FIRST_CLASS_INTERNATIONAL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'First Class Mail International',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'First Class Package International',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::USPS_PRIORITY_MAIL_INTERNATIONAL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Priority Mail International',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::USPS_EXPRESS_MAIL_INTERNATIONAL,
                'carrierId'     => CarrierUtility::USPS,
                'name'          => 'Express Mail International',
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
                'id'            => ServiceUtility::UPS_GROUND,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_UPS_STANDARD,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'UPS Standard',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_UPS_SAVER,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'UPS Saver',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_EXPRESS,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Express',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_EXPRESS_PLUS,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Express Plus',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_EXPEDITED,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_NEXT_DAY_AIR,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Next Day Air',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_NEXT_DAY_AIR_SAVER,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Next Day Air Saver',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_NEXT_DAY_AIR_EARLY_AM,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => 'Next Day Air Early AM',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_2ND_DAY_AIR,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => '2nd Day Air',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_2ND_DAY_AIR_AM,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => '2nd Day Air AM',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_3_DAY_SELECT,
                'carrierId'     => CarrierUtility::UPS,
                'name'          => '3 Day Select',
                'isDomestic'    => true,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getUPSMailInnovationsServices ()
    {
        return [
            [
                'id'            => ServiceUtility::UPS_MAIL_INNOVATIONS_FIRST,
                'carrierId'     => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'          => 'First',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY,
                'carrierId'     => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'          => 'Priority',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS,
                'carrierId'     => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'          => 'MI Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS,
                'carrierId'     => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'          => 'MI Priority',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS,
                'carrierId'     => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'          => 'MI Economy',
                'isDomestic'    => true,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getDHLGlobalMailServices ()
    {
        return [
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'BPM Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'BPM Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Flats Expedited',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Flats Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Media Mail Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Parcel Plus Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Parcels Plus Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Parcels Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Parcel Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Marketing Parcel Expedited',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC,
                'carrierId'     => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'          => 'Marketing Parcel Ground',
                'isDomestic'    => true,
            ],
        ];
    }

    private function getFedExServices ()
    {
        return [
            [
                'id'            => ServiceUtility::FEDEX_GROUND,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Ground',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_2_DAY,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => '2 Day',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_2_DAY_AM,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => '2 Day AM',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::FEDEX_EXPRESS_SAVER,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Express Saver',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_STANDARD_OVERNIGHT,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Standard Overnight',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_FIRST_OVERNIGHT,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'First Overnight',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_PRIORITY_OVERNIGHT,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Priority Overnight',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_INTERNATIONAL_ECONOMY,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'International Economy',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::FEDEX_INTERNATIONAL_FIRST,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'International First',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::FEDEX_INTERNATIONAL_PRIORITY,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'International Priority',
                'isDomestic'    => false,
            ],
            [
                'id'            => ServiceUtility::FEDEX_GROUND_HOME_DELIVERY,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Ground Home Delivery',
                'isDomestic'    => true,
            ],
            [
                'id'            => ServiceUtility::FEDEX_SMART_POST,
                'carrierId'     => CarrierUtility::FEDEX,
                'name'          => 'Smart Post',
                'isDomestic'    => true,
            ],
        ];
    }

}
