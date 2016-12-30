<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ShippingApiService;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class ShippingApiServiceRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      ShippingApiService[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['shippingApiService']);
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
        $qb->from('App\Models\Integrations\ShippingApiService', 'shippingApiService')
            ->join('shippingApiService.service', 'service', Query\Expr\Join::ON)
            ->join('shippingApiService.shippingApiCarrier', 'shippingApiCarrier', Query\Expr\Join::ON)
            ->join('shippingApiCarrier.carrier', 'carrier', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('shippingApiService.id', $query['ids']));

        if (!is_null(AU::get($query['serviceIds'])))
            $qb->andWhere($qb->expr()->in('service.id', $query['serviceIds']));

        if (!is_null(AU::get($query['shippingApiCarrierIds'])))
            $qb->andWhere($qb->expr()->in('shippingApiCarrier.id', $query['shippingApiCarrierIds']));

        if (!is_null(AU::get($query['carrierIds'])))
            $qb->andWhere($qb->expr()->in('carrier.id', $query['carrierIds']));


        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                 = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->eq('shippingApiService.name', $qb->expr()->literal($name)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['shippingApiCarrierNames'])))
        {
            $orX                    = $qb->expr()->orX();
            $shippingApiCarrierNames= explode(',', $query['shippingApiCarrierNames']);
            foreach ($shippingApiCarrierNames AS $shippingApiCarrierName)
            {
                $orX->add($qb->expr()->eq('shippingApiCarrier.name', $qb->expr()->literal($shippingApiCarrierName)));
            }
            $qb->andWhere($orX);
        }


        $qb->orderBy('ShippingApiService.id', 'ASC');
        return $qb;
    }



    /**
     * @param   int     $id
     * @return  ShippingApiService|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}