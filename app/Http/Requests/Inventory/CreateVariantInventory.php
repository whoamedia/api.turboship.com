<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\BaseRequest;
use App\Http\Requests\BaseShowBarcode;
use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateVariantInventory extends CreateInventory implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $variantId;


    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->variantId                = AU::get($data['variantId']);
    }

    public function validate()
    {
        parent::validate();

        if (is_null($this->variantId))
            throw new BadRequestHttpException('variantId is required');

        if (is_null(parent::getInteger($this->variantId)))
            throw new BadRequestHttpException('variantId must be integer');
    }

    public function clean()
    {
        parent::clean();

        $this->variantId                = parent::getInteger($this->variantId);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['variantId']            = $this->variantId;

        return $object;
    }

    /**
     * @return int
     */
    public function getVariantId()
    {
        return $this->variantId;
    }

    /**
     * @param int $variantId
     */
    public function setVariantId($variantId)
    {
        $this->variantId = $variantId;
    }

}