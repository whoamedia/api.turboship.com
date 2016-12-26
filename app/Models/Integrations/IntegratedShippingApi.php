<?php

namespace App\Models\Integrations;


use App\Models\Shipments\Shipper;
use jamesvweston\Utilities\ArrayUtil AS AU;

class IntegratedShippingApi extends IntegratedService
{


    /**
     * @var Shipper
     */
    protected $shipper;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shipper                  = AU::get($data['shipper']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['shipper']              = $this->shipper->jsonSerialize();

        return $object;
    }

    /**
     * @return Shipper
     */
    public function getShipper()
    {
        return $this->shipper;
    }

    /**
     * @param Shipper $shipper
     */
    public function setShipper($shipper)
    {
        $this->shipper = $shipper;
    }

}