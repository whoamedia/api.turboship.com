<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use App\Models\CMS\Staff;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class PickInstruction implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Staff
     */
    protected $staff;

    /**
     * @var Staff
     */
    protected $createdBy;

    /**
     * @var ArrayCollection
     */
    protected $locations;

    /**
     * @var ArrayCollection
     */
    protected $pickTotes;

    /**
     * @var bool
     */
    protected $isAssigned;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $startedAt;

    /**
     * @var \DateTime|null
     */
    protected $completedAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->locations                = new ArrayCollection();
        $this->pickTotes                = new ArrayCollection();

        $this->organization             = AU::get($data['organization']);
        $this->staff                    = AU::get($data['staff']);
        $this->isAssigned               = AU::get($data['isAssigned'], false);
        $this->createdBy                = AU::get($data['createdBy']);
        $this->startedAt                = AU::get($data['startedAt']);
        $this->completedAt              = AU::get($data['completedAt']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['locations']            = $this->getLocations();
        $object['staff']                = $this->staff->jsonSerialize();
        $object['createdBy']            = $this->createdBy->jsonSerialize();
        $object['object']               = $this->getObject();
        $object['createdAt']            = $this->createdAt;
        $object['startedAt']            = is_null($this->startedAt) ? null : $this->startedAt;
        $object['completedAt']          = is_null($this->completedAt) ? null : $this->completedAt;

        return $object;
    }

    /**
     * @return string
     */
    abstract function getObject();

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
     * @return PickLocation[]
     */
    public function getLocations ()
    {
        return $this->locations->toArray();
    }

    /**
     * @param PickLocation $pickLocation
     */
    public function addLocation (PickLocation $pickLocation)
    {
        $pickLocation->setPickInstruction($this);
        $this->locations->add($pickLocation);
    }

    /**
     * @return PickTote[]
     */
    public function getPickTotes ()
    {
        return $this->pickTotes->toArray();
    }

    /**
     * @param PickTote $pickTote
     */
    public function addPickTote (PickTote $pickTote)
    {
        $pickTote->setPickInstruction($this);
        $this->pickTotes->add($pickTote);
    }

    /**
     * @return Staff
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * @param Staff $staff
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;
    }

    /**
     * @return bool
     */
    public function getIsAssigned()
    {
        return $this->isAssigned;
    }

    /**
     * @param bool $isAssigned
     */
    public function setIsAssigned($isAssigned)
    {
        $this->isAssigned = $isAssigned;
    }

    /**
     * @return Staff
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param Staff $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime|null $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
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