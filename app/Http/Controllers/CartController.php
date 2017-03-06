<?php

namespace App\Http\Controllers;


use App\Http\Requests\Carts\CreateCart;
use App\Http\Requests\Carts\GetCarts;
use App\Http\Requests\Carts\ShowCart;
use App\Http\Requests\Carts\UpdateCart;
use App\Models\WMS\Cart;
use App\Models\WMS\Validation\CartValidation;
use App\Repositories\Doctrine\WMS\CartRepository;
use EntityManager;
use Illuminate\Http\Request;

class CartController extends BaseAuthController
{

    /**
     * @var CartRepository
     */
    private $cartRepo;


    public function __construct ()
    {
        $this->cartRepo                 = EntityManager::getRepository('App\Models\WMS\Cart');
    }


    public function index (Request $request)
    {
        $getCarts                       = new GetCarts($request->input());
        $getCarts->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getCarts->jsonSerialize();

        $results                        = $this->cartRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $cart                           = $this->getCartFromRoute($request->route('id'));
        return response($cart);
    }

    public function store (Request $request)
    {
        $createCart                     = new CreateCart($request->input());
        $createCart->validate();
        $createCart->clean();

        $cartValidation                 = new CartValidation();
        $cartValidation->barCodeDoesNotExist(parent::getAuthUserOrganization()->getId(), $createCart->getBarCode());

        $cart                           = new Cart();
        $cart->setOrganization(parent::getAuthUserOrganization());
        $cart->setBarCode($createCart->getBarCode());

        $this->cartRepo->saveAndCommit($cart);
        return response($cart, 201);
    }

    public function update (Request $request)
    {
        $updateCart                     = new UpdateCart($request->input());
        $updateCart->setId($request->route('id'));
        $updateCart->validate();
        $updateCart->clean();

        $cart                           = $this->getCartFromRoute($updateCart->getId());
        if (!is_null($updateCart->getBarCode()))
        {
            if ($cart->getBarCode() != $updateCart->getBarCode())
            {
                $cartValidation                 = new CartValidation();
                $cartValidation->barCodeDoesNotExist(parent::getAuthUserOrganization()->getId(), $updateCart->getBarCode());
                $cart->setBarCode($updateCart->getBarCode());
            }
        }

        $this->cartRepo->saveAndCommit($cart);
        return response($cart);
    }

    /**
     * @param   int     $id
     * @return  Cart
     */
    private function getCartFromRoute ($id)
    {
        $showCart                       = new ShowCart();
        $showCart->setId($id);
        $showCart->validate();
        $showCart->clean();

        $cartValidation                 = new CartValidation();
        $cart                           = $cartValidation->idExists($showCart->getId());
        return $cart;
    }
}