<?php

namespace App\Repositories\Doctrine\Locations;


use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use App\Models\Locations\Country;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;

class CountryRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Country[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['country']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        if ($ignorePagination)
            return $qb->getQuery()->getResult();
        else
            return $this->paginate($qb->getQuery(), $pagination['limit']);
    }

    /**
     * @param       QueryBuilder            $qb
     * @param       []                      $query
     * @return      QueryBuilder
     */
    private function buildQueryConditions(QueryBuilder $qb, $query)
    {
        $qb->from('App\Models\Locations\Country', 'country');

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('country.id', $query['ids']));

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                  = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->eq('country.name', $qb->expr()->literal($name)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['iso2s'])))
        {
            $orX                    = $qb->expr()->orX();
            $iso2s                  = explode(',', $query['iso2s']);
            foreach ($iso2s AS $iso2)
            {
                $orX->add($qb->expr()->eq('country.iso2', $qb->expr()->literal($iso2)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['iso3s'])))
        {
            $orX                    = $qb->expr()->orX();
            $iso3s                  = explode(',', $query['iso3s']);
            foreach ($iso3s AS $iso3)
            {
                $orX->add($qb->expr()->eq('country.iso3', $qb->expr()->literal($iso3)));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('country.id', 'ASC');
        return $qb;
    }


    /**
     * Gets a Country object by its id
     * @param       int                     $id                 Id to query against
     * @return      Country|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param       string                  $name               name of Country
     * @return      Country|null
     */
    public function getOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param       string                  $iso2               iso2 of Country
     * @return      Country|null
     */
    public function getOneByISO2($iso2)
    {
        return $this->findOneBy(['iso2' => $iso2]);
    }

    /**
     * @param       string                  $iso3               iso3 of Country
     * @return      Country|null
     */
    public function getOneByISO3($iso3)
    {
        return $this->findOneBy(['iso3' => $iso3]);
    }

}