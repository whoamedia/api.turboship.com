<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#addresses
 * Class VerificationDetails
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostVerificationDetails
{

    use SimpleSerialize;

    /**
     * @var string
     */
    protected $latitude;

    /**
     * @var string
     */
    protected $longitude;


    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->latitude                 = AU::get($data['latitude']);
        $this->longitude                = AU::get($data['longitude']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}