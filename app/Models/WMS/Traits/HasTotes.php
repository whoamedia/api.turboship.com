<?php

namespace App\Models\WMS\Traits;


use App\Models\WMS\Tote;
use Doctrine\Common\Collections\ArrayCollection;

trait HasTotes
{

    /**
     * @var ArrayCollection
     */
    protected $totes;


    /**
     * @return Tote[]
     */
    public function getTotes ()
    {
        return $this->totes->toArray();
    }

    /**
     * @param Tote $tote
     */
    public function addTote ($tote)
    {
        $this->totes->add($tote);
    }

}