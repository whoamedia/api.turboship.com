<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;

class ShippingApiIntegration extends Integration
{


    /**
     * @var ArrayCollection
     */
    protected $shippingApiIntegrationCarriers;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shippingApiIntegrationCarriers       = new ArrayCollection();
    }

    /**
     * @return ShippingApiIntegrationCarrier[]
     */
    public function getShippingApiIntegrationCarriers()
    {
        return $this->shippingApiIntegrationCarriers->toArray();
    }

    /**
     * @param ShippingApiIntegrationCarrier $shippingApiIntegrationCarrier
     */
    public function addShippingApiIntegrationCarrier($shippingApiIntegrationCarrier)
    {
        $shippingApiIntegrationCarrier->setShippingApiIntegration($this);
        $this->shippingApiIntegrationCarriers->add($shippingApiIntegrationCarrier);
    }





    /**
     * @return string
     */
    public function getObject()
    {
        return 'ShippingApiIntegration';
    }

}