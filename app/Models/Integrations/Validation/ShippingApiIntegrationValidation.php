<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\ShippingApiIntegration;
use App\Repositories\Doctrine\Integrations\ShippingApiIntegrationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingApiIntegrationValidation
{

    /**
     * @var ShippingApiIntegrationRepository
     */
    private $shippingApiIntegrationRepo;


    public function __construct()
    {
        $this->shippingApiIntegrationRepo=EntityManager::getRepository('App\Models\Integrations\ShippingApiIntegration');
    }

    /**
     * @param   int     $id
     * @return  ShippingApiIntegration
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $shippingApiIntegration         = $this->shippingApiIntegrationRepo->getOneById($id);

        if (is_null($shippingApiIntegration))
            throw new NotFoundHttpException('ShippingApiIntegration not found');

        return $shippingApiIntegration;
    }

}