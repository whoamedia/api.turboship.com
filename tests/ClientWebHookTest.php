<?php

namespace Tests;

use App\Models\Integrations\ClientWebHook;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientWebHookTest extends TestCase
{

    public function testAddingWebHooksToClientIntegration ()
    {
        $this->clientIntegration        = $this->clientIntegrationRepo->getOneById(1);

        $shopifyIntegration             = $this->clientIntegration->getIntegration();

        foreach ($shopifyIntegration->getWebHooks() AS $webHook)
        {
            if (!$webHook->isActive())
                continue;

            $clientWebHook              = new ClientWebHook();
            $clientWebHook->setIntegrationWebHook($webHook);
            $clientWebHook->setExternalId(rand(3, 400000));
            $this->clientIntegration->addWebHook($clientWebHook);
        }

        $this->clientIntegrationRepo->saveAndCommit($this->clientIntegration);
    }
}
