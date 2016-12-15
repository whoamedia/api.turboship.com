<?php

namespace App\Repositories\Doctrine\Locations;


use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use App\Models\Locations\Subdivision;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Illuminate\Pagination\LengthAwarePaginator;
use jamesvweston\Utilities\InputUtil;
use LaravelDoctrine\ORM\Pagination\Paginatable;

class SubdivisionRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Subdivision[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['subdivision']);
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
        $qb->from('App\Models\Locations\Subdivision', 'subdivision')
            ->join('subdivision.country', 'country', Query\Expr\Join::ON)
            ->join('subdivision.subdivisionType', 'subdivisionType', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('subdivision.id', $query['ids']));

        if (!is_null(AU::get($query['countryIds'])))
            $qb->andWhere($qb->expr()->in('country.id', $query['countryIds']));

        if (!is_null(AU::get($query['subdivisionTypeIds'])))
            $qb->andWhere($qb->expr()->in('subdivisionType.id', $query['subdivisionTypeIds']));

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                  = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->eq('subdivision.name', $qb->expr()->literal($name)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['symbols'])))
        {
            $orX                    = $qb->expr()->orX();
            $symbols                = explode(',', $query['symbols']);
            foreach ($symbols AS $symbol)
            {
                $orX->add($qb->expr()->eq('subdivision.symbol', $qb->expr()->literal($symbol)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['localSymbols'])))
        {
            $orX                    = $qb->expr()->orX();
            $localSymbols           = explode(',', $query['localSymbols']);
            foreach ($localSymbols AS $localSymbol)
            {
                $orX->add($qb->expr()->eq('subdivision.localSymbol', $qb->expr()->literal($localSymbol)));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('subdivision.id', 'ASC');
        return $qb;
    }


    /**
     * @param   int         $id
     * @return  Subdivision|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param       string  $symbol
     * @return      Subdivision|null
     */
    public function getOneBySymbol($symbol)
    {
        return $this->findOneBy(['symbol' => $symbol]);
    }

    /**
     * @param   mixed           $param
     * @param   string|null     $countryISO2
     * @return  Subdivision|null
     */
    public function getOneByWildCard ($param, $countryISO2 = null)
    {
        if (!is_null(InputUtil::getInt($param)))
            return $this->getOneById(InputUtil::getInt($param));
        else if (!is_null($countryISO2))
            return $this->getOneBySymbol($countryISO2 . '-' . $param);
        else
            return null;

    }
}