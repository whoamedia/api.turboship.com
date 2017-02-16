<?php

namespace App\Http\Controllers;


use App\Http\Requests\PortableBins\CreatePortableBin;
use App\Http\Requests\PortableBins\GetPortableBins;
use App\Http\Requests\PortableBins\ShowPortableBin;
use App\Http\Requests\PortableBins\UpdatePortableBin;
use App\Models\WMS\PortableBin;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PortableBinController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\WMS\PortableBinRepository
     */
    private $binRepo;


    public function __construct ()
    {
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\PortableBin');
    }


    public function index (Request $request)
    {
        $getPortableBins                = new GetPortableBins($request->input());
        $getPortableBins->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getPortableBins->jsonSerialize();

        $results                        = $this->binRepo->where($query, false);
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
        $binResults                     = $this->binRepo->where($binQuery);
        if (sizeof($binResults) != 0)
            throw new BadRequestHttpException('PortableBin barCode must be unique');

        $json                           = $createPortableBin->jsonSerialize();
        $bin                            = new PortableBin($json);
        $bin->setOrganization(parent::getAuthUserOrganization());

        $this->binRepo->saveAndCommit($bin);
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
            $binResults                 = $this->binRepo->where($binQuery);
            if (sizeof($binResults) != 0)
                throw new BadRequestHttpException('PortableBin barCode must be unique');

            $bin->setBarCode($updatePortableBin->getBarCode());
        }

        $this->binRepo->saveAndCommit($bin);
        return response($bin);
    }

    public function getInventory (Request $request)
    {
        $portableBin                    = $this->getPortableBinFromRoute($request->route('id'));
        return response($portableBin->getInventory());
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

        $bin                            = $this->binRepo->getOneById($showPortableBin->getId());
        if (is_null($bin))
            throw new NotFoundHttpException('PortableBin not found');
        if ($bin->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('PortableBin not found');

        return $bin;
    }

}