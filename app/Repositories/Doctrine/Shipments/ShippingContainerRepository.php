<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\ShippingContainer;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class ShippingContainerRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      ShippingContainer[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['shippingContainer', 'organization', 'shippingContainerType']);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addOrderBy(AU::get($query['orderBy'], 'shippingContainer.id'), AU::get($query['direction'], 'ASC'));

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
            'COUNT(DISTINCT shippingContainer.id) AS total',
            'shippingContainerType.id AS shippingContainerType_id', 'shippingContainerType.name AS shippingContainerType_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('shippingContainerType');

        $result                     =       $qb->getQuery()->getResult();

        $lexicon = [
            'shippingContainerType' =>  [
                'searchField'   => 'shippingContainerTypeIds',
                'type'          => 'integer',
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
        $qb->from('App\Models\Shipments\ShippingContainer', 'shippingContainer')
            ->join('shippingContainer.organization', 'organization', Query\Expr\Join::ON)
            ->join('shippingContainer.shippingContainerType', 'shippingContainerType', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('shippingContainer.id', $query['ids']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['shippingContainerTypeIds'])))
            $qb->andWhere($qb->expr()->in('shippingContainerType.id', $query['shippingContainerTypeIds']));

        return $qb;
    }


    /**
     * @param   int     $id
     * @return  ShippingContainer|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}