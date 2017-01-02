<?php

namespace App\Repositories\Doctrine\Locations;


use App\Models\Locations\SubdivisionType;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;

class SubdivisionTypeRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      SubdivisionType[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['subdivisionType']);
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
        $qb->from('App\Models\Locations\SubdivisionType', 'subdivisionType');

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('subdivisionType.id', $query['ids']));

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                  = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->eq('subdivisionType.name', $qb->expr()->literal($name)));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('subdivisionType.id', 'ASC');
        return $qb;
    }


    /**
     * @param   int         $id
     * @return  SubdivisionType|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}