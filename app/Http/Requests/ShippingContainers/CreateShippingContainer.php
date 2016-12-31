<?php

namespace App\Http\Requests\ShippingContainers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateShippingContainer extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $organizationId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $length;

    /**
     * @var float
     */
    protected $width;

    /**
     * @var float
     */
    protected $height;

    /**
     * @var float
     */
    protected $weight;


    public function __construct($data = [])
    {
        $this->organizationId           = AU::get($data['organizationId']);
        $this->name                     = AU::get($data['name']);
        $this->length                   = AU::get($data['length']);
        $this->width                    = AU::get($data['width']);
        $this->height                   = AU::get($data['height']);
        $this->weight                   = AU::get($data['weight']);
    }

    public function validate()
    {
        if (is_null($this->organizationId))
            throw new BadRequestHttpException('organizationId is required');

        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->length))
            throw new BadRequestHttpException('length is required');

        if (is_null($this->width))
            throw new BadRequestHttpException('width is required');

        if (is_null($this->height))
            throw new BadRequestHttpException('height is required');

        if (is_null($this->weight))
            throw new BadRequestHttpException('weight is required');

        if (is_null(InputUtil::getInt($this->organizationId)))
            throw new BadRequestHttpException('organizationId must be integer');

        $this->length                   = parent::validateFloat($this->length, 'length');
        $this->width                    = parent::validateFloat($this->width, 'width');
        $this->height                   = parent::validateFloat($this->height, 'height');
        $this->weight                   = parent::validateFloat($this->weight, 'weight');
    }

    public function clean ()
    {
        $this->organizationId           = InputUtil::getInt($this->organizationId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['organizationId']       = $this->organizationId;
        $object['name']                 = $this->name;
        $object['length']               = $this->length;
        $object['width']                = $this->width;
        $object['height']               = $this->height;
        $object['weight']               = $this->weight;

        return $object;
    }

    /**
     * @return int
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param int $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param float $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

}