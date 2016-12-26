<?php

namespace App\Http\Controllers;


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