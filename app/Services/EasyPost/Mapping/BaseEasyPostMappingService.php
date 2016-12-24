<?php

namespace App\Services\EasyPost\Mapping;


class BaseEasyPostMappingService
{



    /**
     * @param   string|null     $easyPostDate
     * @return  \DateTime|null
     */
    public function toDate ($easyPostDate)
    {
        if (is_null($easyPostDate))
            return null;
        else
            return \DateTime::createFromFormat('Y-m-d\TH:i:sO', $easyPostDate);
    }
}