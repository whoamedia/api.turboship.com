<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegratedShippingApi;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class IntegratedShippingApiRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      IntegratedShippingApi[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['integratedShippingApi', 'integration', 'shipper', 'organization']);
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
        $qb->from('App\Models\Integrations\IntegratedShippingApi', 'integratedShippingApi')
            ->join('integratedShippingApi.integration', 'integration', Query\Expr\Join::ON)
            ->join('integratedShippingApi.shipper', 'shipper', Query\Expr\Join::ON)
            ->join('shipper.organization', 'organization', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('integratedShippingApi.id', $query['ids']));

        if (!is_null(AU::get($query['integrationIds'])))
            $qb->andWhere($qb->expr()->in('integration.id', $query['integrationIds']));

        if (!is_null(AU::get($query['shipperIds'])))
            $qb->andWhere($qb->expr()->in('shipper.id', $query['shipperIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));


        $qb->orderBy('integratedShippingApi.id', 'ASC');
        return $qb;
    }

    /**
     * @param   int     $id
     * @return  IntegratedShippingApi|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}