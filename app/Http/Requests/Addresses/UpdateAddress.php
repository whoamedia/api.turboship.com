<?php

namespace App\Http\Requests\Addresses;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class UpdateAddress extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var string|null
     */
    protected $company;

    /**
     * @var string|null
     */
    protected $street1;

    /**
     * @var string|null
     */
    protected $street2;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @var string|null
     */
    protected $postalCode;

    /**
     * @var int|null
     */
    protected $countryId;

    /**
     * @var int|null
     */
    protected $subdivisionId;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $email;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->firstName                = AU::get($data['firstName']);
        $this->lastName                 = AU::get($data['lastName']);
        $this->company                  = AU::get($data['company']);
        $this->street1                  = AU::get($data['street1']);
        $this->street2                  = AU::get($data['street2']);
        $this->city                     = AU::get($data['city']);
        $this->postalCode               = AU::get($data['postalCode']);
        $this->countryId                = AU::get($data['countryId']);
        $this->subdivisionId            = AU::get($data['subdivisionId']);
        $this->phone                    = AU::get($data['phone']);
        $this->email                    = AU::get($data['email']);
    }

    public function validate()
    {

        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(parent::getInteger($this->id)))
            throw new BadRequestHttpException('id must be integer');
    }

    public function clean()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['firstName']            = $this->firstName;
        $object['lastName']             = $this->lastName;
        $object['company']              = $this->company;
        $object['street1']              = $this->street1;
        $object['street2']              = $this->street2;
        $object['city']                 = $this->city;
        $object['postalCode']           = $this->postalCode;
        $object['countryId']            = $this->countryId;
        $object['subdivisionId']        = $this->subdivisionId;
        $object['phone']                = $this->phone;
        $object['email']                = $this->email;

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
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return null|string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param null|string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return int|null
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param int|null $countryId
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * @return null|string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @param null|string $street1
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;
    }

    /**
     * @return null|string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param null|string $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }

    /**
     * @return null|string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param null|string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return int|null
     */
    public function getSubdivisionId()
    {
        return $this->subdivisionId;
    }

    /**
     * @param int|null $subdivisionId
     */
    public function setSubdivisionId($subdivisionId)
    {
        $this->subdivisionId = $subdivisionId;
    }

    /**
     * @return null|string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}