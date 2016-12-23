<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#field-error-object
 * Class FieldError
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostFieldError
{

    use SimpleSerialize;

    /**
     * Field of the request that the error describes
     * @var string
     */
    protected $field;

    /**
     * Human readable description of the problem
     * @var string
     */
    protected $message;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}