<?php

namespace App\Listeners;


use App\Jobs\EasyPost\ImportEasyPostLabelJob;
use App\Models\Shipments\Postage;
use App\Utilities\IntegrationUtility;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PostageListener
{

    use DispatchesJobs;

    /**
     * Called after the entity has been saved for the first time
     * @param Postage $postage
     * @param LifecycleEventArgs $event
     */
    public function postPersistHandler (Postage $postage, LifecycleEventArgs $event)
    {
        if ($postage->getRate()->getIntegratedShippingApi()->getId() == IntegrationUtility::EASYPOST_ID)
        {
            $job                            = (new ImportEasyPostLabelJob($postage->getId()))->onQueue('easyPostLabelConversion');
            $this->dispatch($job);
        }
    }

}