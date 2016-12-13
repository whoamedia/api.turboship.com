<?php

use Illuminate\Database\Seeder;

use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;

class UserSeeder extends Seeder
{

    /**
     * @var \App\Repositories\CMS\UserRepository
     */
    private $userRepo;

    /**
     * @var \App\Repositories\CMS\OrganizationRepository
     */
    private $organizationRepo;
    
    public function run()
    {
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
        $this->organizationRepo = EntityManager::getRepository('App\Models\CMS\Organization');
        $this->adminUsers();
        $this->whoaMediaUsers();
        $this->nicheLogisticsUsers();
    }
    
    private function adminUsers()
    {
        $organization           = $this->organizationRepo->getOneById(\App\Utilities\Data\OrganizationDataUtil::getAdminId());
        //  Use direct table insert so the doctrine lifeCycles do not get invoked
        DB::table('User')->insert(
            [
                'id'            => 1,
                'firstName'     => 'Locations',
                'lastName'      => 'Auth Bot',
                'email'         => 'locations.auth.bot@turboship.com',
                'organizationId'=> $organization->id,
            ]
        );
    }


    private function whoaMediaUsers()
    {
        $whoaMediaOrganizationId= 2;
        $organization           = $this->organizationRepo->getOneById($whoaMediaOrganizationId);

        //  Edward
        $user                   = new User();
        $user->firstName        = 'Edward';
        $user->lastName         = 'Upton';
        $user->email            = 'eupton@whoamedia.com';
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);

        
        $createOAuthClientRequest   = new CreateOAuthClientRequest();
        $createOAuthClientRequest->setAccountId($user->getTurboShipAccount()->getId());
        $createOAuthClientRequest->setOAuthScopeIds('locations');
        $createOAuthClientRequest->setId('BEFqakP6CNldNW38');
        $createOAuthClientRequest->setSecret('9RGirmCxOToCtRAjB4vUHZfqdf4UUuRA');
        TSAuthService::get()->oAuthApi->store($createOAuthClientRequest);

        
        //  Ryon
        $user                   = new User();
        $user->firstName        = 'Ryon';
        $user->lastName         = 'Hunter';
        $user->email            = 'ryon@whoamedia.com';
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);

        $createOAuthClientRequest   = new CreateOAuthClientRequest();
        $createOAuthClientRequest->setAccountId($user->getTurboShipAccount()->getId());
        $createOAuthClientRequest->setOAuthScopeIds('locations');
        $createOAuthClientRequest->setId('NOKquaLw60hf9oGG');
        $createOAuthClientRequest->setSecret('KZMCzr96jdzOL8THGM0cfUrDgedbzh9k');
        TSAuthService::get()->oAuthApi->store($createOAuthClientRequest);
    }

    private function nicheLogisticsUsers()
    {
        $nicheLogisticsId       = 3;
        $organization           = $this->organizationRepo->getOneById($nicheLogisticsId);

        //  Brian
        $user                   = new User();
        $user->firstName        = 'Brian';
        $user->lastName         = 'Harding';
        $user->email            = 'brian@nichelogistics.com';
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);

        //  Dave
        $user                   = new User();
        $user->firstName        = 'Dave';
        $user->lastName         = 'Robertson';
        $user->email            = 'dave@nichelogistics.com';
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);
    }

}
