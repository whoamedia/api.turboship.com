<?php

namespace App\Models\WMS;


use Doctrine\Common\Collections\ArrayCollection;

class Cart extends InventoryLocation implements \JsonSerializable
{

    /**
     * @var ArrayCollection
     */
    protected $totes;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->totes                    = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getObject()
    {
        return 'Cart';
    }

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
    public function addTote (Tote $tote)
    {
        $tote->setCart($this);
        $this->totes->add($tote);
    }

}