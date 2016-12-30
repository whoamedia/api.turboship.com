<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;

class ShippingApiIntegration extends Integration implements \JsonSerializable
{


    /**
     * @var ArrayCollection
     */
    protected $shippingApiCarriers;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shippingApiCarriers          = new ArrayCollection();
    }

    /**
     * @return ShippingApiCarrier[]
     */
    public function getShippingApiCarriers()
    {
        return $this->shippingApiCarriers->toArray();
    }

    /**
     * @param ShippingApiCarrier $shippingApiCarrier
     */
    public function addShippingApiCarrier($shippingApiCarrier)
    {
        $shippingApiCarrier->setShippingApiIntegration($this);
        $this->shippingApiCarriers->add($shippingApiCarrier);
    }

    /**
     * @return ShippingApiService[]
     */
    public function getShippingApiServices ()
    {
        $services                       = [];
        foreach ($this->getShippingApiCarriers() AS $shippingApiCarrier)
        {
            foreach ($shippingApiCarrier->getShippingApiServices() AS $shippingApiService)
                $services[]             = $shippingApiService->jsonSerialize();
        }

        return $services;
    }




    /**
     * @return string
     */
    public function getObject()
    {
        return 'ShippingApiIntegration';
    }

}