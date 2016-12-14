<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\OrderStatus;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class OrderStatusRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      OrderStatus[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['orderStatus']);
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
        $qb->from('App\Models\OMS\OrderStatus', 'orderStatus');

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('orderStatus.id', $query['ids']));

        if (!is_null(AU::get($query['names'])))
        {
            $orX                    = $qb->expr()->orX();
            $names                  = explode(',', $query['names']);
            foreach ($names AS $name)
            {
                $orX->add($qb->expr()->LIKE('orderStatus.name', $qb->expr()->literal('%' . trim($name) . '%')));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['isError'])))
        {
            $qb->andWhere($qb->expr()->eq('orderStatus.isError', BU::toString($query['isError'])));
        }

        $qb->orderBy('orderStatus.id', 'ASC');
        return $qb;
    }


    /**
     * @param   int         $id
     * @return  OrderStatus|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param   string      $name
     * @return  OrderStatus|null
     */
    public function getOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

}