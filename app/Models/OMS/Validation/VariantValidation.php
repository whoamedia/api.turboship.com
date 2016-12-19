<?php

namespace App\Models\OMS\Validation;


use App\Models\OMS\Variant;
use App\Repositories\Doctrine\OMS\VariantRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use EntityManager;

class VariantValidation
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;


    public function __construct()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
    }

    /**
     * @param   int $id
     * @return  Variant
     * @throws  NotFoundHttpException
     */
    public function idExists ($id)
    {
        $variant                        = $this->variantRepo->getOneById($id);

        if (is_null($variant))
            throw new NotFoundHttpException('Variant not found');

        return $variant;
    }

    /**
     * @param   Variant     $variant
     * @throws  BadRequestHttpException
     * @return  bool
     */
    public function validateUniqueClientSku ($variant)
    {
        $variantQuery   = [
            'clientIds'     => $variant->getClient()->getId(),
            'skus'          => $variant->getSku(),
        ];

        $variantResult                  = $this->variantRepo->where($variantQuery);

        if (sizeof($variantResult) == 0)    // sku does not exists for the client
            return true;
        else if (sizeof($variantResult) == 1)   // sku exists for the client. Ensure that the variants are the same
        {
            if ($variantResult[0]->getId() == $variant->getId())
                return true;
            else
                throw new BadRequestHttpException('Variant sku ' . $variant->getSku() . ' already exists');
        }
        else
            throw new BadRequestHttpException('Variant sku ' . $variant->getSku() . ' already exists');
    }


}