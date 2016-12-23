<?php

use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ShippingIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\IntegrationWebHook;

class ShippingIntegrationSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\Integrations\ShippingIntegrationRepository
     */
    private $shippingIntegrationRepo;


    public function run()
    {
        $this->shippingIntegrationRepo      = EntityManager::getRepository('App\Models\Integrations\ShippingIntegration');

        $this->easyPost();
    }


    private function easyPost ()
    {
        $shippingIntegration                = new ShippingIntegration();
        $shippingIntegration->setName('EasyPost');


        $easyPostApiKey                     = new IntegrationCredential();
        $easyPostApiKey->setName('apiKey');
        $easyPostApiKey->setIsRequired(true);
        $shippingIntegration->addIntegrationCredential($easyPostApiKey);



    }


}
