<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Postage;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class PostageRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Postage[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['postage', 'rate', 'integratedShippingApi', 'shippingApiService', 'service', 'carrier', 'shipment']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addOrderBy(AU::get($query['orderBy'], 'postage.id'), AU::get($query['direction'], 'ASC'));

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
            'COUNT(DISTINCT postage.id) AS total',
            'shipper.id AS shipper_id', 'shipper.name AS shipper_name',
            'carrier.id AS carrier_id', 'carrier.name AS carrier_name',
            'service.id AS service_id', 'service.name AS service_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('shipper');
        $qb->addGroupBy('carrier');
        $qb->addGroupBy('service');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'shipper'           =>  [
                'displayField'  => 'Shippers',
                'searchField'   => 'shipperIds',
                'type'          => 'integer',
                'values'        => [],
            ],
            'carrier'           =>  [
                'displayField'  => 'Carriers',
                'searchField'   => 'carrierIds',
                'type'          => 'integer',
                'values'        => [],
            ],
            'service'           =>  [
                'displayField'  => 'Services',
                'searchField'   => 'serviceIds',
                'type'          => 'integer',
                'values'        => [],
            ],
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
        $qb->from('App\Models\Shipments\Postage', 'postage')
            ->join('postage.rate', 'rate', Query\Expr\Join::ON)
            ->join('rate.integratedShippingApi', 'integratedShippingApi', Query\Expr\Join::ON)
            ->join('integratedShippingApi.shipper', 'shipper', Query\Expr\Join::ON)
            ->join('rate.shippingApiService', 'shippingApiService', Query\Expr\Join::ON)
            ->join('shippingApiService.service', 'service', Query\Expr\Join::ON)
            ->join('service.carrier', 'carrier', Query\Expr\Join::ON)
            ->join('postage.shipment', 'shipment', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('postage.id', $query['ids']));

        if (!is_null(AU::get($query['rateIds'])))
            $qb->andWhere($qb->expr()->in('rate.id', $query['rateIds']));

        if (!is_null(AU::get($query['shipmentIds'])))
            $qb->andWhere($qb->expr()->in('shipment.id', $query['shipmentIds']));

        if (!is_null(AU::get($query['integratedShippingApiIds'])))
            $qb->andWhere($qb->expr()->in('integratedShippingApi.id', $query['integratedShippingApiIds']));

        if (!is_null(AU::get($query['shippingApiServiceIds'])))
            $qb->andWhere($qb->expr()->in('shippingApiService.id', $query['shippingApiServiceIds']));

        if (!is_null(AU::get($query['serviceIds'])))
            $qb->andWhere($qb->expr()->in('service.id', $query['serviceIds']));

        if (!is_null(AU::get($query['carrierIds'])))
            $qb->andWhere($qb->expr()->in('carrier.id', $query['carrierIds']));

        if (!is_null(AU::get($query['externalIds'])))
        {
            $orX                    = $qb->expr()->orX();
            $externalIds            = explode(',', $query['externalIds']);
            foreach ($externalIds AS $externalId)
            {
                $orX->add($qb->expr()->eq('postage.externalId', $qb->expr()->literal($externalId)));
            }
            $qb->andWhere($orX);
        }

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

        if (!is_null(AU::get($query['isVoided'])))
        {
            if ($query['isVoided'])
                $qb->andWhere($qb->expr()->isNotNull('rate.voidedAt'));
            else
                $qb->andWhere($qb->expr()->isNull('rate.voidedAt'));
        }

        return $qb;
    }

    /**
     * @param   int         $id
     * @return  Postage|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}