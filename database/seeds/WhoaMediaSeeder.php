<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Utilities\IntegrationCredentialUtility;

class WhoaMediaSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\CMS\ClientRepository
     */
    private $clientRepo;

    /**
     * @var \App\Repositories\Doctrine\Integrations\IntegrationCredentialRepository
     */
    private $integrationCredentialRepo;

    /**
     * @var \App\Repositories\Doctrine\Integrations\IntegrationRepository
     */
    private $integrationRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\OrganizationRepository
     */
    private $organizationRepo;


    /**
     * @var \App\Models\CMS\Organization
     */
    private $organization;

    /**
     * @var \App\Models\CMS\Client
     */
    private $client;

    /**
     * @var \App\Repositories\Doctrine\Integrations\ClientIntegrationRepository
     */
    private $clientIntegrationRepo;

    public function run()
    {
        $this->clientRepo       = EntityManager::getRepository('App\Models\CMS\Client');
        $this->integrationRepo  = EntityManager::getRepository('App\Models\Integrations\Integration');
        $this->integrationCredentialRepo    = EntityManager::getRepository('App\Models\Integrations\IntegrationCredential');
        $this->userRepo         = EntityManager::getRepository('App\Models\CMS\User');
        $this->organizationRepo = EntityManager::getRepository('App\Models\CMS\Organization');
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');

        $this->organization();
        $this->users();
        $this->clients();
        $this->shopifyCredentials();
    }

    private function organization ()
    {
        $organization           = new \App\Models\CMS\Organization();
        $organization->setName('Whoa Media');

        $this->organizationRepo->saveAndCommit($organization);

        $this->organization     = $organization;
    }

    private function users()
    {
        //  Edward
        $user                   = new User();
        $user->setFirstName('Edward');
        $user->setLastName('Upton');
        $user->setEmail('eupton@whoamedia.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $this->organizationRepo->saveAndCommit($user);

        //  James
        $user                   = new User();
        $user->setFirstName('James');
        $user->setLastName('Weston');
        $user->setEmail('james@turboship.com');
        $user->setPassword('password');
        $user->setOrganization($this->organization);
        $this->organizationRepo->saveAndCommit($user);
    }

    private function clients ()
    {
        $client                 = new \App\Models\CMS\Client();
        $client->setName('Whoa Media');
        $client->setOrganization($this->organization);
        $this->clientRepo->saveAndCommit($client);

        $this->client           = $client;
    }

    private function shopifyCredentials ()
    {
        $clientIntegration      = new \App\Models\Integrations\ClientIntegration();
        $clientIntegration->setSymbol('WHOA_MEDIA_SHOPIFY');
        $clientIntegration->setClient($this->client);

        $shopifyIntegration     = $this->integrationRepo->getOneById(\App\Utilities\IntegrationUtility::SHOPIFY_ID);
        $clientIntegration->setIntegration($shopifyIntegration);

        $shopifyApiKey          = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_API_KEY_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifyApiKey);

        //  (test)95fc4807a02d76f0e1251be499079371      (production)58f4bf83324fa33ff483dbb23c9f7a97
        $clientCredential->setValue('95fc4807a02d76f0e1251be499079371');
        $clientIntegration->addCredential($clientCredential);


        $shopifyPassword        = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifyPassword);

        //  (test)ffca6bc8c3af8ae6e7077a9644d5d294      (production)9ea1b78b0859f404cec59e520a7aaffe
        $clientCredential->setValue('ffca6bc8c3af8ae6e7077a9644d5d294');
        $clientIntegration->addCredential($clientCredential);

        $shopifyPassword        = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifyPassword);

        //  (test)ship-test     (production)cheapundies
        $clientCredential->setValue('ship-test');
        $clientIntegration->addCredential($clientCredential);


        $shopifySharedSecret    = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_SHARED_SECRET_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifySharedSecret);

        //  (test)1a59ea54bddd0635cdaf9662e5a1235c
        $clientCredential->setValue('1a59ea54bddd0635cdaf9662e5a1235c');
        $clientIntegration->addCredential($clientCredential);

        $this->clientIntegrationRepo->saveAndCommit($clientIntegration);
    }
}
