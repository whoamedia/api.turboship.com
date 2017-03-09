<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\OrderItem;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class OrderItemRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      OrderItem[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['orderItem', 'variant', 'orders', 'source', 'client', 'status', 'shipmentStatus']);
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->orderBy('orderItem.id', 'ASC');

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
        $qb->from('App\Models\OMS\OrderItem', 'orderItem')
            ->leftJoin('orderItem.variant', 'variant', Query\Expr\Join::ON)
            ->join('orderItem.order', 'orders', Query\Expr\Join::ON)
            ->join('orders.source', 'source', Query\Expr\Join::ON)
            ->join('orders.client', 'client', Query\Expr\Join::ON)
            ->join('orders.status', 'status', Query\Expr\Join::ON)
            ->join('orders.shipmentStatus', 'shipmentStatus', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('orderItem.id', $query['ids']));

        if (!is_null(AU::get($query['orderIds'])))
            $qb->andWhere($qb->expr()->in('orders.id', $query['orderIds']));

        if (!is_null(AU::get($query['sourceIds'])))
            $qb->andWhere($qb->expr()->in('source.id', $query['sourceIds']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['statusIds'])))
            $qb->andWhere($qb->expr()->in('status.id', $query['statusIds']));

        if (!is_null(AU::get($query['shipmentStatusIds'])))
            $qb->andWhere($qb->expr()->in('shipmentStatus.id', $query['shipmentStatusIds']));

        if (!is_null(AU::get($query['isError'])))
        {
            $qb->andWhere($qb->expr()->isNull('orderItem.variant'));
        }

        if (!is_null(AU::get($query['externalIds'])))
        {
            $orX                    = $qb->expr()->orX();
            $externalIds            = explode(',', $query['externalIds']);
            foreach ($externalIds AS $externalId)
            {
                $orX->add($qb->expr()->eq('orderItem.externalId', $qb->expr()->literal($externalId)));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }

    /**
     * @param   string|int      $clientIds
     * @param   string|int      $sourceIds
     * @return  string[]
     */
    public function getPendingExternalProductIds ($clientIds, $sourceIds)
    {
        $query  = [
            'clientIds'             => $clientIds,
            'sourceIds'             => $sourceIds,
            'isError'               => true,
        ];

        $qb                         = $this->_em->createQueryBuilder();
        $qb->select(['distinct orderItem.externalProductId']);
        $qb                         = $this->buildQueryConditions($qb, $query);
        $orderItemResults           = $qb->getQuery()->getResult();

        $results                    = [];
        foreach ($orderItemResults AS $orderItem)
            $results[]              = $orderItem['externalProductId'];

        return $results;
    }


    /**
     * @param   int         $id
     * @return  OrderItem|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}