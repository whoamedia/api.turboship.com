<?php

namespace App\Http\Controllers;


use App\Http\Requests\Variants\GetVariants;
use App\Repositories\Doctrine\OMS\VariantRepository;
use EntityManager;
use Illuminate\Http\Request;

class VariantController extends BaseAuthController
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;


    public function __construct ()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
    }


    public function index (Request $request)
    {
        $getVariants                    = new GetVariants($request->input());
        $getVariants->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariants->validate();
        $getVariants->clean();

        $query                          = $getVariants->jsonSerialize();
        $results                        = $this->variantRepo->where($query, false);
        return response($results);
    }

    public function getLexicon (Request $request)
    {
        $getVariants                    = new GetVariants($request->input());
        $getVariants->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariants->validate();
        $getVariants->clean();

        $query                          = $getVariants->jsonSerialize();
        $results                        = $this->variantRepo->getLexicon($query);
        return response($results);
    }
}