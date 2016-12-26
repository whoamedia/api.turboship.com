<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\Order;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class OrderRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Order[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['orders']);
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
        $qb->from('App\Models\OMS\Order', 'orders')
            ->leftJoin('orders.items', 'items', Query\Expr\Join::ON)
            ->join('orders.crmSource', 'crmSource', Query\Expr\Join::ON)
            ->join('orders.client', 'client', Query\Expr\Join::ON)
            ->join('client.organization', 'organization', Query\Expr\Join::ON)
            ->join('orders.status', 'status', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('orders.id', $query['ids']));

        if (!is_null(AU::get($query['itemIds'])))
            $qb->andWhere($qb->expr()->in('items.id', $query['itemIds']));

        if (!is_null(AU::get($query['crmSourceIds'])))
            $qb->andWhere($qb->expr()->in('crmSource.id', $query['crmSourceIds']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['statusIds'])))
            $qb->andWhere($qb->expr()->in('status.id', $query['statusIds']));

        if (!is_null(AU::get($query['isError'])))
            $qb->andWhere($qb->expr()->eq('status.isError', BU::toString($query['isError'])));

        if (!is_null(AU::get($query['externalIds'])))
        {
            $orX                    = $qb->expr()->orX();
            $externalIds            = explode(',', $query['externalIds']);
            foreach ($externalIds AS $externalId)
            {
                $orX->add($qb->expr()->eq('orders.externalId', $qb->expr()->literal($externalId)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['itemSkus'])))
        {
            $orX                    = $qb->expr()->orX();
            $itemSkus               = explode(',', $query['itemSkus']);
            foreach ($itemSkus AS $sku)
            {
                $orX->add($qb->expr()->eq('items.sku', $qb->expr()->literal($sku)));
            }
            $qb->andWhere($orX);
        }


        if (!is_null(AU::get($query['externalCreatedFrom'])))
            $qb->andWhere($qb->expr()->gte('orders.externalCreatedAt', $query['externalCreatedFrom']));

        $qb->orderBy('orders.id', 'ASC');

        return $qb;
    }

    /**
     * @param   array   $query
     * @return  string|null
     */
    public function getMaxExternalId ($query)
    {
        $qb                         = $this->_em->createQueryBuilder();
        $qb->select(['MAX(orders.externalId)']);
        $qb                         = $this->buildQueryConditions($qb, $query);

        $result                     = $qb->getQuery()->getResult();
        return $result[0][1];
    }


    /**
     * @param   int         $id
     * @return  Order|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}