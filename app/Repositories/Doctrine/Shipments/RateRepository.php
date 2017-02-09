<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Rate;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class RateRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Rate[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['rate', 'shipment', 'shippingApiService', 'service', 'carrier']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addOrderBy(AU::get($query['orderBy'], 'rate.id'), AU::get($query['direction'], 'ASC'));

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
        $qb->from('App\Models\Shipments\Rate', 'rate')
            ->join('rate.shipment', 'shipment', Query\Expr\Join::ON)
            ->leftJoin('shipment.items', 'items', Query\Expr\Join::ON)
            ->leftJoin('items.orderItem', 'orderItem', Query\Expr\Join::ON)
            ->leftJoin('orderItem.order', 'orders', Query\Expr\Join::ON)
            ->leftJoin('orders.client', 'client', Query\Expr\Join::ON)
            ->leftJoin('client.organization', 'organization', Query\Expr\Join::ON)
            ->join('rate.shippingApiService', 'shippingApiService', Query\Expr\Join::ON)
            ->join('shippingApiService.service', 'service', Query\Expr\Join::ON)
            ->join('service.carrier', 'carrier', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('rate.id', $query['ids']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['shipmentIds'])))
            $qb->andWhere($qb->expr()->in('shipment.id', $query['shipmentIds']));

        if (!is_null(AU::get($query['shippingApiServiceIds'])))
            $qb->andWhere($qb->expr()->in('shippingApiService.id', $query['shippingApiServiceIds']));

        if (!is_null(AU::get($query['serviceIds'])))
            $qb->andWhere($qb->expr()->in('service.id', $query['serviceIds']));

        if (!is_null(AU::get($query['carrierIds'])))
            $qb->andWhere($qb->expr()->in('carrier.id', $query['carrierIds']));

        return $qb;
    }

    /**
     * @param   int         $id
     * @return  Rate|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}