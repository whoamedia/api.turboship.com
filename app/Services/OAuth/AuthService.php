<?php

namespace App\Services\OAuth;


use Auth;
use Authorizer;
use EntityManager;
use Hash;
use App\Models\CMS\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthService
{

    /**
     * @var     \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    public function __construct()
    {
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
    }

    /**
     * @param   string          $username
     * @param   string          $password
     * @return  int|null
     */
    public function verify($username, $password)
    {
        $user                   = $this->userRepo->getOneByEmail($username);

        if (is_null($user))
            return false;

        if (!Hash::check($password, $user->getPassword()))
            return false;

        $this->validateActiveUser($user);

        Auth::setUser($user);
        return $user->getId();
    }

    /**
     * Set the Auth User from the access_token
     * @param   int|null            $userId
     * @throws  AccessDeniedHttpException
     */
    public function setUserFromToken($userId = null)
    {
        if (Authorizer::getResourceOwnerType() == 'client')
            return;

        $user                   = $this->getUserFromToken($userId);

        if (is_null($user))
            throw new AccessDeniedHttpException('No user associated with the user id ' . Authorizer::getResourceOwnerId());

        $this->validateActiveUser($user);

        Auth::setUser($user);
    }

    /**
     * @param   int|null            $userId
     * @return  User|null
     */
    public function getUserFromToken($userId = null)
    {
        $userId                 = !is_null($userId) ? $userId : Authorizer::getResourceOwnerId();
        $user                   = $this->userRepo->getOneById($userId);
        return $user;
    }

    /**
     * @param   User                $user
     * @return  bool
     */
    public function validateActiveUser($user)
    {
        return true;
    }

}