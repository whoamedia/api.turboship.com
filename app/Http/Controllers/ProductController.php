<?php

namespace App\Http\Controllers;


use App\Http\Requests\Products\GetProducts;
use App\Repositories\Doctrine\OMS\ProductRepository;
use Illuminate\Http\Request;
use EntityManager;

class ProductController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepo;


    public function __construct ()
    {
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
    }


    public function index (Request $request)
    {
        $getProducts                    = new GetProducts($request->input());
        $getProducts->setOrganizationIds(\Auth::getUser()->getOrganization()->getId());
        $getProducts->validate();
        $getProducts->clean();

        $query                          = $getProducts->jsonSerialize();
        $results                        = $this->productRepo->where($query, false);

        return response($results);
    }




}