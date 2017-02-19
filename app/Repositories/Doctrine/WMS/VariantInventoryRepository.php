<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use jamesvweston\Utilities\BooleanUtil;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class VariantInventoryRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      VariantInventory[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['variantInventory', 'variant', 'product', 'client', 'organization', 'inventoryLocation']);

        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->addOrderBy(AU::get($query['orderBy'], 'variantInventory.id'), AU::get($query['direction'], 'ASC'));

        if (!is_null(AU::get($query['groupedReport'])))
        {
            if (!is_null(AU::get($query['groupedReport'])) && BooleanUtil::isTrue($query['groupedReport']))
            {
                $qb->addSelect('count(variantInventory) AS total');
                $qb->groupBy('variant');

                $results            = $qb->getQuery()->getResult();
                $newResults         = [];
                foreach ($results AS $item)
                {
                    $newResults[]   = array_merge($item[0]->jsonSerialize(), ['total' => intval($item['total'])]);
                }
                return $newResults;
            }
        }
        if (!is_null(AU::get($query['inventoryLocationReport'])))
        {
            if (BooleanUtil::isTrue($query['inventoryLocationReport']))
            {
                $qb->addSelect('count(variantInventory) AS total');
                $qb->groupBy('inventoryLocation');

                $results            = $qb->getQuery()->getResult();
                $newResults         = [];
                foreach ($results AS $item)
                {
                    $newResults[]   = array_merge($item[0]->jsonSerialize(), ['total' => intval($item['total'])]);
                }
                return $newResults;
            }
        }
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
        $qb->from('App\Models\WMS\VariantInventory', 'variantInventory')
            ->join('variantInventory.variant', 'variant', Query\Expr\Join::ON)
            ->join('variant.product', 'product', Query\Expr\Join::ON)
            ->join('variant.client', 'client', Query\Expr\Join::ON)
            ->join('variantInventory.organization', 'organization', Query\Expr\Join::ON)
            ->join('variantInventory.inventoryLocation', 'inventoryLocation', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('variantInventory.id', $query['ids']));

        if (!is_null(AU::get($query['variantIds'])))
            $qb->andWhere($qb->expr()->in('variant.id', $query['variantIds']));

        if (!is_null(AU::get($query['productIds'])))
            $qb->andWhere($qb->expr()->in('product.id', $query['productIds']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['inventoryLocationIds'])))
            $qb->andWhere($qb->expr()->in('inventoryLocation.id', $query['inventoryLocationIds']));

        return $qb;
    }

    /**
     * @param   int         $id
     * @return  VariantInventory|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}