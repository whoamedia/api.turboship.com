<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\ShipmentItem;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class ShipmentItemRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      ShipmentItem[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['shipmentItem']);
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->orderBy('shipmentItem.id', 'ASC');

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
        $qb->from('App\Models\Shipments\ShipmentItem', 'shipmentItem')
            ->join('shipmentItem.shipment', 'shipment', Query\Expr\Join::ON)
            ->join('shipment.status', 'status', Query\Expr\Join::ON)
            ->join('shipmentItem.orderItem', 'orderItem', Query\Expr\Join::ON)
            ->leftJoin('orderItem.variant', 'variant', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('shipmentItem.id', $query['ids']));

        if (!is_null(AU::get($query['shipmentIds'])))
            $qb->andWhere($qb->expr()->in('shipment.id', $query['shipmentIds']));

        if (!is_null(AU::get($query['statusIds'])))
            $qb->andWhere($qb->expr()->in('status.id', $query['statusIds']));

        if (!is_null(AU::get($query['orderItemIds'])))
            $qb->andWhere($qb->expr()->in('orderItem.id', $query['orderItemIds']));

        if (!is_null(AU::get($query['variantIds'])))
            $qb->andWhere($qb->expr()->in('variant.id', $query['variantIds']));

        if (!is_null(AU::get($query['insufficientInventory'])))
        {
            $qb->andWhere($qb->expr()->lt('shipmentItem.quantityReserved', 'shipmentItem.quantity'));
        }

        return $qb;
    }

    /**
     * @param   int         $id
     * @return  ShipmentItem|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}