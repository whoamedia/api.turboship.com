<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Utilities\IntegrationCredentialUtility;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Utilities\IntegrationUtility;
use App\Models\Shipments\ShippingContainer;

class WhoaMediaSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\CMS\ClientRepository
     */
    private $clientRepo;

    /**
     * @var \App\Repositories\Doctrine\Integrations\IntegrationCredentialRepository
     */
    private $integrationCredentialRepo;

    /**
     * @var \App\Repositories\Doctrine\Integrations\IntegrationRepository
     */
    private $integrationRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\OrganizationRepository
     */
    private $organizationRepo;

    /**
     * @var \App\Models\CMS\Organization
     */
    private $organization;

    /**
     * @var \App\Models\CMS\Client
     */
    private $client;

    /**
     * @var \App\Repositories\Doctrine\Integrations\ShippingApiIntegrationRepository
     */
    private $shippingIntegrationRepo;

    /**
     * @var \App\Models\Shipments\Shipper
     */
    private $shipper;


    public function run()
    {
        $this->clientRepo       = EntityManager::getRepository('App\Models\CMS\Client');
        $this->integrationRepo  = EntityManager::getRepository('App\Models\Integrations\Integration');
        $this->integrationCredentialRepo    = EntityManager::getRepository('App\Models\Integrations\IntegrationCredential');
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
        $this->organizationRepo = EntityManager::getRepository('App\Models\CMS\Organization');
        $this->shippingIntegrationRepo  = EntityManager::getRepository('App\Models\Integrations\ShippingApiIntegration');

        $this->organization();
        $this->users();
        $this->clients();
        $this->shopify();
        $this->shippingContainers();
        $this->shippers();
        $this->printers();
        $this->easyPost();
    }

    private function organization ()
    {
        $organization           = new \App\Models\CMS\Organization();
        $organization->setName('Whoa Media');

        $this->organizationRepo->saveAndCommit($organization);

        $this->organization     = $organization;
    }

    private function users()
    {
        $sourceValidation       = new \App\Models\Support\Validation\SourceValidation();
        $internalSource         = $sourceValidation->getInternal();
        $imageService           = new \App\Services\ImageService();
        //  Edward
        $user                   = new User();
        $user->setFirstName('Edward');
        $user->setLastName('Upton');
        $user->setEmail('eupton@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/3.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);
        $this->organizationRepo->saveAndCommit($user);

        //  James
        $user                   = new User();
        $user->setFirstName('James');
        $user->setLastName('Weston');
        $user->setEmail('james@turboship.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/15.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Miles
        $user                   = new User();
        $user->setFirstName('Miles');
        $user->setLastName('Maximini');
        $user->setEmail('miles@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/4.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Crystal
        $user                   = new User();
        $user->setFirstName('Crystal');
        $user->setLastName('Buyalos');
        $user->setEmail('crystal@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/5.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Ainsley
        $user                   = new User();
        $user->setFirstName('Ainsley');
        $user->setLastName('Dougherty');
        $user->setEmail('ainsley@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/6.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Cody
        $user                   = new User();
        $user->setFirstName('Cody');
        $user->setLastName('Smith');
        $user->setEmail('cody@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/7.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Corey
        $user                   = new User();
        $user->setFirstName('Corey');
        $user->setLastName('Stallings');
        $user->setEmail('corey@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/8.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Michael
        $user                   = new User();
        $user->setFirstName('Michael');
        $user->setLastName('Ferrell');
        $user->setEmail('michael@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/9.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Michael
        $user                   = new User();
        $user->setFirstName('Michael');
        $user->setLastName('Woolcott');
        $user->setEmail('michael.woolcott@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/11.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Thomas
        $user                   = new User();
        $user->setFirstName('Thomas');
        $user->setLastName('Watson');
        $user->setEmail('thomas@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/12.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Corey
        $user                   = new User();
        $user->setFirstName('Corey');
        $user->setLastName('Webb');
        $user->setEmail('thomas@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/13.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Shenouda
        $user                   = new User();
        $user->setFirstName('Shenouda');
        $user->setLastName('Guergues');
        $user->setEmail('shenouda@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/14.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Lane
        $user                   = new User();
        $user->setFirstName('Lane');
        $user->setLastName('Norman');
        $user->setEmail('lane@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/16.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        //  Travis
        $user                   = new User();
        $user->setFirstName('Travis');
        $user->setLastName('Pence');
        $user->setEmail('travis@cheapundies.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $image                  = $imageService->handleImage('https://flow.turboship.com/images/users/17.jpg');
        $image->setSource($internalSource);
        $user->setImage($image);

        $this->organizationRepo->saveAndCommit($user);
    }

    private function clients ()
    {
        $client                 = new \App\Models\CMS\Client();
        $client->setName('Whoa Media');
        $client->getOptions()->setDefaultShipToPhone('8774430266');
        $client->setOrganization($this->organization);
        $this->clientRepo->saveAndCommit($client);

        $this->client           = $client;
    }

    private function shopify ()
    {
        $integratedShoppingCart      = new IntegratedShoppingCart();
        $integratedShoppingCart->setName('Whoa Media Shopify');
        $integratedShoppingCart->setClient($this->client);

        $shopifyIntegration     = $this->integrationRepo->getOneById(IntegrationUtility::SHOPIFY_ID);
        $integratedShoppingCart->setIntegration($shopifyIntegration);

        $shopifyApiKey          = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_API_KEY_ID);
        $clientCredential       = new \App\Models\Integrations\Credential();
        $clientCredential->setIntegrationCredential($shopifyApiKey);

        //  (test)95fc4807a02d76f0e1251be499079371      (production)e9629539a4e9ef0e1147164e11ce6794
        $clientCredential->setValue('e9629539a4e9ef0e1147164e11ce6794');
        $integratedShoppingCart->addCredential($clientCredential);


        $shopifyPassword        = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID);
        $clientCredential       = new \App\Models\Integrations\Credential();
        $clientCredential->setIntegrationCredential($shopifyPassword);

        //  (test)ffca6bc8c3af8ae6e7077a9644d5d294      (production)67794b72c5dd4f3f085354f8df36f36c
        $clientCredential->setValue('67794b72c5dd4f3f085354f8df36f36c');
        $integratedShoppingCart->addCredential($clientCredential);

        $shopifyPassword        = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID);
        $clientCredential       = new \App\Models\Integrations\Credential();
        $clientCredential->setIntegrationCredential($shopifyPassword);

        //  (test)ship-test     (production)cheapundies
        $clientCredential->setValue('cheapundies');
        $integratedShoppingCart->addCredential($clientCredential);


        $shopifySharedSecret    = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_SHARED_SECRET_ID);
        $clientCredential       = new \App\Models\Integrations\Credential();
        $clientCredential->setIntegrationCredential($shopifySharedSecret);

        //  (test)1a59ea54bddd0635cdaf9662e5a1235c      (production)1a7b27523fc4bb310f3f3506c7e90a88
        $clientCredential->setValue('1a7b27523fc4bb310f3f3506c7e90a88');
        $integratedShoppingCart->addCredential($clientCredential);

        $this->clientRepo->saveAndCommit($integratedShoppingCart);
    }

    private function shippingContainers ()
    {
        $shippingContainerTypeValidation    = new \App\Models\Support\Validation\ShippingContainerTypeValidation();
        $c4                     = new ShippingContainer();
        $c4->setName('Box C4');
        $c4->setLength(11.50);
        $c4->setWidth(13.13);
        $c4->setHeight(2.38);
        $c4->setWeight(0.00);
        $c4->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($c4);

        $A1                     = new ShippingContainer();
        $A1->setName('Bag A1');
        $A1->setLength(10.50);
        $A1->setWidth(16.00);
        $A1->setHeight(1.00);
        $A1->setWeight(0.00);
        $A1->setShippingContainerType($shippingContainerTypeValidation->getAutoBagger());
        $this->organization->addShippingContainer($A1);

        $A2                     = new ShippingContainer();
        $A2->setName('Bag A2');
        $A2->setLength(14.25);
        $A2->setWidth(20.00);
        $A2->setHeight(1.00);
        $A2->setWeight(0.00);
        $A2->setShippingContainerType($shippingContainerTypeValidation->getAutoBagger());
        $this->organization->addShippingContainer($A2);

        $C3                     = new ShippingContainer();
        $C3->setName('Box C3');
        $C3->setLength(7.00);
        $C3->setWidth(7.00);
        $C3->setHeight(6.00);
        $C3->setWeight(0.00);
        $C3->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($C3);

        $C5                     = new ShippingContainer();
        $C5->setName('Box C5');
        $C5->setLength(12.00);
        $C5->setWidth(12.00);
        $C5->setHeight(8.00);
        $C5->setWeight(0.00);
        $C5->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($C5);

        $C2                     = new ShippingContainer();
        $C2->setName('Bag C2');
        $C2->setLength(11.63);
        $C2->setWidth(15.13);
        $C2->setHeight(1.00);
        $C2->setWeight(0.00);
        $C2->setShippingContainerType($shippingContainerTypeValidation->getAutoBagger());
        $this->organization->addShippingContainer($C2);

        $C1                     = new ShippingContainer();
        $C1->setName('Bag C1');
        $C1->setLength(9.50);
        $C1->setWidth(12.50);
        $C1->setHeight(1.00);
        $C1->setWeight(0.00);
        $C1->setShippingContainerType($shippingContainerTypeValidation->getAutoBagger());
        $this->organization->addShippingContainer($C1);

        $B1                     = new ShippingContainer();
        $B1->setName('Box B1');
        $B1->setLength(12.00);
        $B1->setWidth(9.00);
        $B1->setHeight(4.00);
        $B1->setWeight(0.00);
        $B1->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B1);

        $B2                     = new ShippingContainer();
        $B2->setName('Box B2');
        $B2->setLength(13.00);
        $B2->setWidth(10.00);
        $B2->setHeight(4.00);
        $B2->setWeight(0.00);
        $B2->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B2);

        $B3                     = new ShippingContainer();
        $B3->setName('Box B3');
        $B3->setLength(15.00);
        $B3->setWidth(12.00);
        $B3->setHeight(4.00);
        $B3->setWeight(0.00);
        $B3->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B3);

        $B4                     = new ShippingContainer();
        $B4->setName('Box B4');
        $B4->setLength(16.00);
        $B4->setWidth(12.00);
        $B4->setHeight(4.00);
        $B4->setWeight(0.00);
        $B4->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B4);

        $B5                     = new ShippingContainer();
        $B5->setName('Box B5');
        $B5->setLength(16.00);
        $B5->setWidth(12.00);
        $B5->setHeight(6.00);
        $B5->setWeight(0.00);
        $B5->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B5);

        $B6                     = new ShippingContainer();
        $B6->setName('Box B6');
        $B6->setLength(20.00);
        $B6->setWidth(4.00);
        $B6->setHeight(6.00);
        $B6->setWeight(0.00);
        $B6->setShippingContainerType($shippingContainerTypeValidation->getRigidBox());
        $this->organization->addShippingContainer($B6);

        $this->organizationRepo->saveAndCommit($this->organization);
    }


    private function shippers ()
    {
        $subdivisionValidation  = new \App\Models\Locations\Validation\SubdivisionValidation(EntityManager::getRepository('App\Models\Locations\Subdivision'));
        $virginia               = $subdivisionValidation->idExists(4706);

        $this->shipper          = new \App\Models\Shipments\Shipper();
        $this->shipper->setName('Whoa Media');

        $address                = new \App\Models\Locations\Address();
        $address->setFirstName('Shipping');
        $address->setLastName('Department');
        $address->setCompany('WhoaMedia LLC');
        $address->setStreet1('9825 Atlee Commons Dr');
        $address->setStreet2('Suite 124');
        $address->setCity('Ashland');
        $address->setSubdivision($virginia);
        $address->setStateProvince($virginia->getLocalSymbol());
        $address->setPostalCode(23005);
        $address->setCountry($virginia->getCountry());
        $address->setCountryCode($virginia->getCountry()->getIso2());
        $address->setPhone('8774430266');

        $this->shipper->setAddress($address);

        $returnAddress          = new \App\Models\Locations\Address();
        $returnAddress->setFirstName('Shipping');
        $returnAddress->setLastName('Department');
        $returnAddress->setCompany('WhoaMedia LLC');
        $returnAddress->setStreet1('9825 Atlee Commons Dr');
        $returnAddress->setStreet2('Suite 124');
        $returnAddress->setCity('Ashland');
        $returnAddress->setSubdivision($virginia);
        $returnAddress->setStateProvince($virginia->getLocalSymbol());
        $returnAddress->setPostalCode(23005);
        $returnAddress->setCountry($virginia->getCountry());
        $returnAddress->setCountryCode($virginia->getCountry()->getIso2());
        $returnAddress->setPhone('8774430266');

        $this->shipper->setReturnAddress($returnAddress);

        $this->shipper->addClient($this->client);
        $this->organization->addShipper($this->shipper);

        $this->organizationRepo->saveAndCommit($this->organization);

        $this->client->getOptions()->setDefaultShipper($this->shipper);
        $this->clientRepo->saveAndCommit($this->client);
    }

    private function printers ()
    {
        $printerTypeValidation  = new \App\Models\WMS\Validation\PrinterTypeValidation();
        $cupsPrinterType        = $printerTypeValidation->getCUPSServer();

        $ZM400                  = new \App\Models\WMS\Printer();
        $ZM400->setName('ZM400');
        $ZM400->setDescription('Barcode Printer');
        $ZM400->setIpAddress('192.168.1.82');
        $ZM400->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($ZM400);

        $PeelerPrinter          = new \App\Models\WMS\Printer();
        $PeelerPrinter->setName('PeelerPrinter');
        $PeelerPrinter->setDescription('Thermal Label');
        $PeelerPrinter->setIpAddress('192.168.1.82');
        $PeelerPrinter->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($PeelerPrinter);

        $ManualInvoice          = new \App\Models\WMS\Printer();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Invoice beside shipping station');
        $ManualInvoice->setIpAddress('192.168.1.82');
        $ManualInvoice->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($ManualInvoice);

        $Invoice                = new \App\Models\WMS\Printer();
        $Invoice->setName('Invoice');
        $Invoice->setDescription('Conveyor Invoice');
        $Invoice->setIpAddress('192.168.1.82');
        $Invoice->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($Invoice);

        $Datamax1               = new \App\Models\WMS\Printer();
        $Datamax1->setName('Datamax1');
        $Datamax1->setDescription('Bagger');
        $Datamax1->setIpAddress('192.168.1.82');
        $Datamax1->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($Datamax1);

        $PlainPaper             = new \App\Models\WMS\Printer();
        $PlainPaper->setName('PlainPaper');
        $PlainPaper->setDescription('Printer on grey table');
        $PlainPaper->setIpAddress('192.168.1.82');
        $PlainPaper->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($PlainPaper);

        $PeelerPrinter2         = new \App\Models\WMS\Printer();
        $PeelerPrinter2->setName('PeelerPrinter2');
        $PeelerPrinter2->setDescription('Second thermal label');
        $PeelerPrinter2->setIpAddress('192.168.1.82');
        $PeelerPrinter2->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($PeelerPrinter2);

        $ManualInvoice         = new \App\Models\WMS\Printer();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Report printer');
        $ManualInvoice->setIpAddress('192.168.1.82');
        $ManualInvoice->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($ManualInvoice);

        $ManualInvoice         = new \App\Models\WMS\Printer();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Invoice at shipping station');
        $ManualInvoice->setIpAddress('192.168.1.82');
        $ManualInvoice->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($ManualInvoice);

        $ThermalLabel         = new \App\Models\WMS\Printer();
        $ThermalLabel->setName('ThermalLabel');
        $ThermalLabel->setDescription('Front Office Thermal Label');
        $ThermalLabel->setIpAddress('192.168.1.82');
        $ThermalLabel->setPrinterType($cupsPrinterType);
        $this->organization->addPrinter($ThermalLabel);

        $this->organizationRepo->saveAndCommit($this->organization);
    }

    private function easyPost ()
    {
        $integratedShipping     = new \App\Models\Integrations\IntegratedShippingApi();
        $integratedShipping->setName('Whoa Media EasyPost');
        $integratedShipping->setShipper($this->shipper);

        $easyPostIntegration    = $this->integrationRepo->getOneById(IntegrationUtility::EASYPOST_ID);
        $integratedShipping->setIntegration($easyPostIntegration);

        $easyPostApiKey         = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::EASYPOST_API_KEY_ID);
        $clientCredential       = new \App\Models\Integrations\Credential();
        $clientCredential->setIntegrationCredential($easyPostApiKey);

        //  (test)9ryULR9axDntXQZzHQ6zOQ
        $clientCredential->setValue('9ryULR9axDntXQZzHQ6zOQ');
        $integratedShipping->addCredential($clientCredential);

        $this->shipper->addIntegratedShippingApi($integratedShipping);
        $this->organizationRepo->saveAndCommit($this->organization);

        $this->client->getOptions()->setDefaultIntegratedShippingApi($integratedShipping);
        $this->clientRepo->saveAndCommit($this->client);
    }

}
