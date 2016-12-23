<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#api-keys
 * Class ApiKeys
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostApiKeys
{

    use SimpleSerialize;

    /**
     * The User id of the authenticated User making the API Key request
     * @var	string
     */
    protected $id;

    /**
     * A list of all Child Users presented with ONLY id, children, and key array structures.
     * @var	array of API Key response structures
     */
    protected $children;

    /**
     * The list of all API keys active for an account, both for "test" and "production" modes.
     * @var	EasyPostApiKey[]
     */
    protected $keys;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}