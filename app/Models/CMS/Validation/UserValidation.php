<?php

namespace App\Models\CMS\Validation;


use App\Repositories\Doctrine\CMS\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\CMS\User;
use EntityManager;

class UserValidation
{

    /**
     * @var UserRepository
     */
    protected $userRepo;


    /**
     * UserValidation constructor.
     * @param UserRepository|null $userRepo
     */
    public function __construct($userRepo = null)
    {
        if (is_null($userRepo))
            $userRepo                   = EntityManager::getRepository('App\Models\CMS\User');

        $this->userRepo                 = $userRepo;
    }


    /**
     * @param   int     $id
     * @param   bool    $validateOrganization       Validate that the provided user belongs to the same organization as the authUser
     * @return  User
     * @throws  NotFoundHttpException
     */
    public function idExists($id, $validateOrganization = false)
    {
        $user                           = $this->userRepo->getOneById($id);

        if (is_null($user))
            throw new NotFoundHttpException('User not found');

        if ($validateOrganization)
        {
            if (\Auth::getUser()->getOrganization()->getId() != $user->getOrganization()->getId())
                throw new NotFoundHttpException('User not found');
        }

        return $user;
    }

    /**
     * @param   string  $email
     * @return  User
     * @throws  NonUniqueResultException
     */
    public function emailExists($email)
    {
        $user                           = $this->userRepo->getOneByEmail($email);

        if (!is_null($user))
            throw new NotFoundHttpException('User email not found');

        return $user;
    }

    /**
     * @param   string  $email
     * @return  null
     * @throws  NotFoundHttpException
     */
    public function uniqueEmail($email)
    {
        $user                           = $this->userRepo->getOneByEmail($email);

        if (!is_null($user))
            throw new BadRequestHttpException('User email already exists');

        return null;
    }

}