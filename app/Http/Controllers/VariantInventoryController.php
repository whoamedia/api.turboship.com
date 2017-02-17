<?php

namespace App\Http\Controllers;


use App\Http\Requests\Inventory\GetVariantInventory;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use Illuminate\Http\Request;
use EntityManager;

class VariantInventoryController extends BaseAuthController
{

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;


    public function __construct()
    {
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
    }

    public function index (Request $request)
    {
        $getVariantInventory            = new GetVariantInventory($request->input());
        $getVariantInventory->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariantInventory->validate();
        $getVariantInventory->clean();

        $query                          = $getVariantInventory->jsonSerialize();
        $results                        = $this->variantInventoryRepo->where($query, false);

        if ($getVariantInventory->getGroupVariants())
        {
            $newResults                 = new \Illuminate\Support\Collection();
            foreach ($results->getCollection()->toArray() AS $variantInventory)
            {
                $total                  = $variantInventory['total'];
                $item                   = $variantInventory[0]->jsonSerialize();
                $item['total']          = $total;
                $newResults->push($item);
            }
            $results->setCollection($newResults);
        }
        return response($results);
    }
}