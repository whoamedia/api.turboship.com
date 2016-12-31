<?php

namespace App\Models\Integrations;


class ShoppingCartIntegration extends Integration implements \JsonSerializable
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'ShoppingCartIntegration';
    }

}