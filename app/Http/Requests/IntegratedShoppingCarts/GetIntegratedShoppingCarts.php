<?php

namespace App\Http\Requests\IntegratedShoppingCarts;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetIntegratedShoppingCarts extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $clientIds;

    /**
     * @var string|null
     */
    protected $organizationIds;



    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
    }

    public function validate()
    {
        $this->ids                      = $this->validateIds($this->ids, 'ids');
        $this->clientIds                = $this->validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = $this->validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;

        return $object;
    }

    public function clean ()
    {

    }

    /**
     * @return null|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param null|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return null|string
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null|string $clientIds
     */
    public function setClientIds($clientIds)
    {
        $this->clientIds = $clientIds;
    }

    /**
     * @return null|string
     */
    public function getOrganizationIds()
    {
        return $this->organizationIds;
    }

    /**
     * @param null|string $organizationIds
     */
    public function setOrganizationIds($organizationIds)
    {
        $this->organizationIds = $organizationIds;
    }

}