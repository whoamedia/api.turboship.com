<?php

namespace App\Models\Integrations;


class ShippingIntegration extends Integration
{


    /**
     * @return string
     */
    public function getObject()
    {
        return 'ECommerce';
    }

}