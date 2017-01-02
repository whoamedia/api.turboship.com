<?php

namespace App\Http\Controllers;


use App\Http\Requests\Organizations\ShowOrganization;
use App\Http\Requests\Organizations\UpdateOrganization;
use App\Models\CMS\Organization;
use App\Repositories\Doctrine\CMS\OrganizationRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrganizationController extends BaseAuthController
{

    /**
     * @var OrganizationRepository
     */
    private $organizationRepo;


    public function __construct ()
    {
        $this->organizationRepo         = EntityManager::getRepository('App\Models\CMS\Organization');
    }


    public function show (Request $request)
    {
        $organization                   = $this->getOrganizationFromRoute($request->route('id'));
        return response($organization);
    }


    public function update (Request $request)
    {
        $updateOrganization             = new UpdateOrganization($request->input());
        $updateOrganization->setId($request->route('id'));
        $updateOrganization->validate();
        $updateOrganization->clean();

        $organization                   = $this->getOrganizationFromRoute($updateOrganization->getId());

        if (!is_null($updateOrganization->getName()))
            $organization->setName($updateOrganization->getName());

        $this->organizationRepo->saveAndCommit($organization);
        return response($organization);
    }


    /**
     * @param   int     $id
     * @return  Organization
     */
    private function getOrganizationFromRoute ($id)
    {
        $showOrganization               = new ShowOrganization();
        $showOrganization->setId($id);
        $showOrganization->validate();
        $showOrganization->clean();

        $organization                   = $this->organizationRepo->getOneById($showOrganization->getId());

        if (is_null($organization))
            throw new NotFoundHttpException('Organization not found');

        if ($organization->getId() != parent::getAuthUserOrganization()->getId())
            throw new AccessDeniedHttpException('Unable to update organizations that you do not belong to');

        return $organization;
    }

}