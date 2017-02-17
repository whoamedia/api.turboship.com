<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\Cart;
use App\Repositories\Doctrine\WMS\CartRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartValidation
{

    /**
     * @var CartRepository
     */
    private $cartRepo;


    public function __construct ()
    {
        $this->cartRepo                  = EntityManager::getRepository('App\Models\WMS\Cart');
    }


    /**
     * @param   int     $id
     * @return  Cart
     */
    public function idExists ($id)
    {
        $cart                            = $this->cartRepo->getOneById($id);
        if (is_null($cart))
            throw new NotFoundHttpException('Cart not found');

        return $cart;
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  Cart
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->cartRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('Cart not found');

        return $results[0];
    }

}