<?php

namespace App\Http\Controllers;


use App\Http\Requests\ShippingContainers\GetShippingContainers;
use App\Http\Requests\ShippingContainers\ShowShippingContainer;
use App\Models\Shipments\ShippingContainer;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Shipments\ShippingContainerRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingContainerController extends BaseAuthController
{

    /**
     * @var ShippingContainerRepository
     */
    private $shippingContainerRepo;


    public function __construct ()
    {
        $this->shippingContainerRepo    = EntityManager::getRepository('App\Models\Shipments\ShippingContainer');
    }


    public function index (Request $request)
    {
        $getShippingContainers          = new GetShippingContainers($request->input());
        $getShippingContainers->setOrganizationIds(parent::getAuthUserOrganization()->getId());

        $query                          = $getShippingContainers->jsonSerialize();
        $results                        = $this->shippingContainerRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $shippingContainer              = $this->getShippingContainerFromRoute($request->route('id'));
        return response($shippingContainer);
    }

    /**
     * @param   int     $id
     * @return  ShippingContainer
     */
    private function getShippingContainerFromRoute ($id)
    {
        $showShippingContainer          = new ShowShippingContainer();
        $showShippingContainer->setId($id);
        $showShippingContainer->validate();
        $showShippingContainer->clean();

        $shippingContainerValidation    = new ShippingContainerValidation();
        $shippingContainer              = $shippingContainerValidation->idExists($showShippingContainer->getId());

        if ($shippingContainer->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('ShippingContainer not found');

        return $shippingContainer;
    }
}