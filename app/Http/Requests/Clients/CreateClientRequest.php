<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class CreateClientRequest implements Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $name;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
    }


    public function validate()
    {
        if (is_null($this->name))
            throw new MissingMandatoryParametersException('name is required');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['name']                 = $this->name;

        return $object;
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

}