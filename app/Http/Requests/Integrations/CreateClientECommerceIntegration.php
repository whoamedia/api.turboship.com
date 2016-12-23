<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClientECommerceIntegration extends CreateClientIntegration implements Cleanable, Validatable, \JsonSerializable
{


    /**
     * @var int
     */
    protected $eCommerceIntegrationId;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->eCommerceIntegrationId   = AU::get($data['eCommerceIntegrationId']);
    }


    public function validate()
    {
        parent::validate();

        if (is_null($this->eCommerceIntegrationId))
            throw new BadRequestHttpException('eCommerceIntegrationId is required');

        if (is_null(InputUtil::getInt($this->eCommerceIntegrationId)))
            throw new BadRequestHttpException('eCommerceIntegrationId must be integer');
    }

    public function clean()
    {
        parent::clean();

        $this->eCommerceIntegrationId   = InputUtil::getInt($this->eCommerceIntegrationId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['eCommerceIntegrationId'] = $this->eCommerceIntegrationId;

        return $object;
    }

    /**
     * @return int
     */
    public function getECommerceIntegrationId()
    {
        return $this->eCommerceIntegrationId;
    }

    /**
     * @param int $eCommerceIntegrationId
     */
    public function setECommerceIntegrationId($eCommerceIntegrationId)
    {
        $this->eCommerceIntegrationId = $eCommerceIntegrationId;
    }


}