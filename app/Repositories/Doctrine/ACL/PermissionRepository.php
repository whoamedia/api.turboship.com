<?php

namespace App\Repositories\Doctrine\ACL;


use App\Models\ACL\Permission;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class PermissionRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Permission[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['permission']);
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->addOrderBy(AU::get($query['orderBy'], 'permission.id'), AU::get($query['direction'], 'ASC'));

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
            'COUNT(DISTINCT permission.id) AS total',
            'permission.entity AS entity_id', 'permission.entity AS entity_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('permission.entity');
        $qb->addGroupBy('permission.name');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'entity'         =>  [
                'displayField'  => 'Entities',
                'searchField'   => 'entities',
                'type'          => 'string',
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
        $qb->from('App\Models\ACL\Permission', 'permission');

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('permission.id', $query['ids']));


        if (!is_null(AU::get($query['entities'])))
        {
            $orX                    = $qb->expr()->orX();
            $entities                   = explode(',', $query['entities']);
            foreach ($entities AS $entity)
            {
                $orX->add($qb->expr()->eq('permission.entity', $qb->expr()->literal($entity)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                   = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->eq('permission.name', $qb->expr()->literal($name)));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }


    /**
     * @param   int     $id
     * @return  Permission|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}