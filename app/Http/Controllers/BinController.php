<?php

namespace App\Http\Controllers;


use App\Http\Requests\Bins\CreateBin;
use App\Http\Requests\Bins\GetBins;
use App\Http\Requests\Bins\ShowBin;
use App\Http\Requests\Bins\UpdateBin;
use App\Models\WMS\Bin;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BinController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\WMS\BinRepository
     */
    private $binRepo;


    public function __construct ()
    {
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');
    }


    public function index (Request $request)
    {
        $getBins                        = new GetBins($request->input());
        $getBins->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getBins->jsonSerialize();

        $results                        = $this->binRepo->where($query, false);
        return response($results);
    }

    public function store (Request $request)
    {
        $createBin                      = new CreateBin($request->input());
        $createBin->validate();
        $createBin->clean();

        $binQuery       = [
            'organizationIds'           => parent::getAuthUserOrganization()->getId(),
            'barCodes'                  => $createBin->getBarCode(),
        ];
        $binResults                     = $this->binRepo->where($binQuery);
        if (sizeof($binResults) != 0)
            throw new BadRequestHttpException('Bin barCode must be unique');

        $json                           = $createBin->jsonSerialize();
        $bin                            = new Bin($json);
        $bin->setOrganization(parent::getAuthUserOrganization());

        $this->binRepo->saveAndCommit($bin);
        return response($bin, 201);
    }

    public function show (Request $request)
    {
        $bin                            = $this->getBinFromRoute($request->route('id'));
        return response($bin);
    }

    public function update (Request $request)
    {
        $updateBin                      = new UpdateBin($request->input());
        $updateBin->setId($request->route('id'));
        $updateBin->validate();
        $updateBin->clean();

        $bin                            = $this->getBinFromRoute($updateBin->getId());

        if (!is_null($updateBin->getBarCode()) && $bin->getBarCode() != $updateBin->getBarCode())
        {
            $binQuery       = [
                'organizationIds'       => parent::getAuthUserOrganization()->getId(),
                'barCodes'              => $updateBin->getBarCode(),
            ];
            $binResults                 = $this->binRepo->where($binQuery);
            if (sizeof($binResults) != 0)
                throw new BadRequestHttpException('Bin barCode must be unique');

            $bin->setBarCode($updateBin->getBarCode());
        }

        $this->binRepo->saveAndCommit($bin);
        return response($bin);
    }

    /**
     * @param   int     $id
     * @return  Bin
     */
    private function getBinFromRoute ($id)
    {
        $showBin                        = new ShowBin();
        $showBin->setId($id);
        $showBin->validate();
        $showBin->clean();

        $bin                            = $this->binRepo->getOneById($showBin->getId());
        if (is_null($bin))
            throw new NotFoundHttpException('Bin not found');
        if ($bin->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('Bin not found');

        return $bin;
    }

}