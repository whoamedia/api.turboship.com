<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\Bin;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class BinRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Bin[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['bin', 'organization']);
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->addOrderBy(AU::get($query['orderBy'], 'bin.id'), AU::get($query['direction'], 'ASC'));

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
            'COUNT(DISTINCT bin.id) AS total',
            'bin.aisle AS aisle_id', 'bin.aisle AS aisle_name',
            'bin.section AS section_id', 'bin.section AS section_name',
            'bin.row AS row_id', 'bin.row AS row_name',
            'bin.col AS col_id', 'bin.col AS col_name',
        ]);
        $qb                         =   $this->buildQueryConditions($qb, $query);

        $qb->addGroupBy('bin.aisle');
        $qb->addGroupBy('bin.section');
        $qb->addGroupBy('bin.row');
        $qb->addGroupBy('bin.col');

        $result                                 =       $qb->getQuery()->getResult();

        $lexicon = [
            'aisle'         =>  [
                'searchField'   => 'aisles',
                'type'          => 'string',
                'values'        => [],
            ],
            'section'       =>  [
                'searchField'   => 'sections',
                'type'          => 'string',
                'values'        => [],
            ],
            'row'           =>  [
                'searchField'   => 'rows',
                'type'          => 'string',
                'values'        => [],
            ],
            'col'           =>  [
                'searchField'   => 'cols',
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
        $qb->from('App\Models\WMS\Bin', 'bin')
            ->join('bin.organization', 'organization', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('bin.id', $query['ids']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));

        if (!is_null(AU::get($query['barCodes'])))
        {
            $orX                    = $qb->expr()->orX();
            $barCodes               = explode(',', $query['barCodes']);
            foreach ($barCodes AS $barCode)
            {
                $orX->add($qb->expr()->eq('bin.barCode', $qb->expr()->literal($barCode)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['aisles'])))
        {
            $orX                    = $qb->expr()->orX();
            $aisles                 = explode(',', $query['aisles']);
            foreach ($aisles AS $aisle)
            {
                $orX->add($qb->expr()->eq('bin.aisle', $qb->expr()->literal($aisle)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['sections'])))
        {
            $orX                    = $qb->expr()->orX();
            $sections               = explode(',', $query['sections']);
            foreach ($sections AS $section)
            {
                $orX->add($qb->expr()->eq('bin.section', $qb->expr()->literal($section)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['rows'])))
        {
            $orX                    = $qb->expr()->orX();
            $rows                   = explode(',', $query['rows']);
            foreach ($rows AS $row)
            {
                $orX->add($qb->expr()->eq('bin.row', $qb->expr()->literal($row)));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['cols'])))
        {
            $orX                    = $qb->expr()->orX();
            $cols                   = explode(',', $query['cols']);
            foreach ($cols AS $col)
            {
                $orX->add($qb->expr()->eq('bin.col', $qb->expr()->literal($col)));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }

    /**
     * @param   int         $id
     * @return  Bin|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}