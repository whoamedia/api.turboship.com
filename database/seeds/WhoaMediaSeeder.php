<?php

use Illuminate\Database\Seeder;
use \App\Models\CMS\User;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Utilities\IntegrationCredentialUtility;
use App\Models\Integrations\ClientIntegration;
use App\Utilities\IntegrationUtility;

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
        $this->easyPost();
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
        $clientIntegration      = new ClientIntegration();
        $clientIntegration->setSymbol('WHOA_MEDIA_SHOPIFY');
        $clientIntegration->setClient($this->client);

        $shopifyIntegration     = $this->integrationRepo->getOneById(IntegrationUtility::SHOPIFY_ID);
        $clientIntegration->setIntegration($shopifyIntegration);

        $shopifyApiKey          = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_API_KEY_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifyApiKey);

        //  (test)95fc4807a02d76f0e1251be499079371      (production)e9629539a4e9ef0e1147164e11ce6794
        $clientCredential->setValue('95fc4807a02d76f0e1251be499079371');
        $clientIntegration->addCredential($clientCredential);


        $shopifyPassword        = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($shopifyPassword);

        //  (test)ffca6bc8c3af8ae6e7077a9644d5d294      (production)67794b72c5dd4f3f085354f8df36f36c
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

        //  (test)1a59ea54bddd0635cdaf9662e5a1235c      (production)1a7b27523fc4bb310f3f3506c7e90a88
        $clientCredential->setValue('1a59ea54bddd0635cdaf9662e5a1235c');
        $clientIntegration->addCredential($clientCredential);

        $this->clientIntegrationRepo->saveAndCommit($clientIntegration);
    }


    private function easyPost ()
    {
        $clientIntegration      = new ClientIntegration();
        $clientIntegration->setSymbol('WHOA_MEDIA_EASYPOST');
        $clientIntegration->setClient($this->client);

        $easyPostIntegration    = $this->integrationRepo->getOneById(IntegrationUtility::EASYPOST_ID);
        $clientIntegration->setIntegration($easyPostIntegration);

        $easyPostApiKey         = $this->integrationCredentialRepo->getOneById(IntegrationCredentialUtility::EASYPOST_API_KEY_ID);
        $clientCredential       = new \App\Models\Integrations\ClientCredential();
        $clientCredential->setIntegrationCredential($easyPostApiKey);

        //  (test)9ryULR9axDntXQZzHQ6zOQ
        $clientCredential->setValue('9ryULR9axDntXQZzHQ6zOQ');
        $clientIntegration->addCredential($clientCredential);

        $this->clientIntegrationRepo->saveAndCommit($clientIntegration);
    }
}
