<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateClient implements Cleanable, Validatable, \JsonSerializable
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
     * @var UpdateClientOptions|null
     */
    protected $options;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        if (!is_null($this->id))
            $this->setId($this->id);

        $this->name                     = AU::get($data['name']);

        $this->options                  = AU::get($data['options']);
        if (!is_null($this->options))
        {
            $this->options              = new UpdateClientOptions($this->options);
            $this->setId($this->id);
        }

    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (!is_null($this->options))
            $this->options->validate();
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);

        if (!is_null($this->options))
            $this->options->clean();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['options']              = is_null($this->options) ? null : $this->options->jsonSerialize();

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
        if (!is_null($this->options))
            $this->options->setId($this->id);
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
     * @return UpdateClientOptions|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param UpdateClientOptions|null $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}