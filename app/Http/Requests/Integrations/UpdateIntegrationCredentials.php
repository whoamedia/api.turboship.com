<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateIntegrationCredentials implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var UpdateCredential[]
     */
    protected $credentials;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);

        $this->credentials              = [];

        $credentials                    = AU::get($data['credentials']);
        if (!is_null($credentials) && is_array($credentials))
        {
            if (AU::isArrays($credentials))
            {
                foreach ($credentials AS $item)
                    $this->credentials[]    = new UpdateCredential($item);
            }
        }
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->credentials) || empty($this->credentials))
            throw new BadRequestHttpException('credentials is required');

        if (!is_array($this->credentials))
            throw new BadRequestHttpException('credentials must be an array');

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
        $object['credentials']          = [];

        foreach ($this->credentials AS $credential)
            $object['credentials'][]    = $credential->jsonSerialize();

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
     * @return UpdateCredential[]
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param UpdateCredential[] $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }


}