<?php

namespace App\Repositories\EasyPost;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Models\Shipments\Shipment;

class EasyPostShipmentRepository extends BaseEasyPostRepository
{

    /**
     * @param   CreateEasyPostShipment $createEasyPostShipment
     * @return  \App\Integrations\EasyPost\Models\Responses\EasyPostShipment
     */
    public function rate (CreateEasyPostShipment $createEasyPostShipment)
    {
        return $this->easyPostIntegration->shipmentApi->create($createEasyPostShipment);

    }

    /**
     * @param   string      $easyPostShipmentId
     * @param   string      $easyPostRateId
     * @return  \App\Integrations\EasyPost\Models\Responses\EasyPostShipment
     */
    public function buy ($easyPostShipmentId, $easyPostRateId)
    {
        return $this->easyPostIntegration->shipmentApi->buy($easyPostShipmentId, $easyPostRateId);
    }

    /**
     * @param   string      $easyPostShipmentId
     * @return \App\Integrations\EasyPost\Models\Responses\EasyPostShipment
     */
    public function void ($easyPostShipmentId)
    {
        return $this->easyPostIntegration->shipmentApi->refund($easyPostShipmentId);
    }
}