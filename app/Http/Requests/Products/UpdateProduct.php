<?php

namespace App\Http\Requests\Products;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateProduct extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
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
     * @var string|null
     */
    protected $description;



    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');
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

}