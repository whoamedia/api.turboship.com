<?php

namespace App\Models\Support\Validation;


use App\Models\Support\ShippingContainerType;
use App\Repositories\Doctrine\Support\ShippingContainerTypeRepository;
use App\Utilities\ShippingContainerTypeUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingContainerTypeValidation
{

    /**
     * @var ShippingContainerTypeRepository
     */
    private $shippingContainerTypeRepo;


    public function __construct()
    {
        $this->shippingContainerTypeRepo    = EntityManager::getRepository('App\Models\Support\ShippingContainerType');
    }

    /**
     * @param   int     $id
     * @return  ShippingContainerType
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $shippingContainerType              = $this->shippingContainerTypeRepo->getOneById($id);

        if (is_null($shippingContainerType))
            throw new NotFoundHttpException('ShippingContainerType not found');

        return $shippingContainerType;
    }

    /**
     * @return ShippingContainerType
     */
    public function getRigidBox ()
    {
        return $this->idExists(ShippingContainerTypeUtility::RIGID_BOX);
    }

    /**
     * @return ShippingContainerType
     */
    public function getBubbleMailer ()
    {
        return $this->idExists(ShippingContainerTypeUtility::BUBBLE_MAILER);
    }

    /**
     * @return ShippingContainerType
     */
    public function getAutoBagger ()
    {
        return $this->idExists(ShippingContainerTypeUtility::AUTO_BAGGER);
    }

}