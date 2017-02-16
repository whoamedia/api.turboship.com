<?php

namespace App\Utilities;


class ShipmentStatusUtility
{

    const PENDING                       = 1;
    const PARTIALLY_SHIPPED             = 2;
    const FULLY_SHIPPED                 = 3;

    //  Address Error Statuses
    const INVALID_CITY_ID               = 100;
    const INVALID_STATE_ID              = 101;
    const INVALID_ADDRESS_ID            = 102;
    const MULTIPLE_ADDRESSES_FOUND_ID   = 103;
    const INVALID_POSTAL_CODE_ID        = 104;
    const INVALID_COUNTRY_ID            = 105;
    const INVALID_STREET_ID             = 106;
    const INVALID_PHONE_NUMBER          = 107;

}