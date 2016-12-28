<?php

namespace App\Utilities;


use App\Models\OMS\Validation\OrderStatusValidation;

class OrderStatusUtility
{

    //  LifeCycle
    const CREATED_ID                    = 1;
    const CANCELLED                     = 2;


    //  Address Error Statuses
    const INVALID_CITY_ID               = 100;
    const INVALID_STATE_ID              = 101;
    const INVALID_ADDRESS_ID            = 102;
    const MULTIPLE_ADDRESSES_FOUND_ID   = 103;
    const INVALID_POSTAL_CODE_ID        = 104;
    const INVALID_COUNTRY_ID            = 105;
    const INVALID_STREET_ID             = 106;


    //  Order Error Statuses
    const UNMAPPED_SKU                  = 120;


    //  Fulfillment Operations
    const PENDING_FULFILLMENT_ID        = 200;
    const PULLED_ID                     = 201;
    const PICKED_ID                     = 202;


    //  Shipping Operations
    const PARTIALLY_SHIPPED_ID          = 300;
    const FULLY_SHIPPED_ID              = 301;

    /**
     * @return array
     */
    public static function getAddressErrors ()
    {
        return [
            self::INVALID_CITY_ID,
            self::INVALID_STATE_ID,
            self::INVALID_ADDRESS_ID,
            self::MULTIPLE_ADDRESSES_FOUND_ID,
            self::INVALID_POSTAL_CODE_ID,
            self::INVALID_COUNTRY_ID,
            self::INVALID_STREET_ID,
        ];
    }

    /**
     * @var OrderStatusValidation
     */
    private $orderStatusValidation;


    public function __construct()
    {
        $this->orderStatusValidation    = new OrderStatusValidation();
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getCreated ()
    {
        return $this->orderStatusValidation->idExists(self::CREATED_ID);
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getPendingFulfillment ()
    {
        return $this->orderStatusValidation->idExists(self::PENDING_FULFILLMENT_ID);
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getPulled ()
    {
        return $this->orderStatusValidation->idExists(self::PULLED_ID);
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getPicked ()
    {
        return $this->orderStatusValidation->idExists(self::PICKED_ID);
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getPartiallyShipped ()
    {
        return $this->orderStatusValidation->idExists(self::PARTIALLY_SHIPPED_ID);
    }

    /**
     * @return \App\Models\OMS\OrderStatus
     */
    public function getFullyShipped ()
    {
        return $this->orderStatusValidation->idExists(self::FULLY_SHIPPED_ID);
    }

}