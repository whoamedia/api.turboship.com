<?php

namespace App\Http\Controllers;


use App\Http\Requests\PortableBins\CreatePortableBin;
use App\Http\Requests\PortableBins\CreatePortableBinToBinTransfer;
use App\Http\Requests\PortableBins\GetPortableBins;
use App\Http\Requests\PortableBins\ShowPortableBin;
use App\Http\Requests\PortableBins\UpdatePortableBin;
use App\Jobs\Inventory\ReadyInventoryAddedJob;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\WMS\PortableBin;
use App\Models\WMS\Validation\BinValidation;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Services\InventoryService;
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
     * @var VariantRepository
     */
    private $variantRepo;

    public function __construct ()
    {
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
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

        $query      = [
            'inventoryLocationIds'      => $portableBin->getId(),
            'limit'                     => 20,
            'page'                      => 1,
        ];
        $results                        = $this->portableBinRepo->where($query, false);
        return response($results);
    }

    public function transferToBin (Request $request)
    {
        $createPortableBinToBinTransfer = new CreatePortableBinToBinTransfer($request->input());
        $createPortableBinToBinTransfer->setId($request->route('id'));
        $createPortableBinToBinTransfer->setBinId($request->route('binId'));
        $createPortableBinToBinTransfer->validate();
        $createPortableBinToBinTransfer->clean();

        $portableBin                    = $this->getPortableBinFromRoute($createPortableBinToBinTransfer->getId());

        $binValidation                  = new BinValidation();
        $bin                            = $binValidation->idExists($createPortableBinToBinTransfer->getBinId());

        $variantValidation              = new VariantValidation();
        $variant                        = $variantValidation->idExists($createPortableBinToBinTransfer->getVariantId());

        $staff                          = parent::getAuthStaff();
        $quantity                       = $createPortableBinToBinTransfer->getQuantity();
        $inventoryService               = new InventoryService();
        $inventoryService->transferVariantInventoryToBin($portableBin, $bin, $variant, $staff, $quantity);

        $this->variantRepo->saveAndCommit($variant);

        $job                            = (new ReadyInventoryAddedJob($variant->getId()))->onQueue('shipmentReadyInventory')->delay(config('turboship.variants.readyInventoryDelay'));
        $this->dispatch($job);
        return response($portableBin);
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