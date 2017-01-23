<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClient implements Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CreateClientOptions
     */
    protected $options;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->options                  = AU::get($data['options']);

        if (!is_null($this->options))
            $this->options              = new CreateClientOptions($this->options);
    }


    public function validate()
    {
        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->options))
            throw new BadRequestHttpException('options is required');

        $this->options->validate();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['name']                 = $this->name;
        $object['options']              = $this->options->jsonSerialize();

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

    /**
     * @return CreateClientOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param CreateClientOptions $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}