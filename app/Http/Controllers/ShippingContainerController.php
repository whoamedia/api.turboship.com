<?php

namespace App\Http\Controllers;


use App\Http\Requests\ShippingContainers\CreateShippingContainer;
use App\Http\Requests\ShippingContainers\GetShippingContainers;
use App\Http\Requests\ShippingContainers\ShowShippingContainer;
use App\Http\Requests\ShippingContainers\UpdateShippingContainer;
use App\Models\Shipments\ShippingContainer;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Models\Support\Validation\ShippingContainerTypeValidation;
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


    public function create (Request $request)
    {
        $createShippingContainer        = new CreateShippingContainer($request->input());
        $createShippingContainer->setOrganizationId(parent::getAuthUserOrganization()->getId());
        $createShippingContainer->validate();
        $createShippingContainer->clean();

        $shippingContainerTypeValidation = new ShippingContainerTypeValidation();
        $shippingContainerType          = $shippingContainerTypeValidation->idExists($createShippingContainer->getShippingContainerTypeId());

        $json                           = $createShippingContainer->jsonSerialize();
        unset($json['organizationId']);
        unset($json['shippingContainerTypeId']);
        $json['organization']           = parent::getAuthUserOrganization();

        $shippingContainer              = new ShippingContainer($json);
        $shippingContainer->setShippingContainerType($shippingContainerType);
        $shippingContainer->validate();

        $this->shippingContainerRepo->saveAndCommit($shippingContainer);

        return response($shippingContainer, 201);
    }

    public function update (Request $request)
    {
        $updateShippingContainer        = new UpdateShippingContainer($request->input());
        $updateShippingContainer->setId($request->route('id'));
        $updateShippingContainer->validate();
        $updateShippingContainer->clean();

        $shippingContainer              = $this->getShippingContainerFromRoute($updateShippingContainer->getId());

        if (!is_null($updateShippingContainer->getShippingContainerTypeId()))
        {
            $shippingContainerTypeValidation = new ShippingContainerTypeValidation();
            $shippingContainerType      = $shippingContainerTypeValidation->idExists($updateShippingContainer->getShippingContainerTypeId());
            $shippingContainer->setShippingContainerType($shippingContainerType);
        }

        if (!is_null($updateShippingContainer->getName()))
            $shippingContainer->setName($updateShippingContainer->getName());

        if (!is_null($updateShippingContainer->getLength()))
            $shippingContainer->setLength($updateShippingContainer->getLength());

        if (!is_null($updateShippingContainer->getWidth()))
            $shippingContainer->setWidth($updateShippingContainer->getWidth());

        if (!is_null($updateShippingContainer->getHeight()))
            $shippingContainer->setHeight($updateShippingContainer->getHeight());

        if (!is_null($updateShippingContainer->getWeight()))
            $shippingContainer->setWeight($updateShippingContainer->getWeight());

        $shippingContainer->validate();
        $this->shippingContainerRepo->saveAndCommit($shippingContainer);
        return response ($shippingContainer);
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