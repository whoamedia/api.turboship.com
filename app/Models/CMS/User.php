<?php

namespace App\Models\CMS;


use App\Models\ACL\Traits\HasPermissions;
use App\Models\ACL\Traits\HasRoles;
use App\Models\BaseModel;
use App\Models\Support\Traits\HasImage;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Illuminate\Contracts\Auth\Authenticatable;
use Hash;

/**
 * @SWG\Definition()
 */
class User extends BaseModel implements Authenticatable, \JsonSerializable
{

    use HasPermissions, HasRoles, HasImage;


    /**
     * @SWG\Property(example="1")
     * @var int
     */
    protected $id;

    /**
     * @SWG\Property(example="John")
     * @var     string
     */
    protected $firstName;

    /**
     * @SWG\Property(example="Doe")
     * @var     string
     */
    protected $lastName;

    /**
     * @SWG\Property(example="1")
     * @var string
     */
    protected $email;

    /**
     * @SWG\Property(example="asdfasdf")
     * @var string
     */
    protected $password;
    
    /**
     * @SWG\Property()
     * @var Organization
     */
    protected $organization;

    /**
     * @var Client|null
     */
    protected $client;
    
    /**
     * @SWG\Property(ref="#/definitions/DateTime")
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * Account constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->permissions              = new ArrayCollection();
        $this->roles                    = new ArrayCollection();
        $this->createdAt                = new \DateTime();

        if (is_array($data))
        {
            $this->firstName            = AU::get($data['firstName']);
            $this->lastName             = AU::get($data['lastName']);
            $this->email                = AU::get($data['email']);
            $this->password             = AU::get($data['password']);
            $this->organization         = AU::get($data['organization']);
            $this->client               = AU::get($data['client']);
            $this->image                = AU::get($data['image']);
        }
    }

    public function validate()
    {
        
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['firstName']            = $this->getFirstName();
        $object['lastName']             = $this->getLastName();
        $object['email']                = $this->getEmail();
        $object['organization']         = $this->organization->jsonSerialize();
        $object['client']               = is_null($this->client) ? null : $this->client->jsonSerialize();
        $object['image']                = !is_null($this->image) ? $this->getImage()->jsonSerialize() : null;
        $object['object']               = 'User';

        return $object;
    }

    /**
     * This is temporarily in place to support Bugsnag's error reporting
     * @return array
     */
    public function toArray ()
    {
        return $this->jsonSerialize();
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password                 = Hash::make($password);
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
     * @return Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }





    /**
     * Auth junk
     */


    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {

    }

    /**
     * Get the unique identifier for the User
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the unique identifier name for the User
     * @return mixed
     */
    public function getAuthIdentifierName()
    {
        return null;
    }

    /**
     * Get the password for the User
     * @return string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     * @return string
     */
    public function getRememberToken()
    {
        return false;
    }

    /**
     * Get the column name for the "remember me" token.
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->id;
    }

}