<?php

namespace App\Models\WMS;


class TotePick extends PickInstruction implements \JsonSerializable
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'TotePick';
    }

}