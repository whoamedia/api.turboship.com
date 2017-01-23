<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\Product;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class ProductRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Product[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->select(['product', 'client', 'organization', 'variants', 'aliases']);
        $qb->addOrderBy(AU::get($query['orderBy'], 'product.id'), AU::get($query['direction'], 'ASC'));

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
            'COUNT(DISTINCT product.id) AS total',
            'source.id AS source_id', 'source.name AS source_name',
            'client.id AS client_id', 'client.name AS client_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('client');
        $qb->addGroupBy('source');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'source'            =>  [],
            'client'            =>  [],
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
        $qb->from('App\Models\OMS\Product', 'product')
            ->join('product.client', 'client', Query\Expr\Join::ON)
            ->join('client.organization', 'organization', Query\Expr\Join::ON)
            ->leftJoin('product.variants', 'variants', Query\Expr\Join::ON)
            ->leftJoin('product.aliases', 'aliases', Query\Expr\Join::ON)
            ->join('aliases.source', 'source', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('product.id', $query['ids']));

        if (!is_null(AU::get($query['clientIds'])))
            $qb->andWhere($qb->expr()->in('client.id', $query['clientIds']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['variantIds'])))
            $qb->andWhere($qb->expr()->in('variants.id', $query['variantIds']));

        if (!is_null(AU::get($query['aliasIds'])))
            $qb->andWhere($qb->expr()->in('aliases.id', $query['aliasIds']));

        if (!is_null(AU::get($query['variantSkus'])))
        {
            $orX                    = $qb->expr()->orX();
            $variantSkus            = explode(',', $query['variantSkus']);
            foreach ($variantSkus AS $variantSku)
            {
                $orX->add($qb->expr()->eq('variants.sku', $qb->expr()->literal($variantSku)));
            }
            $qb->andWhere($orX);
        }



        if (!is_null(AU::get($query['externalIds'])))
        {
            $orX                    = $qb->expr()->orX();
            $externalIds            = explode(',', $query['externalIds']);
            foreach ($externalIds AS $externalId)
            {
                $orX->add($qb->expr()->eq('aliases.externalId', $qb->expr()->literal($externalId)));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }


    /**
     * @param   int         $id
     * @return  Product|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}