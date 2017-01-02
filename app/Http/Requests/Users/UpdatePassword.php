<?php

namespace App\Http\Requests\Users;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdatePassword implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $currentPassword;

    /**
     * @var string
     */
    protected $newPassword;

    /**
     * @param   array|null $data
     */
    public function __construct($data = null)
    {
        if (is_array($data))
        {
            $this->id                       = AU::get($data['id']);
            $this->currentPassword          = AU::get($data['currentPassword']);
            $this->newPassword              = AU::get($data['newPassword']);
        }
    }

    public function validate()
    {
        /**
         * Validate required fields
         */
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->currentPassword))
            throw new BadRequestHttpException('currentPassword is required');

        if (is_null($this->newPassword))
            throw new BadRequestHttpException('newPassword is required');

        /**
         * Validate expected data types
         */
        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');
    }

    public function clean ()
    {
        $this->id                           = InputUtil::getInt($this->id);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                       = $this->id;
        $object['currentPassword']          = $this->currentPassword;
        $object['newPassword']              = $this->newPassword;

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
     * @return string
     */
    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     */
    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

}