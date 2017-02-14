<?php

namespace App\Http\Controllers;


use App\Http\Requests\Bins\CreateBin;
use App\Http\Requests\Bins\GetBins;
use App\Http\Requests\Bins\ShowBin;
use App\Http\Requests\Bins\UpdateBin;
use App\Models\WMS\Bin;
use App\Models\WMS\Validation\BinValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BinController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\WMS\BinRepository
     */
    private $binRepo;

    /**
     * @var BinValidation
     */
    private $binValidation;


    public function __construct ()
    {
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');
        $this->binValidation            = new BinValidation();
    }


    public function index (Request $request)
    {
        $getBins                        = new GetBins($request->input());
        $getBins->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getBins->jsonSerialize();

        $results                        = $this->binRepo->where($query, false);
        return response($results);
    }

    public function getLexicon (Request $request)
    {
        $getBins                        = new GetBins($request->input());
        $getBins->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getBins->jsonSerialize();

        $results                        = $this->binRepo->getLexicon($query);
        return response($results);
    }

    public function store (Request $request)
    {
        $createBin                      = new CreateBin($request->input());
        $createBin->validate();
        $createBin->clean();

        $this->binValidation->barCodeDoesNotExist(parent::getAuthUserOrganization()->getId(), $createBin->getBarCode());
        $json                           = $createBin->jsonSerialize();
        $bin                            = new Bin($json);
        $this->binValidation->uniqueRackLocation($bin);
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
            $this->binValidation->barCodeDoesNotExist(parent::getAuthUserOrganization()->getId(), $updateBin->getBarCode());
            $bin->setBarCode($updateBin->getBarCode());
        }

        if (!is_null($updateBin->getAisle()))
            $bin->setAisle($updateBin->getAisle());

        if (!is_null($updateBin->getSection()))
            $bin->setSection($updateBin->getSection());

        if (!is_null($updateBin->getRow()))
            $bin->setRow($updateBin->getRow());

        if (!is_null($updateBin->getCol()))
            $bin->setCol($updateBin->getCol());

        $this->binValidation->uniqueRackLocation($bin);

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