<?php

namespace App\Models\WMS\Traits;


use App\Models\WMS\Bin;
use Doctrine\Common\Collections\ArrayCollection;

trait HasBins
{

    /**
     * @var ArrayCollection
     */
    protected $bins;

    /**
     * @return Bin[]
     */
    public function getBins ()
    {
        return $this->bins->toArray();
    }

    /**
     * @param Bin $bin
     */
    public function addBin ($bin)
    {
        $this->bins->add($bin);
    }

}