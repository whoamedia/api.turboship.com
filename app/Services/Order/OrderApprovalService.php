<?php

namespace App\Services\Order;


use App\Services\Address\ProvidedAddressService;

class OrderApprovalService
{

    /**
     * @var ProvidedAddressService
     */
    private $providedAddressService;


    public function __construct()
    {
        $this->providedAddressService   = new ProvidedAddressService();
    }
}