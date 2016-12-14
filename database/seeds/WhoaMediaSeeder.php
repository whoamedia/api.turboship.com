<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;

class WhoaMediaSeeder extends Seeder
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

        $this->organization();
        $this->users();
        $this->clients();
    }

    private function organization ()
    {
        DB::table('Organization')->insert(
            [
                'id'                =>  1,
                'name'              => 'Whoa Media',
            ]
        );
    }

    private function users()
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

        //  James
        $user                   = new User();
        $user->setFirstName('James');
        $user->setLastName('Weston');
        $user->setEmail('james@turboship.com');
        $user->setPassword('password');
        $user->setOrganization($organization);
        $this->organizationRepo->saveAndCommit($user);
    }

    private function clients ()
    {
        DB::table('Client')->insert(
            [
                'name'              => 'Whoa Media',
                'organizationId'    => 1
            ]
        );
    }
}
