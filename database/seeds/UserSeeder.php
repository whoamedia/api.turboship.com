<?php

use Illuminate\Database\Seeder;

use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;

class UserSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\OrganizationRepository
     */
    private $organizationRepo;
    
    public function run()
    {
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
        $this->organizationRepo = EntityManager::getRepository('App\Models\CMS\Organization');

        $this->whoaMediaUsers();
        $this->nicheLogisticsUsers();
    }


    private function whoaMediaUsers()
    {
        $whoaMediaOrganizationId= 1;
        $organization           = $this->organizationRepo->getOneById($whoaMediaOrganizationId);

        //  Edward
        $user                   = new User();
        $user->setFirstName('Edward');
        $user->setLastName('Upton');
        $user->setEmail('eupton@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);




    }

    private function nicheLogisticsUsers()
    {
        $nicheLogisticsId       = 2;
        $organization           = $this->organizationRepo->getOneById($nicheLogisticsId);

        //  Brian
        $user                   = new User();
        $user->setFirstName('Brian');
        $user->setLastName('Harding');
        $user->setEmail('brian@nichelogistics.com');
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);


        //  Dave
        $user                   = new User();
        $user->setFirstName('Dave');
        $user->setLastName('Robertson');
        $user->setEmail('dave@nichelogistics.com');
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);

    }

}
