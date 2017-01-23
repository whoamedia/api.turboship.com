<?php

namespace App\Repositories\Doctrine;


use App\Repositories\Doctrine\Contracts\RepositoryInterface;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository implements RepositoryInterface
{

    public function commit ()
    {
        $this->_em->flush();
    }

    public function create ($obj)
    {
        $this->_em->persist($obj);
    }

    public function save ($obj)
    {
        $this->_em->persist($obj);
    }

    public function update ($obj)
    {
        $this->_em->merge($obj);
    }

    public function saveAndCommit ($obj)
    {
        $this->save($obj);
        $this->commit();
        return $obj;
    }

    public function delete ($obj)
    {
        $this->_em->remove($obj);
    }

    /**
     * Build attributes used for pagination
     * @param       []                      $data               Information used to construct
     * @param       int|null                $maxLimit           If provided limit is greater than this value, set is to this value
     * @param       int|null                $maxPage            If the provided page is greater than this value, restrict it to this value
     * @return      array                   $data               Everything needed for pagination
     */
    protected function buildPagination($data = null, $maxLimit = 5000, $maxPage = 100)
    {
        $data                           =   is_array($data) ? $data : [];

        if (is_array($data))
        {
            $data['page']               =   AU::get($data['page'], 1);
            $data['limit']              =   AU::get($data['limit'], 80);
            $data['lexicon']            =   AU::get($data['lexicon'], false);
        }
        // page checks
        if (empty($data['page']) || is_null($data['page']))
            $data['page']               =   1;

        if ($data['page'] > $maxPage)
            $data['page']               =   $maxPage;

        // limit checks
        if (empty($data['limit']) || is_null($data['limit']))
            $data['limit']              =   80;

        if ($data['limit'] > $maxLimit)
            $data['limit']              =   $maxLimit;

        $data['lexicon']                =   ($data['lexicon'] == 1 || $data['lexicon'] === 'true') ? true : false;

        return $data;
    }

    /**
     * @param   array   $lexicon
     * @param   array
     * @return  array
     */
    protected function buildLexicon($lexicon, $result)
    {
        $lexiconKeySet                  = array_keys($lexicon);
        foreach($result AS $resultItem)
        {
            foreach ($lexiconKeySet AS $lexiconKey)
            {
                $key                    = array_search($resultItem[$lexiconKey.'_'.'id'], array_column($lexicon[$lexiconKey], 'id'));
                if ($key !== false)
                    $lexicon[$lexiconKey][$key]['total'] += $resultItem['total'];
                else
                {
                //  echo array_search($resultItem[$lexiconKey.'_'.'id'], array_column($lexicon[$lexiconKey], 'id')) . PHP_EOL;
                array_push($lexicon[$lexiconKey],
                [
                    'id' => $resultItem[$lexiconKey.'_'.'id'],
                    'name' => $resultItem[$lexiconKey.'_'.'name'],
                    'total' => $resultItem['total']
                ]);
                }
            }
        }

        return $lexicon;
    }


}