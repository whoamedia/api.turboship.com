<?php

namespace App\Models\Support\Validation;

use App\Models\Support\Source;
use App\Repositories\Doctrine\Support\SourceRepository;
use App\Utilities\SourceUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SourceValidation
{

    /**
     * @var SourceRepository
     */
    private $sourceRepo;


    public function __construct()
    {
        $this->sourceRepo                   = EntityManager::getRepository('App\Models\Support\Source');
    }

    /**
     * @param   int     $id
     * @return  Source
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $source                             = $this->sourceRepo->getOneById($id);

        if (is_null($source))
            throw new NotFoundHttpException('Source not found');

        return $source;
    }

    /**
     * @return \App\Models\Support\Source
     */
    public function getInternal ()
    {
        return $this->sourceRepo->getOneById(SourceUtility::INTERNAL_ID);
    }

    /**
     * @return \App\Models\Support\Source
     */
    public function getShopify ()
    {
        return $this->sourceRepo->getOneById(SourceUtility::SHOPIFY_ID);
    }

}