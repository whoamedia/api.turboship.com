<?php

namespace App\Http\Controllers;


use App\Http\Requests\Inventory\GetVariantInventory;
use App\Http\Requests\PortableBins\CreatePortableBin;
use App\Http\Requests\PortableBins\GetPortableBins;
use App\Http\Requests\PortableBins\ShowPortableBin;
use App\Http\Requests\PortableBins\UpdatePortableBin;
use App\Models\WMS\PortableBin;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PortableBinController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\WMS\PortableBinRepository
     */
    private $portableBinRepo;

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;

    public function __construct ()
    {
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
    }


    public function index (Request $request)
    {
        $getPortableBins                = new GetPortableBins($request->input());
        $getPortableBins->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getPortableBins->jsonSerialize();

        $results                        = $this->portableBinRepo->where($query, false);
        return response($results);
    }

    public function store (Request $request)
    {
        $createPortableBin              = new CreatePortableBin($request->input());
        $createPortableBin->validate();
        $createPortableBin->clean();

        $binQuery       = [
            'organizationIds'           => parent::getAuthUserOrganization()->getId(),
            'barCodes'                  => $createPortableBin->getBarCode(),
        ];
        $binResults                     = $this->portableBinRepo->where($binQuery);
        if (sizeof($binResults) != 0)
            throw new BadRequestHttpException('PortableBin barCode must be unique');

        $json                           = $createPortableBin->jsonSerialize();
        $bin                            = new PortableBin($json);
        $bin->setOrganization(parent::getAuthUserOrganization());

        $this->portableBinRepo->saveAndCommit($bin);
        return response($bin, 201);
    }

    public function show (Request $request)
    {
        $bin                            = $this->getPortableBinFromRoute($request->route('id'));
        return response($bin);
    }

    public function update (Request $request)
    {
        $updatePortableBin              = new UpdatePortableBin($request->input());
        $updatePortableBin->setId($request->route('id'));
        $updatePortableBin->validate();
        $updatePortableBin->clean();

        $bin                            = $this->getPortableBinFromRoute($updatePortableBin->getId());

        if (!is_null($updatePortableBin->getBarCode()) && $bin->getBarCode() != $updatePortableBin->getBarCode())
        {
            $binQuery       = [
                'organizationIds'       => parent::getAuthUserOrganization()->getId(),
                'barCodes'              => $updatePortableBin->getBarCode(),
            ];
            $binResults                 = $this->portableBinRepo->where($binQuery);
            if (sizeof($binResults) != 0)
                throw new BadRequestHttpException('PortableBin barCode must be unique');

            $bin->setBarCode($updatePortableBin->getBarCode());
        }

        $this->portableBinRepo->saveAndCommit($bin);
        return response($bin);
    }

    public function getInventory (Request $request)
    {
        $portableBin                    = $this->getPortableBinFromRoute($request->route('id'));

        $getVariantInventory            = new GetVariantInventory($request->input());
        $getVariantInventory->setInventoryLocationIds($portableBin->getId());
        $getVariantInventory->setGroupedReport(true);

        $query                          = $getVariantInventory->jsonSerialize();
        $results                        = $this->variantInventoryRepo->where($query);

        return response($results);
    }

    /**
     * @param   int     $id
     * @return  PortableBin
     */
    private function getPortableBinFromRoute ($id)
    {
        $showPortableBin                    = new ShowPortableBin();
        $showPortableBin->setId($id);
        $showPortableBin->validate();
        $showPortableBin->clean();

        $portableBin                        = $this->portableBinRepo->getOneById($showPortableBin->getId());
        if (is_null($portableBin))
            throw new NotFoundHttpException('PortableBin not found');
        if ($portableBin->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('PortableBin not found');

        return $portableBin;
    }

}