<?php

namespace App\Models\Shipments;


use App\Models\CMS\Organization;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShippingContainer implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

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

    /**
     * @var Organization
     */
    protected $organization;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->length                   = AU::get($data['length']);
        $this->width                    = AU::get($data['width']);
        $this->height                   = AU::get($data['height']);
        $this->weight                   = AU::get($data['weight']);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['length']               = $this->length;
        $object['width']                = $this->width;
        $object['height']               = $this->height;
        $object['weight']               = $this->weight;

        return $object;
    }

    public function validate ()
    {
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


        if (empty(trim($this->name)))
            throw new BadRequestHttpException('name cannot be empty');

        if (is_null(InputUtil::getInt($this->length)) && is_null(InputUtil::getFloat($this->length)))
            throw new BadRequestHttpException('length must be decimal');

        if (is_null(InputUtil::getInt($this->width)) && is_null(InputUtil::getFloat($this->width)))
            throw new BadRequestHttpException('width must be decimal');

        if (is_null(InputUtil::getInt($this->height)) && is_null(InputUtil::getFloat($this->height)))
            throw new BadRequestHttpException('height must be decimal');

        if (is_null(InputUtil::getInt($this->weight)) && is_null(InputUtil::getFloat($this->weight)))
            throw new BadRequestHttpException('weight must be decimal');
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

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

}