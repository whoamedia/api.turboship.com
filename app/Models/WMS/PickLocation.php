<?php

namespace App\Models\WMS;


use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class PickLocation implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var PickInstruction
     */
    protected $pickInstruction;

    /**
     * @var Bin
     */
    protected $bin;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $completedAt;


    public function __construct($data = [])
    {
        $this->items                    = new ArrayCollection();
        $this->createdAt                = new \DateTime();
        $this->pickInstruction          = AU::get($data['pickInstruction']);
        $this->bin                      = AU::get($data['bin']);
        $this->completedAt              = AU::get($data['completedAt']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['bin']                  = $this->bin->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['completedAt']          = is_null($this->completedAt) ? null : $this->completedAt;

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
     * @return PickInstruction
     */
    public function getPickInstruction()
    {
        return $this->pickInstruction;
    }

    /**
     * @param PickInstruction $pickInstruction
     */
    public function setPickInstruction($pickInstruction)
    {
        $this->pickInstruction = $pickInstruction;
    }

    /**
     * @return Bin
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param Bin $bin
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @return PickItem[]
     */
    public function getItems ()
    {
        return $this->items->toArray();
    }

    /**
     * @param PickItem $item
     */
    public function addItem (PickItem $item)
    {
        $item->setPickLocation($this);
        $this->items->add($item);
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @param \DateTime|null $completedAt
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }

}