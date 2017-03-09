<?php

namespace App\Models\Support\Validation;


use App\Models\Support\Image;
use App\Repositories\Doctrine\Support\ImageRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageValidation
{

    /**
     * @var ImageRepository
     */
    private $imageRepo;


    public function __construct()
    {
        $this->imageRepo                    = EntityManager::getRepository('App\Models\Support\Image');
    }

    /**
     * @param   int     $id
     * @return  Image
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $image                              = $this->imageRepo->getOneById($id);

        if (is_null($image))
            throw new NotFoundHttpException('Image not found');

        return $image;
    }

}