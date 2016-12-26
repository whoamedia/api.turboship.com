<?php


use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ShippingApiIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\ShippingApiIntegrationCarrier;
use App\Utilities\CarrierUtility;
use App\Utilities\ServiceUtility;
use App\Models\Integrations\ShippingApiIntegrationService;


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
        $easyPostUSPS                       = new ShippingApiIntegrationCarrier();
        $easyPostUSPS->setName('USPS');
        $easyPostUSPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::USPS));

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PARCEL_SELECT),
                    'name'          => 'ParcelSelect',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_LIBRARY_MAIL),
                    'name'          => 'LibraryMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_MEDIA_MAIL),
                    'name'          => 'MediaMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_CRITICAL_MAIL),
                    'name'          => 'CriticalMail',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_INTERNATIONAL),
                    'name'          => 'FirstClassMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL),
                    'name'          => 'FirstClassPackageInternationalService',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_INTERNATIONAL),
                    'name'          => 'PriorityMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_EXPRESS_MAIL_INTERNATIONAL),
                    'name'          => 'ExpressMailInternational',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiIntegrationCarrier($easyPostUSPS);
    }

    private function easyPostUPS ()
    {
        $easyPostUPS                        = new ShippingApiIntegrationCarrier();
        $easyPostUPS->setName('UPS');
        $easyPostUPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS));

        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_GROUND),
                    'name'          => 'Ground',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_STANDARD),
                    'name'          => 'UPSStandard',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_SAVER),
                    'name'          => 'UPSSaver',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS_PLUS),
                    'name'          => 'ExpressPlus',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPEDITED),
                    'name'          => 'Expedited',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR),
                    'name'          => 'NextDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_SAVER),
                    'name'          => 'NextDayAirSaver',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_EARLY_AM),
                    'name'          => 'NextDayAirEarlyAM',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR),
                    'name'          => '2ndDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR_AM),
                    'name'          => '2ndDayAirAM',
                ]
            )
        );
        $easyPostUPS->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_3_DAY_SELECT),
                    'name'          => '3DaySelect',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiIntegrationCarrier($easyPostUPS);
    }

    private function easyPostUPSMailInnovations ()
    {
        $easyPostUPSMailInnovations = new ShippingApiIntegrationCarrier();
        $easyPostUPSMailInnovations->setName('UPS Mail Innovations');
        $easyPostUPSMailInnovations->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS_MAIL_INNOVATIONS));

        $easyPostUPSMailInnovations->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_FIRST),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS),
                    'name'          => 'ExpeditedMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS),
                    'name'          => 'PriorityMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS),
                    'name'          => 'EconomyMailInnovations',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiIntegrationCarrier($easyPostUPSMailInnovations);
    }

    private function easyPostDHLGlobalMail ()
    {
        $easyPostDHLGlobalMail      = new ShippingApiIntegrationCarrier();
        $easyPostDHLGlobalMail->setName('DHL Global Mail');
        $easyPostDHLGlobalMail->setCarrier($this->carrierRepo->getOneById(CarrierUtility::DHL_GLOBAL_MAIL));

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC),
                    'name'          => 'BPMExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC),
                    'name'          => 'BPMGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC),
                    'name'          => 'FlatsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC),
                    'name'          => 'FlatsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC),
                    'name'          => 'MediaMailGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelPlusExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC),
                    'name'          => 'ParcelPlusGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'ParcelsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'MarketingParcelExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'MarketingParcelGroundDomestic',
                ]
            )
        );


        $this->easyPostShippingApiIntegration->addShippingApiIntegrationCarrier($easyPostDHLGlobalMail);
    }

    private function easyPostFedEx ()
    {
        $easyPostFedEx              = new ShippingApiIntegrationCarrier();
        $easyPostFedEx->setName('FedEx');
        $easyPostFedEx->setCarrier($this->carrierRepo->getOneById(CarrierUtility::FEDEX));

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND),
                    'name'          => 'FEDEX_GROUND',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY),
                    'name'          => 'FEDEX_2_DAY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY_AM),
                    'name'          => 'FEDEX_2_DAY_AM',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_EXPRESS_SAVER),
                    'name'          => 'FEDEX_EXPRESS_SAVER',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_STANDARD_OVERNIGHT),
                    'name'          => 'STANDARD_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_FIRST_OVERNIGHT),
                    'name'          => 'FIRST_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_PRIORITY_OVERNIGHT),
                    'name'          => 'PRIORITY_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_ECONOMY),
                    'name'          => 'INTERNATIONAL_ECONOMY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_FIRST),
                    'name'          => 'INTERNATIONAL_FIRST',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_PRIORITY),
                    'name'          => 'INTERNATIONAL_PRIORITY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND_HOME_DELIVERY),
                    'name'          => 'GROUND_HOME_DELIVERY',
                ]
            )
        );

        $easyPostFedEx->addShippingApiIntegrationService(
            new ShippingApiIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_SMART_POST),
                    'name'          => 'SMART_POST',
                ]
            )
        );

        $this->easyPostShippingApiIntegration->addShippingApiIntegrationCarrier($easyPostFedEx);
    }

}
