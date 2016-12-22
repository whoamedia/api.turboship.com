<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#field-error-object
 * Class FieldError
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostFieldError
{

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
}