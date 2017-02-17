<?php

namespace App\Repositories\Doctrine\CMS;


use App\Models\CMS\Staff;
use App\Repositories\Doctrine\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\Paginatable;
use LaravelDoctrine\ORM\Utilities\ArrayUtil AS AU;

class StaffRepository extends BaseRepository
{

    use Paginatable;

    /**
     * Query against all fields
     * @param       []                      $query              Values to query against
     * @param       bool                    $ignorePagination   If true will not return pagination
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      Staff[]|LengthAwarePaginator
     */
    function where ($query, $ignorePagination = true, $maxLimit = 5000, $maxPage = 100)
    {
        $pagination                 =   $this->buildPagination($query, $maxLimit, $maxPage);

        $qb                         =   $this->_em->createQueryBuilder();
        $qb->select(['staff', 'organization']);
        $qb                         =   $this->buildQueryConditions($qb, $query);
        $qb->addOrderBy(AU::get($query['orderBy'], 'staff.id'), AU::get($query['direction'], 'ASC'));

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
        $qb->from('App\Models\CMS\Staff', 'staff')
            ->join('staff.organization', 'organization', Query\Expr\Join::ON);

        if (!is_null(AU::get($query['ids'])))
            $qb->andWhere($qb->expr()->in('staff.id', $query['ids']));

        if (!is_null(AU::get($query['organizationIds'])))
            $qb->andWhere($qb->expr()->in('organization.id', $query['organizationIds']));


        if (!is_null(AU::get($query['barCodes'])))
        {
            $orX                    = $qb->expr()->orX();
            $barCodes               = explode(',', $query['barCodes']);
            foreach ($barCodes AS $barCode)
            {
                $orX->add($qb->expr()->eq('staff.barCode', $qb->expr()->literal($barCode)));
            }
            $qb->andWhere($orX);
        }


        if (!is_null(AU::get($query['firstNames'])))
        {
            $orX                    = $qb->expr()->orX();
            $firstNames                  = explode(',', $query['firstNames']);
            foreach ($firstNames AS $name)
            {
                $orX->add($qb->expr()->LIKE('staff.firstName', $qb->expr()->literal('%' . trim($name) . '%')));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['lastNames'])))
        {
            $orX                    = $qb->expr()->orX();
            $lastNames                  = explode(',', $query['lastNames']);
            foreach ($lastNames AS $name)
            {
                $orX->add($qb->expr()->LIKE('staff.lastName', $qb->expr()->literal('%' . trim($name) . '%')));
            }
            $qb->andWhere($orX);
        }

        if (!is_null(AU::get($query['emails'])))
        {
            $orX                    = $qb->expr()->orX();
            $emails                  = explode(',', $query['emails']);
            foreach ($emails AS $name)
            {
                $orX->add($qb->expr()->LIKE('staff.email', $qb->expr()->literal('%' . trim($name) . '%')));
            }
            $qb->andWhere($orX);
        }

        return $qb;
    }


    /**
     * @param   int     $id
     * @return  Staff|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param   string  $email
     * @return  Staff|null
     */
    public function getOneByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param   string  $barCode
     * @return  Staff|null
     */
    public function getOneByBarCode($barCode)
    {
        return $this->findOneBy(['barCode' => $barCode]);
    }

}