<?php

namespace App\Http\Controllers;


use App\Http\Requests\Products\GetProducts;
use App\Http\Requests\Products\ShowProduct;
use App\Models\OMS\Product;
use App\Repositories\Doctrine\OMS\ProductRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function show (Request $request)
    {
        $product                        = $this->getProductFromRoute($request->route('id'));
        return response($product);
    }

    public function getAliases (Request $request)
    {
        $product                        = $this->getProductFromRoute($request->route('id'));
        return response($product->getAliases());
    }

    public function getImages (Request $request)
    {
        $product                        = $this->getProductFromRoute($request->route('id'));
        return response($product->getImages());
    }

    public function getVariants (Request $request)
    {
        $product                        = $this->getProductFromRoute($request->route('id'));
        return response($product->getVariants());
    }


    /**
     * @param   int     $id
     * @return  Product
     */
    private function getProductFromRoute ($id)
    {
        $showProduct                    = new ShowProduct();
        $showProduct->setId($id);
        $showProduct->validate();
        $showProduct->clean();

        $product                        = $this->productRepo->getOneById($showProduct->getId());
        if (is_null($product))
            throw new NotFoundHttpException('Product not found');

        return $product;
    }

}