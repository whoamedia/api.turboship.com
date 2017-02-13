<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetVariantInventory extends GetInventory implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $variantIds;


    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->variantIds               = AU::get($data['variantIds']);
    }

    public function validate()
    {
        parent::validate();

        $this->variantIds               = parent::validateIds($this->variantIds, 'variantIds');
    }

    public function clean()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['variantIds']           = $this->variantIds;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getVariantIds()
    {
        return $this->variantIds;
    }

    /**
     * @param null|string $variantIds
     */
    public function setVariantIds($variantIds)
    {
        $this->variantIds = $variantIds;
    }

}