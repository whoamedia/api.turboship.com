<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use App\Models\Support\Traits\HasBarcode;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Cart implements \JsonSerializable
{

    use HasBarcode;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var ArrayCollection
     */
    protected $totes;


    public function __construct($data = [])
    {
        $this->totes                    = new ArrayCollection();
        $this->barCode                  = AU::get($data['barCode']);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['barCode']              = $this->barCode;

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
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

    /**
     * @param Tote $tote
     * @return bool
     */
    public function hasTote (Tote $tote)
    {
        return $this->totes->contains($tote);
    }

    /**
     * @param Tote $tote
     */
    public function removeTote (Tote $tote)
    {
        $tote->setCart(null);
        $this->totes->removeElement($tote);
    }
}