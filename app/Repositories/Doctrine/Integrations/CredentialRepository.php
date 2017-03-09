<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\Credential;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class CredentialRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Credential[]|LengthAwarePaginator
     */
    public function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['credential', 'integrationCredential', 'integratedService']);
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
        $qb->from('App\Models\Integrations\Credential', 'credential')
            ->join('credential.integrationCredential', 'integrationCredential', Query\Expr\Join::ON)
            ->join('credential.integratedService', 'integratedService', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('credential.id', $query['ids']));

        if (!is_null(AU::get($query['integrationCredentialIds'])))
            $qb->andWhere($qb->expr()->in('integrationCredential.id', $query['integrationCredentialIds']));

        if (!is_null(AU::get($query['integratedServiceIds'])))
            $qb->andWhere($qb->expr()->in('integratedService.id', $query['integratedServiceIds']));


        $qb->orderBy('credential.id', 'ASC');
        return $qb;
    }

    /**
     * @param   int     $id
     * @return  Credential|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}