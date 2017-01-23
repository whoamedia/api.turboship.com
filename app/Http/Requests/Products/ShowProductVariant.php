<?php

namespace App\Http\Requests\Products;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShowProductVariant extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $variantId;



    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->variantId                = AU::get($data['variantId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->variantId))
            throw new BadRequestHttpException('variantId is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->variantId)))
            throw new BadRequestHttpException('variantId must be integer');
    }

    public function clean ()
    {
        $this->id                       = parent::getInteger($this->id);
        $this->variantId                = parent::getInteger($this->variantId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['variantId']            = $this->variantId;

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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