<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegratedService;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class IntegratedServiceRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      IntegratedService[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['integratedService']);
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
        $qb->from('App\Models\Integrations\IntegratedService', 'integratedService')
            ->join('integratedService.integration', 'integration', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('integratedService.id', $query['ids']));

        if (!is_null(AU::get($query['integrationIds'])))
            $qb->andWhere($qb->expr()->in('integration.id', $query['integrationIds']));


        $qb->orderBy('integratedService.id', 'ASC');
        return $qb;
    }

    /**
     * @param   int     $id
     * @return  IntegratedService|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}