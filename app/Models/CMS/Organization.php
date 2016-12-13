<?php

namespace App\Models\CMS;


use App\Models\BaseModel;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @SWG\Definition()
 */
class Organization extends BaseModel
{

    /**
     * @SWG\Property(example="1")
     * @var int
     */
    protected $id;

    /**
     * @SWG\Property(example="Joe's Fulfillment Company")
     * @var string
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $users;
    
    /**
     * @var \DateTime
     */
    protected $createdAt;
    
    
    /**
     * Organization constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->createdAt                = new \DateTime();
        $this->users                    = new ArrayCollection();
        
        if (is_array($data))
        {
            $this->name                 = AU::get($data['name']);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        
        return $object;
    }
    
    public function validate()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $user->setOrganization($this);
        $this->users->add($user);
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }
    
}