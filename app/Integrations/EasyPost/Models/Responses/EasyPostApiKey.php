<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#api-keys
 * Class ApiKey
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostApiKey
{

    /**
     * "ApiKey"
     * @var	string
     */
    protected $object;

    /**
     * "test" or "production"
     * @var	string
     */
    protected $mode;

    /**
     * The actual key value to use for authentication
     * @var	string
     */
    protected $key;

    /**
     * @var	string
     */
    protected $created_at;


}