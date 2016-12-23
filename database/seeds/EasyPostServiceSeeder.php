<?php


use Illuminate\Database\Seeder;
use App\Utilities\IntegrationUtility;
use App\Utilities\ServiceUtility;


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


    /**
     * @return array
     */
    private function getUSPSServices ()
    {
        return [
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_FIRST_CLASS,
                'symbol'        => 'First',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_PRIORITY_MAIL,
                'symbol'        => 'Priority',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_PRIORITY_MAIL_EXPRESS,
                'symbol'        => 'Express',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_PARCEL_SELECT,
                'symbol'        => 'ParcelSelect',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_LIBRARY_MAIL,
                'symbol'        => 'LibraryMail',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_MEDIA_MAIL,
                'symbol'        => 'MediaMail',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_CRITICAL_MAIL,
                'symbol'        => 'CriticalMail',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_FIRST_CLASS_INTERNATIONAL,
                'symbol'        => 'FirstClassMailInternational',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL,
                'symbol'        => 'FirstClassPackageInternationalService',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_PRIORITY_MAIL_INTERNATIONAL,
                'symbol'        => 'PriorityMailInternational',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::USPS_EXPRESS_MAIL_INTERNATIONAL,
                'symbol'        => 'ExpressMailInternational',
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
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_GROUND,
                'symbol'        => 'Ground',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_UPS_STANDARD,
                'symbol'        => 'UPSStandard',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_UPS_SAVER,
                'symbol'        => 'UPSSaver',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_EXPRESS,
                'symbol'        => 'Express',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_EXPRESS_PLUS,
                'symbol'        => 'ExpressPlus',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_EXPEDITED,
                'symbol'        => 'Expedited',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_NEXT_DAY_AIR,
                'symbol'        => 'NextDayAir',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_NEXT_DAY_AIR_SAVER,
                'symbol'        => 'NextDayAirSaver',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_NEXT_DAY_AIR_EARLY_AM,
                'symbol'        => 'NextDayAirEarlyAM',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_2ND_DAY_AIR,
                'symbol'        => '2ndDayAir',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_2ND_DAY_AIR_AM,
                'symbol'        => '2ndDayAirAM',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_3_DAY_SELECT,
                'symbol'        => '3DaySelect',
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
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_MAIL_INNOVATIONS_FIRST,
                'symbol'        => 'First',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY,
                'symbol'        => 'Priority',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS,
                'symbol'        => 'ExpeditedMailInnovations',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS,
                'symbol'        => 'PriorityMailInnovations',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS,
                'symbol'        => 'EconomyMailInnovations',
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
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC,
                'symbol'        => 'BPMExpeditedDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC,
                'symbol'        => 'BPMGroundDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC,
                'symbol'        => 'FlatsExpeditedDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC,
                'symbol'        => 'FlatsGroundDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC,
                'symbol'        => 'MediaMailGroundDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC,
                'symbol'        => 'ParcelPlusExpeditedDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC,
                'symbol'        => 'ParcelPlusGroundDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC,
                'symbol'        => 'ParcelsExpeditedDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC,
                'symbol'        => 'ParcelsGroundDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC,
                'symbol'        => 'MarketingParcelExpeditedDomestic',
            ],
            [
                'integrationId' => IntegrationUtility::EASYPOST_ID,
                'serviceId'     => ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC,
                'symbol'        => 'MarketingParcelGroundDomestic',
            ],
        ];
    }
}
