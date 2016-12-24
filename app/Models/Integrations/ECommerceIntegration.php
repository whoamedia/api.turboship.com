<?php

namespace App\Models\Integrations;


class ECommerceIntegration extends Integration
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'ECommerce';
    }

}