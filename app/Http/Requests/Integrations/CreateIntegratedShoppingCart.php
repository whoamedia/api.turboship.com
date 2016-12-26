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
    protected $shoppingCartIntegrationId;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->shoppingCartIntegrationId   = AU::get($data['shoppingCartIntegrationId']);
    }


    public function validate()
    {
        parent::validate();

        if (is_null($this->shoppingCartIntegrationId))
            throw new BadRequestHttpException('shoppingCartIntegrationId is required');

        if (is_null(InputUtil::getInt($this->shoppingCartIntegrationId)))
            throw new BadRequestHttpException('shoppingCartIntegrationId must be integer');
    }

    public function clean()
    {
        parent::clean();

        $this->shoppingCartIntegrationId   = InputUtil::getInt($this->shoppingCartIntegrationId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['shoppingCartIntegrationId'] = $this->shoppingCartIntegrationId;

        return $object;
    }

    /**
     * @return int
     */
    public function getECommerceIntegrationId()
    {
        return $this->shoppingCartIntegrationId;
    }

    /**
     * @param int $shoppingCartIntegrationId
     */
    public function setECommerceIntegrationId($shoppingCartIntegrationId)
    {
        $this->shoppingCartIntegrationId = $shoppingCartIntegrationId;
    }


}