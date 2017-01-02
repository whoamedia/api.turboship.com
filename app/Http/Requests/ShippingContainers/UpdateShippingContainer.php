<?php

namespace App\Http\Requests\ShippingContainers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateShippingContainer extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var float|null
     */
    protected $length;

    /**
     * @var float|null
     */
    protected $width;

    /**
     * @var float|null
     */
    protected $height;

    /**
     * @var float|null
     */
    protected $weight;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->name                     = AU::get($data['name']);
        $this->length                   = AU::get($data['length']);
        $this->width                    = AU::get($data['width']);
        $this->height                   = AU::get($data['height']);
        $this->weight                   = AU::get($data['weight']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (!is_null($this->length))
            $this->length               = parent::validateFloat($this->length, 'length');

        if (!is_null($this->width))
            $this->width                = parent::validateFloat($this->width, 'width');

        if (!is_null($this->height))
            $this->height               = parent::validateFloat($this->height, 'height');

        if (!is_null($this->weight))
            $this->weight               = parent::validateFloat($this->weight, 'weight');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['length']               = $this->length;
        $object['width']                = $this->width;
        $object['height']               = $this->height;
        $object['weight']               = $this->weight;

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
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param float|null $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return float|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float|null $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return float|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float|null $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

}