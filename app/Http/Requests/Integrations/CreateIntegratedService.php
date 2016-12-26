<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class CreateIntegratedService implements Cleanable, Validatable, \JsonSerializable
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
     * @var CreateCredential[]
     */
    protected $credentials;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->name                     = AU::get($data['name']);

        $this->credentials              = [];
        $credentials                    = AU::get($data['credentials'], []);

        if (!empty($credentials))
        {
            foreach ($credentials AS $item)
            {
                $this->credentials[]    = new CreateCredential($item);
            }
        }
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->credentials))
            throw new BadRequestHttpException('credentials is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (empty(trim($this->name)))
            throw new BadRequestHttpException('name must be string');
        if (empty($this->credentials))
            throw new BadRequestHttpException('credentials is required');

        foreach ($this->credentials AS $credential)
            $credential->validate();
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        foreach ($this->credentials AS $credential)
            $credential->clean();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['name']               = $this->name;
        $object['credentials']          = [];
        foreach ($this->credentials AS $credential)
            $object['credentials']      = $credential->jsonSerialize();

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
     * @return CreateCredential[]
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param CreateCredential[] $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

}