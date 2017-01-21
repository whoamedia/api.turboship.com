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
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   parent::buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['orders']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->orderBy('orders.id', 'ASC');

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
            'COUNT(DISTINCT orders.id) AS total',
            'source.id AS source_id', 'source.name AS source_name',
            'client.id AS client_id', 'client.name AS client_name',
            'status.id AS status_id', 'status.name AS status_name',
            'shipmentStatus.id AS shipmentStatus_id', 'shipmentStatus.name AS shipmentStatus_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('source');
        $qb->addGroupBy('client');
        $qb->addGroupBy('status');
        $qb->addGroupBy('shipmentStatus');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'source'            =>  [],
            'client'            =>  [],
            'status'            =>  [],
            'shipmentStatus'    =>  [],
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
        $qb->from('App\Models\OMS\Order', 'orders')
            ->leftJoin('orders.items', 'items', Query\Expr\Join::ON)
            ->join('orders.source', 'source', Query\Expr\Join::ON)
            ->join('orders.client', 'client', Query\Expr\Join::ON)
            ->join('client.organization', 'organization', Query\Expr\Join::ON)
            ->join('orders.status', 'status', Query\Expr\Join::ON)
            ->join('orders.shipmentStatus', 'shipmentStatus', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('orders.id', $query['ids']));

        if (!is_null(AU::get($query['itemIds'])))
            $qb->andWhere($qb->expr()->in('items.id', $query['itemIds']));

        if (!is_null(AU::get($query['sourceIds'])))
            $qb->andWhere($qb->expr()->in('source.id', $query['sourceIds']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['statusIds'])))
            $qb->andWhere($qb->expr()->in('status.id', $query['statusIds']));

        if (!is_null(AU::get($query['shipmentStatusIds'])))
            $qb->andWhere($qb->expr()->in('shipmentStatus.id', $query['shipmentStatusIds']));

        if (!is_null(AU::get($query['isError'])))
            $qb->andWhere($qb->expr()->eq('status.isError', BU::toString($query['isError'])));

        if (!is_null(AU::get($query['receivedFrom'])))
        {
            $qb->andWhere($qb->expr()->gte('orders.createdAt', ':receivedFrom'));
            $qb->setParameter('receivedFrom', $query['receivedFrom']);
        }

        if (!is_null(AU::get($query['receivedTo'])))
        {
            $qb->andWhere($qb->expr()->gte('orders.createdAt', ':receivedTo'));
            $qb->setParameter('receivedTo', $query['receivedTo']);
        }

        if (!is_null(AU::get($query['externalCreatedFrom'])))
        {
            $qb->andWhere($qb->expr()->gte('orders.externalCreatedAt', ':externalCreatedFrom'));
            $qb->setParameter('externalCreatedFrom', $query['externalCreatedFrom']);
        }

        if (!is_null(AU::get($query['externalCreatedTo'])))
        {
            $qb->andWhere($qb->expr()->gte('orders.externalCreatedAt', ':externalCreatedTo'));
            $qb->setParameter('externalCreatedTo', $query['externalCreatedTo']);
        }

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