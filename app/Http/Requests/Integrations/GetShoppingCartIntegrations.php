<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShoppingCartIntegrations extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
    }

    public function validate()
    {
        $this->ids                      = $this->validateIds($this->ids, 'ids');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;

        return $object;
    }

    public function clean ()
    {

    }

}