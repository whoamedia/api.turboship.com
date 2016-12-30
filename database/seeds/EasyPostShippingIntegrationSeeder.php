<?php


use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ShippingApiIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\ShippingApiCarrier;
use App\Utilities\CarrierUtility;
use App\Utilities\ServiceUtility;
use App\Models\Integrations\ShippingApiService;


/**
 * @see https://www.easypost.com/service-levels-and-parcels.html
 * Class EasyPostShippingApiIntegrationSeeder
 */
class EasyPostShippingIntegrationSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\Shipments\CarrierRepository
     */
    private $carrierRepo;

    /**
     * @var \App\Repositories\Doctrine\Shipments\ServiceRepository
     */
    private $serviceRepo;

    /**
     * @var \App\Repositories\Doctrine\Integrations\ShippingApiIntegrationRepository
     */
    private $shippingIntegrationRepo;

    /**
     * @var ShippingApiIntegration
     */
    private $easyPostShippingApiIntegration;

    public function run()
    {
        $this->carrierRepo                  = EntityManager::getRepository('App\Models\Shipments\Carrier');
        $this->serviceRepo                  = EntityManager::getRepository('App\Models\Shipments\Service');
        $this->shippingIntegrationRepo      = EntityManager::getRepository('App\Models\Integrations\ShippingApiIntegration');

        $this->easyPostShippingApiIntegration  = new ShippingApiIntegration();
        $this->easyPostShippingApiIntegration->setName('EasyPost');


        $easyPostApiKey                     = new IntegrationCredential();
        $easyPostApiKey->setName('apiKey');
        $easyPostApiKey->setIsRequired(true);
        $this->easyPostShippingApiIntegration->addIntegrationCredential($easyPostApiKey);

        $this->easyPostUSPS();
        $this->easyPostUPS();
        $this->easyPostUPSMailInnovations();
        $this->easyPostDHLGlobalMail();
        $this->easyPostFedEx();

        $this->shippingIntegrationRepo->saveAndCommit($this->easyPostShippingApiIntegration);
    }


    private function easyPostUSPS ()
    {
        /**
         * USPS
         */
        $easyPostUSPS                       = new ShippingApiCarrier();
        $easyPostUSPS->setName('USPS');
        $easyPostUSPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::USPS));

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PARCEL_SELECT),
                    'name'          => 'ParcelSelect',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_LIBRARY_MAIL),
                    'name'          => 'LibraryMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_MEDIA_MAIL),
                    'name'          => 'MediaMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_CRITICAL_MAIL),
                    'name'          => 'CriticalMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_INTERNATIONAL),
                    'name'          => 'FirstClassMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL),
                    'name'          => 'FirstClassPackageInternationalService',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_INTERNATIONAL),
                    'name'          => 'PriorityMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_EXPRESS_MAIL_INTERNATIONAL),
                    'name'          => 'ExpressMailInternational',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiCarrier($easyPostUSPS);
    }

    private function easyPostUPS ()
    {
        $easyPostUPS                        = new ShippingApiCarrier();
        $easyPostUPS->setName('UPS');
        $easyPostUPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS));

        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_GROUND),
                    'name'          => 'Ground',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_STANDARD),
                    'name'          => 'UPSStandard',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_SAVER),
                    'name'          => 'UPSSaver',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS_PLUS),
                    'name'          => 'ExpressPlus',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPEDITED),
                    'name'          => 'Expedited',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR),
                    'name'          => 'NextDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_SAVER),
                    'name'          => 'NextDayAirSaver',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_EARLY_AM),
                    'name'          => 'NextDayAirEarlyAM',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR),
                    'name'          => '2ndDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR_AM),
                    'name'          => '2ndDayAirAM',
                ]
            )
        );
        $easyPostUPS->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_3_DAY_SELECT),
                    'name'          => '3DaySelect',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiCarrier($easyPostUPS);
    }

    private function easyPostUPSMailInnovations ()
    {
        $easyPostUPSMailInnovations = new ShippingApiCarrier();
        $easyPostUPSMailInnovations->setName('UPS Mail Innovations');
        $easyPostUPSMailInnovations->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS_MAIL_INNOVATIONS));

        $easyPostUPSMailInnovations->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_FIRST),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS),
                    'name'          => 'ExpeditedMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS),
                    'name'          => 'PriorityMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS),
                    'name'          => 'EconomyMailInnovations',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiCarrier($easyPostUPSMailInnovations);
    }

    private function easyPostDHLGlobalMail ()
    {
        $easyPostDHLGlobalMail      = new ShippingApiCarrier();
        $easyPostDHLGlobalMail->setName('DHL Global Mail');
        $easyPostDHLGlobalMail->setCarrier($this->carrierRepo->getOneById(CarrierUtility::DHL_GLOBAL_MAIL));

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC),
                    'name'          => 'BPMExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC),
                    'name'          => 'BPMGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC),
                    'name'          => 'FlatsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC),
                    'name'          => 'FlatsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC),
                    'name'          => 'MediaMailGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelPlusExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC),
                    'name'          => 'ParcelPlusGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'ParcelsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'MarketingParcelExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'MarketingParcelGroundDomestic',
                ]
            )
        );


        $this->easyPostShippingApiIntegration->addShippingApiCarrier($easyPostDHLGlobalMail);
    }

    private function easyPostFedEx ()
    {
        $easyPostFedEx              = new ShippingApiCarrier();
        $easyPostFedEx->setName('FedEx');
        $easyPostFedEx->setCarrier($this->carrierRepo->getOneById(CarrierUtility::FEDEX));

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND),
                    'name'          => 'FEDEX_GROUND',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY),
                    'name'          => 'FEDEX_2_DAY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY_AM),
                    'name'          => 'FEDEX_2_DAY_AM',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_EXPRESS_SAVER),
                    'name'          => 'FEDEX_EXPRESS_SAVER',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_STANDARD_OVERNIGHT),
                    'name'          => 'STANDARD_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_FIRST_OVERNIGHT),
                    'name'          => 'FIRST_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_PRIORITY_OVERNIGHT),
                    'name'          => 'PRIORITY_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_ECONOMY),
                    'name'          => 'INTERNATIONAL_ECONOMY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_FIRST),
                    'name'          => 'INTERNATIONAL_FIRST',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_PRIORITY),
                    'name'          => 'INTERNATIONAL_PRIORITY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND_HOME_DELIVERY),
                    'name'          => 'GROUND_HOME_DELIVERY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiService(
            new ShippingApiService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_SMART_POST),
                    'name'          => 'SMART_POST',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiCarrier($easyPostFedEx);
    }

}
