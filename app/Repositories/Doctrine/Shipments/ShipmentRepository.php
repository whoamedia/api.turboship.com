<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Shipment;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class ShipmentRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Shipment[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['shipment', 'status', 'shipper', 'shippingContainer', 'postage', 'service', 'carrier', 'items', 'orderItem', 'orders', 'client', 'organization']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->orderBy(AU::get($query['orderBy'], 'shipment.id'), AU::get($query['direction'], 'ASC'));

        if ($ignorePagination)
            return $qb->getQuery()->getResult();
        else
            return $this->paginate($qb->getQuery(), $pagination['limit']);
    }

    /**
     * @param       array                   $query
     * @return      array
     */
    public function getLexicon ($query)
    {
        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select([
            'COUNT(orders.id) AS total',
            'shippingContainer.id AS shippingContainer_id', 'shippingContainer.name AS shippingContainer_name',
            'carrier.id AS carrier_id', 'carrier.name AS carrier_name',
            'service.id AS service_id', 'service.name AS service_name',
            'client.id AS client_id', 'client.name AS client_name',
            'status.id AS status_id', 'status.name AS status_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('shippingContainer');
        $qb->addGroupBy('carrier');
        $qb->addGroupBy('service');
        $qb->addGroupBy('client');
        $qb->addGroupBy('status');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'shippingContainer' =>  [],
            'carrier'           =>  [],
            'service'           =>  [],
            'client'            =>  [],
            'status'            =>  [],
        ];

        return $this->buildLexicon($lexicon, $result);
    }

    /**
     * @param       QueryBuilder            $qb
     * @param       []                      $query
     * @return      QueryBuilder
     */
    private function buildQueryConditions(QueryBuilder $qb, $query)
    {
        $qb->from('App\Models\Shipments\Shipment', 'shipment')
            ->join('shipment.status', 'status', Query\Expr\Join::ON)
            ->join('shipment.shipper', 'shipper', Query\Expr\Join::ON)
            ->leftJoin('shipment.shippingContainer', 'shippingContainer', Query\Expr\Join::ON)
            ->leftJoin('shipment.postage', 'postage', Query\Expr\Join::ON)
            ->leftJoin('shipment.service', 'service', Query\Expr\Join::ON)
            ->leftJoin('service.carrier', 'carrier', Query\Expr\Join::ON)
            ->leftJoin('shipment.items', 'items', Query\Expr\Join::ON)
            ->leftJoin('items.orderItem', 'orderItem', Query\Expr\Join::ON)
            ->leftJoin('orderItem.order', 'orders', Query\Expr\Join::ON)
            ->leftJoin('orders.client', 'client', Query\Expr\Join::ON)
            ->leftJoin('client.organization', 'organization', Query\Expr\Join::ON)
            ->leftJoin('shipment.toAddress', 'toAddress', Query\Expr\Join::ON)
            ->leftJoin('toAddress.country', 'toCountry', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('shipment.id', $query['ids']));

        if (!is_null(AU::get($query['shipperIds'])))
            $qb->andWhere($qb->expr()->in('shipper.id', $query['shipperIds']));

        if (!is_null(AU::get($query['shippingContainerIds'])))
            $qb->andWhere($qb->expr()->in('shippingContainer.id', $query['shippingContainerIds']));

        if (!is_null(AU::get($query['itemIds'])))
            $qb->andWhere($qb->expr()->in('items.id', $query['itemIds']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['orderIds'])))
            $qb->andWhere($qb->expr()->in('orders.id', $query['orderIds']));

        if (!is_null(AU::get($query['orderItemIds'])))
            $qb->andWhere($qb->expr()->in('orderItem.id', $query['orderItemIds']));

        if (!is_null(AU::get($query['serviceIds'])))
            $qb->andWhere($qb->expr()->in('service.id', $query['serviceIds']));

        if (!is_null(AU::get($query['carrierIds'])))
            $qb->andWhere($qb->expr()->in('carrier.id', $query['carrierIds']));

        if (!is_null(AU::get($query['statusIds'])))
            $qb->andWhere($qb->expr()->in('status.id', $query['statusIds']));

        if (!is_null(AU::get($query['trackingNumbers'])))
        {
            $orX                    = $qb->expr()->orX();
            $trackingNumbers               = explode(',', $query['trackingNumbers']);
            foreach ($trackingNumbers AS $trackingNumber)
            {
                $orX->add($qb->expr()->eq('postage.trackingNumber', $qb->expr()->literal($trackingNumber)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['toAddressCountryIds'])))
            $qb->andWhere($qb->expr()->in('toCountry.id', $query['toAddressCountryIds']));

        if (!is_null(AU::get($query['createdFrom'])))
        {
            $qb->andWhere($qb->expr()->gte('shipment.createdAt', ':createdFrom'));
            $qb->setParameter('createdFrom', $query['createdFrom'] . ' 00:00:00');
        }

        if (!is_null(AU::get($query['createdTo'])))
        {
            $qb->andWhere($qb->expr()->lte('shipment.createdAt', ':createdTo'));
            $qb->setParameter('createdTo', $query['createdTo'] . ' 23:59:59');
        }


        return $qb;
    }

    /**
     * @param   array   $query
     * @return  string|null
     */
    public function getMaxExternalId ($query)
    {
        $qb                         = $this->_em->createQueryBuilder();
        $qb->select(['MAX(shipment.externalId)']);
        $qb                         = $this->buildQueryConditions($qb, $query);

        $result                     = $qb->getQuery()->getResult();
        return $result[0][1];
    }

    /**
     * @param   int         $id
     * @return  Shipment|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}