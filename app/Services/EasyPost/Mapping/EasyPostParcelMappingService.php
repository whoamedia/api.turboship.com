<?php

namespace App\Services\EasyPost\Mapping;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostParcel;
use App\Models\Shipments\ShippingContainer;

class EasyPostParcelMappingService extends BaseEasyPostMappingService
{

    /**
     * @param   ShippingContainer $shippingContainer
     * @return  CreateEasyPostParcel
     */
    public function toEasyPostParcel (ShippingContainer $shippingContainer)
    {
        $createEasyPostParcel           = new CreateEasyPostParcel();
        $createEasyPostParcel->setLength($shippingContainer->getLength());
        $createEasyPostParcel->setWidth($shippingContainer->getWidth());
        $createEasyPostParcel->setHeight($shippingContainer->getHeight());
        $createEasyPostParcel->setWeight($shippingContainer->getWeight());

        return $createEasyPostParcel;
    }

}