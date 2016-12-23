<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;

class ShippingIntegration extends Integration
{


    /**
     * @var ArrayCollection
     */
    protected $shippingIntegrationCarriers;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shippingIntegrationCarriers       = new ArrayCollection();
    }

    /**
     * @return ShippingIntegrationCarrier[]
     */
    public function getShippingIntegrationCarriers()
    {
        return $this->shippingIntegrationCarriers->toArray();
    }

    /**
     * @param ShippingIntegrationCarrier $shippingIntegrationCarrier
     */
    public function addShippingIntegrationCarrier($shippingIntegrationCarrier)
    {
        $shippingIntegrationCarrier->setShippingIntegration($this);
        $this->shippingIntegrationCarriers->add($shippingIntegrationCarrier);
    }





    /**
     * @return string
     */
    public function getObject()
    {
        return 'ShippingIntegration';
    }

}