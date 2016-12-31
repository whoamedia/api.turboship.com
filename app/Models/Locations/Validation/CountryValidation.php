<?php

namespace App\Models\Locations\Validation;


use App\Models\Locations\Country;
use App\Repositories\Doctrine\Locations\CountryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use EntityManager;

class CountryValidation
{

    /**
     * @var CountryRepository
     */
    private $countryRepo;


    /**
     * CountryValidation constructor.
     * @param CountryRepository|null $countryRepo
     */
    public function __construct($countryRepo = null)
    {
        if (is_null($countryRepo))
            $countryRepo                = EntityManager::getRepository('App\Models\Locations\Country');

        $this->countryRepo              = $countryRepo;
    }

    /**
     * @param   int     $id
     * @return  Country
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $entity                     = $this->countryRepo->getOneById($id);
        if (is_null($entity))
            throw new NotFoundHttpException('Country id does not exist');
        return $entity;
    }

    /**
     * @param   string  $name
     * @return  Country
     * @throws  NotFoundHttpException
     */
    public function nameExists($name)
    {
        $entity                     = $this->countryRepo->getOneByName($name);
        if (is_null($entity))
            throw new NotFoundHttpException('Country name does not exist');
        else
            return $entity;
    }

    /**
     * @param   string  $iso2
     * @return  Country
     * @throws  NotFoundHttpException
     */
    public function iso2Exists($iso2)
    {
        $entity                     = $this->countryRepo->getOneByISO2($iso2);
        if (is_null($entity))
            throw new NotFoundHttpException('Country iso2 does not exist');
        else
            return $entity;
    }

}