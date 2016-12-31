<?php

namespace App\Models\CMS;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Shipper;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ClientOption implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Client
     */
    protected $client;

    /**
     * In the OrderApprovalService if the shippingAddress phone number is empty this will be used
     * @var string|null
     */
    protected $defaultShipToPhone;

    /**
     * @var Shipper|null
     */
    protected $defaultShipper;

    /**
     * @var IntegratedShippingApi|null
     */
    protected $defaultIntegratedShippingApi;


    public function __construct($data = [])
    {
        $this->client                   = AU::get($data['client']);
        $this->defaultShipToPhone       = AU::get($data['defaultShipToPhone']);
        $this->defaultShipper           = AU::get($data['defaultShipper']);
        $this->defaultIntegratedShippingApi = AU::get($data['defaultIntegratedShippingApi']);
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['defaultShipToPhone']   = $this->defaultShipToPhone;
        $object['defaultShipper']       = is_null($this->defaultShipper) ? null : $this->defaultShipper->jsonSerialize();
        $object['defaultIntegratedShippingApi'] = is_null($this->defaultIntegratedShippingApi) ? null : $this->defaultIntegratedShippingApi->jsonSerialize();

        return $object;
    }

    /**
     * @return null|string
     */
    public function getDefaultShipToPhone()
    {
        return $this->defaultShipToPhone;
    }

    /**
     * @param null|string $defaultShipToPhone
     */
    public function setDefaultShipToPhone($defaultShipToPhone)
    {
        $this->defaultShipToPhone = $defaultShipToPhone;
    }

    /**
     * @return Shipper|null
     */
    public function getDefaultShipper()
    {
        return $this->defaultShipper;
    }

    /**
     * @param Shipper|null $defaultShipper
     */
    public function setDefaultShipper($defaultShipper)
    {
        $this->defaultShipper = $defaultShipper;
    }

    /**
     * @return IntegratedShippingApi|null
     */
    public function getDefaultIntegratedShippingApi()
    {
        return $this->defaultIntegratedShippingApi;
    }

    /**
     * @param IntegratedShippingApi|null $defaultIntegratedShippingApi
     */
    public function setDefaultIntegratedShippingApi($defaultIntegratedShippingApi)
    {
        $this->defaultIntegratedShippingApi = $defaultIntegratedShippingApi;
    }

}