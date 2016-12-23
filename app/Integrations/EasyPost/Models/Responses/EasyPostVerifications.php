<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

class EasyPostVerifications
{

    use SimpleSerialize;

    /**
     * @var EasyPostVerification
     */
    protected $zip4;

    /**
     * @var EasyPostVerification
     */
    protected $delivery;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

    /**
     * @return EasyPostVerification
     */
    public function getZip4()
    {
        return $this->zip4;
    }

    /**
     * @param EasyPostVerification $zip4
     */
    public function setZip4($zip4)
    {
        $this->zip4 = $zip4;
    }

    /**
     * @return EasyPostVerification
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param EasyPostVerification $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }

}