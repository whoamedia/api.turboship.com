<?php

namespace App\Http\Requests\IntegratedShippingApis;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\Integrations\CreateIntegratedService;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateIntegratedShippingApi extends CreateIntegratedService implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $shipperId;


    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->shipperId                = AU::get($data['shipperId']);
    }

    public function validate()
    {
        parent::validate();
        if (is_null($this->shipperId))
            throw new BadRequestHttpException('shipperId is required');

        if (is_null(InputUtil::getInt($this->shipperId)))
            throw new BadRequestHttpException('shipperId must be integer');
    }

    public function clean ()
    {
        parent::clean();
        $this->shipperId                = InputUtil::getInt($this->shipperId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['shipperId']            = $this->shipperId;

        return $object;
    }

    /**
     * @return int
     */
    public function getShipperId()
    {
        return $this->shipperId;
    }

    /**
     * @param int $shipperId
     */
    public function setShipperId($shipperId)
    {
        $this->shipperId = $shipperId;
    }

}