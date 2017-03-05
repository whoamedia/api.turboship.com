<?php

namespace App\Http\Requests\Carts;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetCarts extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $barCodes;

    public function __construct($data = [])
    {
        parent::__construct('cart.id', $data);

        $this->barCodes                 = AU::get($data['barCodes']);
        $this->organizationIds          = AU::get($data['organizationIds']);
    }

    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['barCodes']             = $this->barCodes;
        $object['organizationIds']      = $this->organizationIds;

        return $object;
    }

    public function clean()
    {
        parent::clean();
        $this->organizationIds          = parent::getCommaSeparatedIds($this->organizationIds);
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

    /**
     * @return null|string
     */
    public function getBarCodes()
    {
        return $this->barCodes;
    }

    /**
     * @param null|string $barCodes
     */
    public function setBarCodes($barCodes)
    {
        $this->barCodes = $barCodes;
    }

}