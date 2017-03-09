<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\PickTote;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class PickToteRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      PickTote[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['pickTote']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addOrderBy(AU::get($query['orderBy'], 'pickTote.id'), AU::get($query['direction'], 'ASC'));

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
        $qb->from('App\Models\WMS\PickTote', 'pickTote')
            ->leftJoin('pickTote.tote', 'tote', Query\Expr\Join::ON)
            ->leftJoin('pickTote.shipment', 'shipment', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('pickTote.id', $query['ids']));

        if (!is_null(AU::get($query['toteIds'])))
            $qb->andWhere($qb->expr()->in('tote.id', $query['toteIds']));

        if (!is_null(AU::get($query['shipmentIds'])))
            $qb->andWhere($qb->expr()->in('shipment.id', $query['shipmentIds']));


        return $qb;
    }

    /**
     * @param   int         $id
     * @return  PickTote|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}