<?php

namespace App\Repositories\Doctrine\Hardware;


use App\Models\Hardware\ShippingStation;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class ShippingStationRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      ShippingStation[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->select(['shippingStation', 'organization', 'printer']);
        $qb->addOrderBy(AU::get($query['orderBy'], 'shippingStation.id'), AU::get($query['direction'], 'ASC'));

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
        $qb->from('App\Models\Hardware\ShippingStation', 'shippingStation')
            ->join('shippingStation.organization', 'organization', Query\Expr\Join::ON)
            ->leftJoin('shippingStation.printer', 'printer', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('shippingStation.id', $query['ids']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['printerIds'])))
            $qb->andWhere($qb->expr()->in('printer.id', $query['printerIds']));

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                  = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->LIKE('shippingStation.name', $qb->expr()->literal('%' . trim($name) . '%')));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }


    /**
     * @param   int         $id
     * @return  ShippingStation|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}