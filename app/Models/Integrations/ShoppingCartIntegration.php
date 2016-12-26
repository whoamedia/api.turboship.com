<?php

namespace App\Models\Integrations;


class ShoppingCartIntegration extends Integration
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'ShoppingCartIntegration';
    }

}