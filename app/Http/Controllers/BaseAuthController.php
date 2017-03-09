<?php

namespace App\Http\Controllers;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BaseAuthController extends Controller
{

    /**
     * @return  \App\Models\CMS\User
     */
    public function getAuthUser ()
    {
        return \Auth::getUser();
    }

    /**
     * @return \App\Models\CMS\Staff
     */
    public function getAuthStaff ()
    {
        $user                       = $this->getAuthUser();

        if ( !($user instanceof \App\Models\CMS\Staff) )
            throw new AccessDeniedHttpException('Insufficient access');

        return $user;
    }

    /**
     * @return \App\Models\CMS\Organization
     */
    public function getAuthUserOrganization ()
    {
        return \Auth::getUser()->getOrganization();
    }

    /**
     * If the User does not belong to a client that means they only belong to an organization
     * @return bool
     */
    public function authUserIsClient ()
    {
        return is_null(\Auth::getUser()->getClient()) ? false : true;
    }

}