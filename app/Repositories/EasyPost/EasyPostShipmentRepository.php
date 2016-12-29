<?php

namespace App\Repositories\EasyPost;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Models\Shipments\Shipment;

class EasyPostShipmentRepository extends BaseEasyPostRepository
{


    public function rate (CreateEasyPostShipment $createEasyPostShipment)
    {
        $easyPostShipment                   = $this->easyPostIntegration->shipmentApi->create($createEasyPostShipment);
        dd($easyPostShipment);
    }
}