<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;

class NicheLogisticsSeeder extends Seeder
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
    }

    private function organization ()
    {
        DB::table('Organization')->insert(
            [
                'id'                =>  2,
                'name'              => 'Niche Logistics',
            ]
        );
    }

    private function users()
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
