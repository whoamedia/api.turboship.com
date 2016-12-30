<?php

namespace App\Integrations\EasyPost\Models\Requests;


use App\Integrations\EasyPost\Models\Responses\EasyPostOptions;
use App\Integrations\EasyPost\Traits\SimpleSerialize;

class CreateEasyPostShipment implements \JsonSerializable
{

    use SimpleSerialize;

    /**
     * @var string|null
     */
    protected $reference;

    /**
     * @var CreateEasyPostAddress
     */
    protected $to_address;

    /**
     * @var CreateEasyPostAddress
     */
    protected $from_address;

    /**
     * @var CreateEasyPostParcel
     */
    protected $parcel;

    /**
     * @var string|null
     */
    protected $carrier_accounts;

    /**
     * @var EasyPostOptions|null
     */
    protected $options;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['shipment']                     = $this->simpleSerialize();
        $object['shipment']['to_address']       = is_null($this->to_address) ? null : $this->to_address->jsonSerialize();
        $object['shipment']['from_address']     = is_null($this->from_address) ? null : $this->from_address->jsonSerialize();
        $object['shipment']['parcel']           = is_null($this->parcel) ? null : $this->parcel->jsonSerialize();
        $object['shipment']['options']          = is_null($this->options) ? null : $this->options->jsonSerialize();

        return $object;
    }

    /**
     * @return null|string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param null|string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return CreateEasyPostAddress
     */
    public function getToAddress()
    {
        return $this->to_address;
    }

    /**
     * @param CreateEasyPostAddress $to_address
     */
    public function setToAddress($to_address)
    {
        $this->to_address = $to_address;
    }

    /**
     * @return CreateEasyPostAddress
     */
    public function getFromAddress()
    {
        return $this->from_address;
    }

    /**
     * @param CreateEasyPostAddress $from_address
     */
    public function setFromAddress($from_address)
    {
        $this->from_address = $from_address;
    }

    /**
     * @return CreateEasyPostParcel
     */
    public function getParcel()
    {
        return $this->parcel;
    }

    /**
     * @param CreateEasyPostParcel $parcel
     */
    public function setParcel($parcel)
    {
        $this->parcel = $parcel;
    }

    /**
     * @return null|string
     */
    public function getCarrierAccounts()
    {
        return $this->carrier_accounts;
    }

    /**
     * @param null|string $carrier_accounts
     */
    public function setCarrierAccounts($carrier_accounts)
    {
        $this->carrier_accounts = $carrier_accounts;
    }

    /**
     * @return EasyPostOptions|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param EasyPostOptions|null $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}