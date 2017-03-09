<?php

namespace App\Models\CMS;


use App\Models\Support\Traits\HasBarcode;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Staff extends User implements \JsonSerializable
{

    use HasBarcode;

    public function __construct($data = null)
    {
        parent::__construct($data);

        $this->barCode              = AU::get($data['barCode']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                     = parent::jsonSerialize();
        $object['barCode']          = $this->barCode;
        $object['object']           = 'Staff';

        return $object;
    }

    /**
     * This is temporarily in place to support Bugsnag's error reporting
     * @return array
     */
    public function toArray ()
    {
        return $this->jsonSerialize();
    }

}