<?php

namespace App\Models\Shipments\Validation;


use App\Models\Shipments\ShippingContainer;
use App\Repositories\Doctrine\Shipments\ShippingContainerRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingContainerValidation
{

    /**
     * @var ShippingContainerRepository
     */
    private $shippingContainerRepo;


    public function __construct()
    {
        $this->shippingContainerRepo        = EntityManager::getRepository('App\Models\Shipments\ShippingContainer');
    }

    /**
     * @param   int     $id
     * @return  ShippingContainer|null
     */
    public function idExists ($id)
    {
        $shippingContainer                  = $this->shippingContainerRepo->getOneById($id);

        if (is_null($shippingContainer))
            throw new NotFoundHttpException('ShippingContainer not found');

        return $shippingContainer;
    }

}