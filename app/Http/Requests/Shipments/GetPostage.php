<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GetPostage extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(parent::getInteger($this->id)))
            throw new BadRequestHttpException('id must be integer');
    }

    public function clean ()
    {
        $this->id                       = parent::getInteger($this->id);
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