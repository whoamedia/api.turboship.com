<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClientShippingIntegration extends CreateClientIntegration implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $shippingIntegrationId;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shippingIntegrationId    = AU::get($data['shippingIntegrationId']);
    }


    public function validate()
    {
        parent::validate();

        if (is_null($this->shippingIntegrationId))
            throw new BadRequestHttpException('shippingIntegrationId is required');

        if (is_null(InputUtil::getInt($this->shippingIntegrationId)))
            throw new BadRequestHttpException('shippingIntegrationId must be integer');
    }

    public function clean()
    {
        parent::clean();

        $this->shippingIntegrationId    = InputUtil::getInt($this->shippingIntegrationId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['shippingIntegrationId']= $this->shippingIntegrationId;

        return $object;
    }

    /**
     * @return int
     */
    public function getShippingIntegrationId()
    {
        return $this->shippingIntegrationId;
    }

    /**
     * @param int $shippingIntegrationId
     */
    public function setShippingIntegrationId($shippingIntegrationId)
    {
        $this->shippingIntegrationId = $shippingIntegrationId;
    }

}