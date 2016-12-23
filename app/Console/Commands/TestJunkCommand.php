<?php

namespace App\Console\Commands;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\EasyPostIntegration;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostAddress;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Requests\GetEasyPostShipments;
use App\Models\Integrations\Validation\ClientIntegrationValidation;
use App\Utilities\CredentialUtility;
use Illuminate\Console\Command;

class TestJunkCommand extends Command
{

    protected $signature    =   'turboship:test
                                {--PRODUCTS : Import products}
                                {--ORDERS : Import orders}';

    protected $description = 'Test whatever junk you want here';


    /**
     * @var ClientIntegrationValidation
     */
    private $clientIntegrationValidation;


    public function __construct()
    {
        parent::__construct();

        $this->clientIntegrationValidation  = new ClientIntegrationValidation();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $clientIntegration          = $this->clientIntegrationValidation->idExists(2);
        $credentialUtility          = new CredentialUtility($clientIntegration);

        $easyPostApiKey             = $credentialUtility->getEasyPostApiKey();

        $easyPostConfiguration      = new EasyPostConfiguration();
        $easyPostConfiguration->setApiKey($easyPostApiKey->getValue());

        $easyPostIntegration        = new EasyPostIntegration($easyPostConfiguration);

        $createEasyPostAddress      = new CreateEasyPostAddress();
        $createEasyPostAddress->setStreet1('41 East Liberty St');
        $createEasyPostAddress->setCity('Savannah');
        $createEasyPostAddress->setState('GA');
        $createEasyPostAddress->setZip('31401');
        $createEasyPostAddress->setCountry('US');

        //  $easyPostIntegration->addressApi->create($createEasyPostAddress);
        //  $easyPostIntegration->addressApi->show('adr_d07f7a41fe1642ccbc2750c07cad770b');
        $createEasyPostShipment     = new CreateEasyPostShipment();
        $createEasyPostShipment->setToAddress($createEasyPostAddress);

        $getEasyPostShipments       = new GetEasyPostShipments();
        $easyPostIntegration->shipmentApi->get($getEasyPostShipments);
    }

}