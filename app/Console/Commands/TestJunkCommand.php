<?php

namespace App\Console\Commands;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\EasyPostIntegration;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostAddress;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Requests\GetEasyPostShipments;
use App\Jobs\Orders\OrderSkuMappingJob;
use App\Models\Integrations\Validation\IntegratedServiceValidation;
use App\Services\CredentialService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TestJunkCommand extends Command
{

    use DispatchesJobs;

    protected $signature    =   'turboship:test
                                {--PRODUCTS : Import products}
                                {--ORDERS : Import orders}';

    protected $description = 'Test whatever junk you want here';


    /**
     * @var IntegratedServiceValidation
     */
    private $integratedServiceValidation;


    public function __construct()
    {
        parent::__construct();

        $this->integratedServiceValidation  = new IntegratedServiceValidation();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dispatch(new OrderSkuMappingJob(1, 'asdf'));
    }

}