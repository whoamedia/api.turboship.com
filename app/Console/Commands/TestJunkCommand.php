<?php

namespace App\Console\Commands;


use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\PostageService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

use App\Services\IPP\PrintIPP;
use Storage;

class TestJunkCommand extends Command
{

    use DispatchesJobs;

    protected $signature    =   'turboship:test
                                {--PRODUCTS : Import products}
                                {--ORDERS : Import orders}';

    protected $description = 'Test whatever junk you want here';

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var IntegratedShippingApiRepository
     */
    private $integratedShippingApiRepo;

    public function __construct()
    {
        parent::__construct();

        $this->shipmentRepo                 = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shipment                           = $this->shipmentRepo->getOneById(1);
        $zplPath                            = 'https://easypost-files.s3-us-west-2.amazonaws.com/files/postage_label/20170210/1ccb4d4672884c0b85ef28d7bf579f5c.zpl';
        $labelContents                      = file_get_contents($zplPath);
        $path                               = 'label.zpl';
        Storage::put($path, $labelContents);

        $storagePrefix                      = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $fullPath                           = $storagePrefix . ltrim(Storage::disk('local')->url($path), '/');

        $l                          = [];

        foreach (explode("\n", $labelContents) as $line)
        {
            if (!preg_match("/^\^FO10,560\^GFB,2040/", $line))
                $l[]                = $line;
        }
        $label                      = implode("\n", $l);

        $print = new PrintIPP();
        $print->setPort('631');
        $print->setHost('208.73.141.38');
        $print->setPrinterURI('/printers/ThermalLabel');
        $print->setRawText();

        $print->setData($label);

        $result =  $print->printJob();
        dd($result);
    }

}