<?php

namespace App\Http\Requests\Users;


use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class CreateUser implements Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;


    public function __construct($data = [])
    {
        $this->firstName                = AU::get($data['firstName']);
        $this->lastName                 = AU::get($data['lastName']);
        $this->email                    = AU::get($data['email']);
        $this->password                 = AU::get($data['password']);
    }

    public function validate()
    {
        if (is_null($this->firstName))
            throw new MissingMandatoryParametersException('firstName is required');

        if (is_null($this->lastName))
            throw new MissingMandatoryParametersException('lastName is required');

        if (is_null($this->email))
            throw new MissingMandatoryParametersException('email is required');

        if (is_null($this->password))
            throw new MissingMandatoryParametersException('password is required');

        /**
         * Validate expected data types
         */
        if (!v::email()->validate($this->email))
            throw new BadRequestHttpException('Invalid email');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['firstName']            = $this->firstName;
        $object['lastName']             = $this->lastName;
        $object['email']                = $this->email;
        $object['password']             = $this->password;

        return $object;
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
        $this->password = $password;
    }

}