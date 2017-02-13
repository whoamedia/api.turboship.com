<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $qb->select(['variantInventory', 'organization', 'inventoryLocation']);
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
        $qb->from('App\Models\WMS\VariantInventory', 'variantInventory')
            ->join('variantInventory.variant', 'variant', Query\Expr\Join::ON)
            ->join('variantInventory.organization', 'organization', Query\Expr\Join::ON)
            ->join('variantInventory.inventoryLocation', 'inventoryLocation', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('variantInventory.id', $query['ids']));

        if (!is_null(AU::get($query['variantIds'])))
            $qb->andWhere($qb->expr()->in('variant.id', $query['variantIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['inventoryLocationIds'])))
            $qb->andWhere($qb->expr()->in('inventoryLocation.id', $query['inventoryLocationIds']));

        if (!is_null(AU::get($query['barCodes'])))
        {
            $orX                    = $qb->expr()->orX();
            $barCodes               = explode(',', $query['barCodes']);
            foreach ($barCodes AS $barCode)
            {
                $orX->add($qb->expr()->eq('variantInventory.barCode', $qb->expr()->literal($barCode)));
            }
            $qb->andWhere($orX);
        }

        $qb->orderBy('variantInventory.id', 'ASC');
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