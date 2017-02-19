<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\Staff;
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

    /**
     * @var \App\Repositories\Doctrine\WMS\BinRepository
     */
    private $binRepo;

    /**
     * @var \App\Repositories\Doctrine\WMS\ToteRepository
     */
    private $toteRepo;

    /**
     * @var \App\Repositories\Doctrine\WMS\PortableBinRepository
     */
    private $portableBinRepo;

    public function run()
    {
        $this->clientRepo       = EntityManager::getRepository('App\Models\CMS\Client');
        $this->integrationRepo  = EntityManager::getRepository('App\Models\Integrations\Integration');
        $this->integrationCredentialRepo    = EntityManager::getRepository('App\Models\Integrations\IntegrationCredential');
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
        $this->organizationRepo = EntityManager::getRepository('App\Models\CMS\Organization');
        $this->shippingIntegrationRepo  = EntityManager::getRepository('App\Models\Integrations\ShippingApiIntegration');
        $this->binRepo          = EntityManager::getRepository('App\Models\WMS\Bin');
        $this->toteRepo         = EntityManager::getRepository('App\Models\WMS\Tote');
        $this->portableBinRepo  = EntityManager::getRepository('App\Models\WMS\PortableBin');

        $this->organization();
        $this->users();
        $this->clients();
        $this->shopify();
        $this->shippingContainers();
        $this->shippers();
        $this->printers();
        $this->easyPost();
        $this->bins();
        $this->totes();
        $this->portableBins();
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
        $Edward                 = new Staff();
        $Edward->setBarCode('0vyGeE');
        $Edward->setFirstName('Edward');
        $Edward->setLastName('Upton');
        $Edward->setEmail('eupton@whoamedia.com');
        $Edward->setPassword('password');
        $Edward->setOrganization($this->organization);
        $EdwardImage            = $imageService->handleImage('https://flow.turboship.com/images/users/3.jpg');
        $EdwardImage->setSource($internalSource);
        $Edward->setImage($EdwardImage);
        $this->userRepo->saveAndCommit($Edward);

        //  James
        $James                   = new Staff();
        $James->setBarCode('jf3Nf3');
        $James->setFirstName('James');
        $James->setLastName('Weston');
        $James->setEmail('james@turboship.com');
        $James->setPassword('password');
        $James->setOrganization($this->organization);
        $JamesImage             = $imageService->handleImage('https://flow.turboship.com/images/users/15.jpg');
        $JamesImage->setSource($internalSource);
        $James->setImage($JamesImage);
        $this->userRepo->saveAndCommit($James);

        //  Miles
        $Miles                   = new Staff();
        $Miles->setBarCode('IDrUS9');
        $Miles->setFirstName('Miles');
        $Miles->setLastName('Maximini');
        $Miles->setEmail('miles@cheapundies.com');
        $Miles->setPassword('password');
        $Miles->setOrganization($this->organization);
        $MilesImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/4.jpg');
        $MilesImage->setSource($internalSource);
        $Miles->setImage($MilesImage);
        $this->userRepo->saveAndCommit($Miles);

        //  Crystal
        $Crystal                   = new Staff();
        $Crystal->generateBarCode(50);
        $Crystal->setFirstName('Crystal');
        $Crystal->setLastName('Buyalos');
        $Crystal->setEmail('crystal@cheapundies.com');
        $Crystal->setPassword('password');
        $Crystal->setOrganization($this->organization);
        $CrystalImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/5.jpg');
        $CrystalImage->setSource($internalSource);
        $Crystal->setImage($CrystalImage);
        $this->userRepo->saveAndCommit($Crystal);

        //  Ainsley
        $Ainsley                   = new Staff();
        $Ainsley->generateBarCode(50);
        $Ainsley->setFirstName('Ainsley');
        $Ainsley->setLastName('Dougherty');
        $Ainsley->setEmail('ainsley@cheapundies.com');
        $Ainsley->setPassword('password');
        $Ainsley->setOrganization($this->organization);
        $AinsleyImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/6.jpg');
        $AinsleyImage->setSource($internalSource);
        $Ainsley->setImage($AinsleyImage);
        $this->userRepo->saveAndCommit($Ainsley);

        //  Cody
        $CodySmith                   = new Staff();
        $CodySmith->generateBarCode(50);
        $CodySmith->setFirstName('Cody');
        $CodySmith->setLastName('Smith');
        $CodySmith->setEmail('cody@cheapundies.com');
        $CodySmith->setPassword('password');
        $CodySmith->setOrganization($this->organization);
        $CodySmithImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/7.jpg');
        $CodySmithImage->setSource($internalSource);
        $CodySmith->setImage($CodySmithImage);
        $this->userRepo->saveAndCommit($CodySmith);

        //  Corey
        $CoreyStallings                   = new Staff();
        $CoreyStallings->generateBarCode(50);
        $CoreyStallings->setFirstName('Corey');
        $CoreyStallings->setLastName('Stallings');
        $CoreyStallings->setEmail('corey@cheapundies.com');
        $CoreyStallings->setPassword('password');
        $CoreyStallings->setOrganization($this->organization);
        $CoreyStallingsImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/8.jpg');
        $CoreyStallingsImage->setSource($internalSource);
        $CoreyStallings->setImage($CoreyStallingsImage);
        $this->userRepo->saveAndCommit($CoreyStallings);

        //  Michael
        $MichaelFerrell                   = new Staff();
        $MichaelFerrell->generateBarCode(50);
        $MichaelFerrell->setFirstName('Michael');
        $MichaelFerrell->setLastName('Ferrell');
        $MichaelFerrell->setEmail('michael@cheapundies.com');
        $MichaelFerrell->setPassword('password');
        $MichaelFerrell->setOrganization($this->organization);
        $MichaelFerrellImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/9.jpg');
        $MichaelFerrellImage->setSource($internalSource);
        $MichaelFerrell->setImage($MichaelFerrellImage);
        $this->userRepo->saveAndCommit($MichaelFerrell);

        //  Michael
        $MichaelWoolcott                   = new Staff();
        $MichaelWoolcott->generateBarCode(50);
        $MichaelWoolcott->setFirstName('Michael');
        $MichaelWoolcott->setLastName('Woolcott');
        $MichaelWoolcott->setEmail('michael.woolcott@cheapundies.com');
        $MichaelWoolcott->setPassword('password');
        $MichaelWoolcott->setOrganization($this->organization);
        $MichaelWoolcottImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/11.jpg');
        $MichaelWoolcottImage->setSource($internalSource);
        $MichaelWoolcott->setImage($MichaelWoolcottImage);
        $this->userRepo->saveAndCommit($MichaelWoolcott);

        //  Thomas
        $ThomasWatson                   = new Staff();
        $ThomasWatson->generateBarCode(50);
        $ThomasWatson->setFirstName('Thomas');
        $ThomasWatson->setLastName('Watson');
        $ThomasWatson->setEmail('thomas@cheapundies.com');
        $ThomasWatson->setPassword('password');
        $ThomasWatson->setOrganization($this->organization);
        $ThomasWatsonImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/12.jpg');
        $ThomasWatsonImage->setSource($internalSource);
        $ThomasWatson->setImage($ThomasWatsonImage);
        $this->userRepo->saveAndCommit($ThomasWatson);

        //  Corey
        $CoreyWebb                   = new Staff();
        $CoreyWebb->generateBarCode(50);
        $CoreyWebb->setFirstName('Corey');
        $CoreyWebb->setLastName('Webb');
        $CoreyWebb->setEmail('thomas@whoamedia.com');
        $CoreyWebb->setPassword('password');
        $CoreyWebb->setOrganization($this->organization);
        $CoreyWebbImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/13.jpg');
        $CoreyWebbImage->setSource($internalSource);
        $CoreyWebb->setImage($CoreyWebbImage);
        $this->userRepo->saveAndCommit($CoreyWebb);

        //  Shenouda
        $Shenouda                   = new Staff();
        $Shenouda->generateBarCode(50);
        $Shenouda->setFirstName('Shenouda');
        $Shenouda->setLastName('Guergues');
        $Shenouda->setEmail('shenouda@whoamedia.com');
        $Shenouda->setPassword('password');
        $Shenouda->setOrganization($this->organization);
        $ShenoudaImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/14.jpg');
        $ShenoudaImage->setSource($internalSource);
        $Shenouda->setImage($ShenoudaImage);
        $this->userRepo->saveAndCommit($Shenouda);

        //  Lane
        $Lane                   = new Staff();
        $Lane->generateBarCode(50);
        $Lane->setFirstName('Lane');
        $Lane->setLastName('Norman');
        $Lane->setEmail('lane@whoamedia.com');
        $Lane->setPassword('password');
        $Lane->setOrganization($this->organization);
        $LaneImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/16.jpg');
        $LaneImage->setSource($internalSource);
        $Lane->setImage($LaneImage);
        $this->userRepo->saveAndCommit($Lane);

        //  Travis
        $TravisPence                   = new Staff();
        $TravisPence->generateBarCode(50);
        $TravisPence->setFirstName('Travis');
        $TravisPence->setLastName('Pence');
        $TravisPence->setEmail('travis@cheapundies.com');
        $TravisPence->setPassword('password');
        $TravisPence->setOrganization($this->organization);
        $TravisPenceImage                  = $imageService->handleImage('https://flow.turboship.com/images/users/17.jpg');
        $TravisPenceImage->setSource($internalSource);
        $TravisPence->setImage($TravisPenceImage);
        $this->userRepo->saveAndCommit($TravisPence);
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
        $printerTypeValidation  = new \App\Models\Hardware\Validation\PrinterTypeValidation();
        $cupsPrinterType        = $printerTypeValidation->getCUPSServer();

        $ZM400                  = new \App\Models\Hardware\CUPSPrinter();
        $ZM400->setName('ZM400');
        $ZM400->setDescription('Barcode Printer');
        $ZM400->setAddress('208.73.141.38');
        $ZM400->setPort('631');
        $ZM400->setFormat('ZPL');
        $this->organization->addPrinter($ZM400);

        $PeelerPrinter          = new \App\Models\Hardware\CUPSPrinter();
        $PeelerPrinter->setName('PeelerPrinter');
        $PeelerPrinter->setDescription('Thermal Label');
        $PeelerPrinter->setAddress('208.73.141.38');
        $PeelerPrinter->setPort('631');
        $PeelerPrinter->setFormat('ZPL');
        $this->organization->addPrinter($PeelerPrinter);

        $ManualInvoice          = new \App\Models\Hardware\CUPSPrinter();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Invoice beside shipping station');
        $ManualInvoice->setAddress('208.73.141.38');
        $ManualInvoice->setPort('631');
        $ManualInvoice->setFormat('ZPL');
        $this->organization->addPrinter($ManualInvoice);

        $Invoice                = new \App\Models\Hardware\CUPSPrinter();
        $Invoice->setName('Invoice');
        $Invoice->setDescription('Conveyor Invoice');
        $Invoice->setAddress('208.73.141.38');
        $Invoice->setPort('631');
        $Invoice->setFormat('ZPL');
        $this->organization->addPrinter($Invoice);

        $Datamax1               = new \App\Models\Hardware\CUPSPrinter();
        $Datamax1->setName('Datamax1');
        $Datamax1->setDescription('Bagger');
        $Datamax1->setAddress('208.73.141.38');
        $Datamax1->setPort('631');
        $Datamax1->setFormat('ZPL');
        $this->organization->addPrinter($Datamax1);

        $PlainPaper             = new \App\Models\Hardware\CUPSPrinter();
        $PlainPaper->setName('PlainPaper');
        $PlainPaper->setDescription('Printer on grey table');
        $PlainPaper->setAddress('208.73.141.38');
        $PlainPaper->setPort('631');
        $PlainPaper->setFormat('ZPL');
        $this->organization->addPrinter($PlainPaper);

        $PeelerPrinter2         = new \App\Models\Hardware\CUPSPrinter();
        $PeelerPrinter2->setName('PeelerPrinter2');
        $PeelerPrinter2->setDescription('Second thermal label');
        $PeelerPrinter2->setAddress('208.73.141.38');
        $PeelerPrinter2->setPort('631');
        $PeelerPrinter2->setFormat('ZPL');
        $this->organization->addPrinter($PeelerPrinter2);

        $ManualInvoice         = new \App\Models\Hardware\CUPSPrinter();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Report printer');
        $ManualInvoice->setAddress('208.73.141.38');
        $ManualInvoice->setPort('631');
        $ManualInvoice->setFormat('ZPL');
        $this->organization->addPrinter($ManualInvoice);

        $ManualInvoice         = new \App\Models\Hardware\CUPSPrinter();
        $ManualInvoice->setName('ManualInvoice');
        $ManualInvoice->setDescription('Invoice at shipping station');
        $ManualInvoice->setAddress('208.73.141.38');
        $ManualInvoice->setPort('631');
        $ManualInvoice->setFormat('ZPL');
        $this->organization->addPrinter($ManualInvoice);

        $ThermalLabel         = new \App\Models\Hardware\CUPSPrinter();
        $ThermalLabel->setName('ThermalLabel');
        $ThermalLabel->setDescription('Front Office Thermal Label');
        $ThermalLabel->setAddress('208.73.141.38');
        $ThermalLabel->setPort('631');
        $ThermalLabel->setFormat('ZPL');
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

    private function bins ()
    {
        for( $aisle = 1; $aisle < 11; $aisle++ )
        {
            for( $section = 'A'; $section < 'G'; $section++ )
            {
                for( $row = 1; $row < 11; $row++ )
                {
                    for( $col = 1; $col < 11; $col++ )
                    {
                        $bin    = new \App\Models\WMS\Bin();
                        $bin->setAisle($aisle);
                        $bin->setSection($section);
                        $bin->setRow($row);
                        $bin->setCol($col);
                        $bin->setOrganization($this->organization);
                        $bin->setBarCode(\Illuminate\Support\Str::random(20));
                        $this->binRepo->save($bin);
                    }
                }
            }
        }
    }

    private function totes ()
    {
        for( $i = 7000; $i < 7200; $i++ )
        {
            $tote               = new \App\Models\WMS\Tote();
            $tote->setBarCode($i);
            $tote->setWeight(4.5);
            $tote->setOrganization($this->organization);
            $this->toteRepo->save($tote);
        }
    }

    private function portableBins ()
    {
        for( $i = 0; $i < 20; $i++ )
        {
            $portableBin        = new \App\Models\WMS\PortableBin();
            $portableBin->setBarCode(\Illuminate\Support\Str::random(20));
            $portableBin->setOrganization($this->organization);
            $this->portableBinRepo->save($portableBin);
        }
    }
}
