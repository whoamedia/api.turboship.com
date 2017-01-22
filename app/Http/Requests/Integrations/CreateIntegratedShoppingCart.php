<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateIntegratedShoppingCart extends CreateIntegratedService implements Cleanable, Validatable, \JsonSerializable
{


    /**
     * @var int
     */
    protected $clientId;


    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->clientId                 = AU::get($data['clientId']);
    }

    public function validate()
    {
        parent::validate();
        if (is_null($this->clientId))
            throw new BadRequestHttpException('clientId is required');

        if (is_null(InputUtil::getInt($this->clientId)))
            throw new BadRequestHttpException('clientId must be integer');
    }

    public function clean ()
    {
        parent::clean();
        $this->clientId                 = InputUtil::getInt($this->clientId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['clientId']             = $this->clientId;

        return $object;
    }

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

}