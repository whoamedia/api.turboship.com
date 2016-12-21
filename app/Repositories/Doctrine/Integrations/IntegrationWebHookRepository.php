<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegrationWebHook;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class IntegrationWebHookRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      IntegrationWebHook[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['integrationWebHook']);
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
        $qb->from('App\Models\Integrations\IntegrationWebHook', 'integrationWebHook')
            ->join('integrationWebHook.integration', 'integration', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('integrationWebHook.id', $query['ids']));

        if (!is_null(AU::get($query['integrationIds'])))
            $qb->andWhere($qb->expr()->in('integration.id', $query['integrationIds']));

        if (!is_null(AU::get($query['topics'])))
        {
            $orX                    = $qb->expr()->orX();
            $topics                 = explode(',', $query['topics']);
            foreach ($topics AS $topic)
            {
                $orX->add($qb->expr()->eq('integrationWebHook.topic', $qb->expr()->literal($topic)));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('integrationWebHook.id', 'ASC');
        return $qb;
    }

    /**
     * @param   int     $id
     * @return  IntegrationWebHook|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}