<?php

use Illuminate\Database\Seeder;
use \App\Utilities\IntegrationUtility;
use \App\Utilities\IntegrationCredentialUtility;

class IntegrationCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('IntegrationCredential')->insert(
            $this->getIntegrationCredentials()
        );
    }


    private function getIntegrationCredentials ()
    {
        return [
            /**
             * Shopify
             */
            [
                'id'                => IntegrationCredentialUtility::SHOPIFY_API_KEY_ID,
                'name'              => 'apiKey',
                'integrationId'     => IntegrationUtility::SHOPIFY_ID,
                'isRequired'        => true
            ],
            [
                'id'                => IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID,
                'name'              => 'password',
                'integrationId'     => IntegrationUtility::SHOPIFY_ID,
                'isRequired'        => true
            ],
            [
                'id'                => IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID,
                'name'              => 'hostName',
                'integrationId'     => IntegrationUtility::SHOPIFY_ID,
                'isRequired'        => true
            ],
            [
                'id'                => IntegrationCredentialUtility::SHOPIFY_SHARED_SECRET_ID,
                'name'              => 'sharedSecret',
                'integrationId'     => IntegrationUtility::SHOPIFY_ID,
                'isRequired'        => false
            ],


            /**
             * EasyPost
             */
            [
                'id'                => IntegrationCredentialUtility::EASYPOST_API_KEY_ID,
                'name'              => 'apiKey',
                'integrationId'     => IntegrationUtility::EASYPOST_ID,
                'isRequired'        => true
            ],
        ];
    }

}
