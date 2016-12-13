<?php

namespace App\Models\OAuth;


/**
 * @SWG\Definition()
 */
class AccessToken
{

    /**
     * @SWG\Property(example="asdj32oihsadfjh")
     * @var string
     */
    public $access_token;

    /**
     * @SWG\Property(example="Bearer")
     * @var string
     */
    public $token_type;

    /**
     * @SWG\Property(example="604800")
     * @var int
     */
    public $expires_in;

}