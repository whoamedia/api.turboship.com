<?php


use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ShippingIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\ShippingIntegrationCarrier;
use App\Utilities\CarrierUtility;
use App\Utilities\ServiceUtility;
use App\Models\Integrations\ShippingIntegrationService;


/**
 * @see https://www.easypost.com/service-levels-and-parcels.html
 * Class EasyPostServiceSeeder
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
     * @var \App\Repositories\Doctrine\Integrations\ShippingIntegrationRepository
     */
    private $shippingIntegrationRepo;

    /**
     * @var ShippingIntegration
     */
    private $easyPostShippingIntegration;

    public function run()
    {
        $this->carrierRepo                  = EntityManager::getRepository('App\Models\Shipments\Carrier');
        $this->serviceRepo                  = EntityManager::getRepository('App\Models\Shipments\Service');
        $this->shippingIntegrationRepo      = EntityManager::getRepository('App\Models\Integrations\ShippingIntegration');

        $this->easyPostShippingIntegration  = new ShippingIntegration();
        $this->easyPostShippingIntegration->setName('EasyPost');


        $easyPostApiKey                     = new IntegrationCredential();
        $easyPostApiKey->setName('apiKey');
        $easyPostApiKey->setIsRequired(true);
        $this->easyPostShippingIntegration->addIntegrationCredential($easyPostApiKey);

        $this->easyPostUSPS();
        $this->easyPostUPS();
        $this->easyPostUPSMailInnovations();
        $this->easyPostDHLGlobalMail();
        $this->easyPostFedEx();

        $this->shippingIntegrationRepo->saveAndCommit($this->easyPostShippingIntegration);
    }


    private function easyPostUSPS ()
    {
        /**
         * USPS
         */
        $easyPostUSPS                       = new ShippingIntegrationCarrier();
        $easyPostUSPS->setName('USPS');
        $easyPostUSPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::USPS));

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PARCEL_SELECT),
                    'name'          => 'ParcelSelect',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_LIBRARY_MAIL),
                    'name'          => 'LibraryMail',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_MEDIA_MAIL),
                    'name'          => 'MediaMail',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_CRITICAL_MAIL),
                    'name'          => 'CriticalMail',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_INTERNATIONAL),
                    'name'          => 'FirstClassMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL),
                    'name'          => 'FirstClassPackageInternationalService',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_PRIORITY_MAIL_INTERNATIONAL),
                    'name'          => 'PriorityMailInternational',
                ]
            )
        );

        $easyPostUSPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::USPS_EXPRESS_MAIL_INTERNATIONAL),
                    'name'          => 'ExpressMailInternational',
                ]
            )
        );

        $this->easyPostShippingIntegration->addShippingIntegrationCarrier($easyPostUSPS);
    }

    private function easyPostUPS ()
    {
        $easyPostUPS                        = new ShippingIntegrationCarrier();
        $easyPostUPS->setName('UPS');
        $easyPostUPS->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS));

        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_GROUND),
                    'name'          => 'Ground',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_STANDARD),
                    'name'          => 'UPSStandard',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_UPS_SAVER),
                    'name'          => 'UPSSaver',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS),
                    'name'          => 'Express',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPRESS_PLUS),
                    'name'          => 'ExpressPlus',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_EXPEDITED),
                    'name'          => 'Expedited',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR),
                    'name'          => 'NextDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_SAVER),
                    'name'          => 'NextDayAirSaver',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_NEXT_DAY_AIR_EARLY_AM),
                    'name'          => 'NextDayAirEarlyAM',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR),
                    'name'          => '2ndDayAir',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_2ND_DAY_AIR_AM),
                    'name'          => '2ndDayAirAM',
                ]
            )
        );
        $easyPostUPS->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_3_DAY_SELECT),
                    'name'          => '3DaySelect',
                ]
            )
        );

        $this->easyPostShippingIntegration->addShippingIntegrationCarrier($easyPostUPS);
    }

    private function easyPostUPSMailInnovations ()
    {
        $easyPostUPSMailInnovations = new ShippingIntegrationCarrier();
        $easyPostUPSMailInnovations->setName('UPS Mail Innovations');
        $easyPostUPSMailInnovations->setCarrier($this->carrierRepo->getOneById(CarrierUtility::UPS_MAIL_INNOVATIONS));

        $easyPostUPSMailInnovations->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_FIRST),
                    'name'          => 'First',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY),
                    'name'          => 'Priority',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS),
                    'name'          => 'ExpeditedMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS),
                    'name'          => 'PriorityMailInnovations',
                ]
            )
        );

        $easyPostUPSMailInnovations->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS),
                    'name'          => 'EconomyMailInnovations',
                ]
            )
        );

        $this->easyPostShippingIntegration->addShippingIntegrationCarrier($easyPostUPSMailInnovations);
    }

    private function easyPostDHLGlobalMail ()
    {
        $easyPostDHLGlobalMail      = new ShippingIntegrationCarrier();
        $easyPostDHLGlobalMail->setName('DHL Global Mail');
        $easyPostDHLGlobalMail->setCarrier($this->carrierRepo->getOneById(CarrierUtility::DHL_GLOBAL_MAIL));

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC),
                    'name'          => 'BPMExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC),
                    'name'          => 'BPMGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC),
                    'name'          => 'FlatsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC),
                    'name'          => 'FlatsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC),
                    'name'          => 'MediaMailGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelPlusExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC),
                    'name'          => 'ParcelPlusGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'ParcelsExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'ParcelsGroundDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC),
                    'name'          => 'MarketingParcelExpeditedDomestic',
                ]
            )
        );

        $easyPostDHLGlobalMail->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC),
                    'name'          => 'MarketingParcelGroundDomestic',
                ]
            )
        );


        $this->easyPostShippingIntegration->addShippingIntegrationCarrier($easyPostDHLGlobalMail);
    }

    private function easyPostFedEx ()
    {
        $easyPostFedEx              = new ShippingIntegrationCarrier();
        $easyPostFedEx->setName('FedEx');
        $easyPostFedEx->setCarrier($this->carrierRepo->getOneById(CarrierUtility::FEDEX));

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND),
                    'name'          => 'FEDEX_GROUND',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY),
                    'name'          => 'FEDEX_2_DAY',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_2_DAY_AM),
                    'name'          => 'FEDEX_2_DAY_AM',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_EXPRESS_SAVER),
                    'name'          => 'FEDEX_EXPRESS_SAVER',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_STANDARD_OVERNIGHT),
                    'name'          => 'STANDARD_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_FIRST_OVERNIGHT),
                    'name'          => 'FIRST_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_PRIORITY_OVERNIGHT),
                    'name'          => 'PRIORITY_OVERNIGHT',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_ECONOMY),
                    'name'          => 'INTERNATIONAL_ECONOMY',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_FIRST),
                    'name'          => 'INTERNATIONAL_FIRST',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_INTERNATIONAL_PRIORITY),
                    'name'          => 'INTERNATIONAL_PRIORITY',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_GROUND_HOME_DELIVERY),
                    'name'          => 'GROUND_HOME_DELIVERY',
                ]
            )
        );

        $easyPostFedEx->addShippingIntegrationService(
            new ShippingIntegrationService(
                [
                    'service'       => $this->serviceRepo->getOneById(ServiceUtility::FEDEX_SMART_POST),
                    'name'          => 'SMART_POST',
                ]
            )
        );

        $this->easyPostShippingIntegration->addShippingIntegrationCarrier($easyPostFedEx);
    }

}
