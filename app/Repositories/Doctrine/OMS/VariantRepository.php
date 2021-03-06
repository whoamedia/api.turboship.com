<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\Variant;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class VariantRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Variant[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['variant']);
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
        $qb->from('App\Models\OMS\Variant', 'variant')
            ->join('variant.client', 'client', Query\Expr\Join::ON)
            ->join('variant.product', 'product', Query\Expr\Join::ON)
            ->join('variant.source', 'source', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('variant.id', $query['ids']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['productIds'])))
            $qb->andWhere($qb->expr()->in('product.id', $query['productIds']));

        if (!is_null(AU::get($query['sourceIds'])))
            $qb->andWhere($qb->expr()->in('source.id', $query['sourceIds']));

        if (!is_null(AU::get($query['externalIds'])))
        {
            $orX                    = $qb->expr()->orX();
            $externalIds            = explode(',', $query['externalIds']);
            foreach ($externalIds AS $externalId)
            {
                $orX->add($qb->expr()->eq('variant.externalId', $qb->expr()->literal($externalId)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['skus'])))
        {
            $orX                    = $qb->expr()->orX();
            $skus                   = explode(',', $query['skus']);
            foreach ($skus AS $sku)
            {
                $orX->add($qb->expr()->eq('variant.sku', $qb->expr()->literal(trim($sku))));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('variant.id', 'ASC');
        return $qb;
    }


    /**
     * @param   int         $id
     * @return  Variant|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}