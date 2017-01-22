<?php

namespace App\Services;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\EasyPostIntegration;
use App\Integrations\EasyPost\Exceptions\EasyPostInvalidCredentialsException;
use App\Integrations\Shopify\Exceptions\ShopifyInvalidCredentialsException;
use App\Integrations\Shopify\ShopifyConfiguration;
use App\Integrations\Shopify\ShopifyIntegration;
use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedService;
use App\Repositories\Doctrine\Integrations\CredentialRepository;
use App\Utilities\IntegrationCredentialUtility;
use App\Utilities\IntegrationUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CredentialService
{

    /**
     * @var IntegratedService
     */
    private $integratedService;

    /**
     * @var CredentialRepository
     */
    private $clientCredentialRepo;

    public function __construct(IntegratedService $integratedService)
    {
        $this->integratedService        = $integratedService;
        $this->clientCredentialRepo     = EntityManager::getRepository('App\Models\Integrations\Credential');
    }

    /**
     * @return  Credential
     */
    public function getShopifyApiKey ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifyPassword ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifyHostName ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifySharedSecret ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_SHARED_SECRET_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }


    /**
     * @return  Credential
     */
    public function getEasyPostApiKey ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::EASYPOST_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return bool
     */
    public function validateCredentials ()
    {
        if ($this->integratedService->getIntegration()->getId() == IntegrationUtility::SHOPIFY_ID)
        {
            $service                    = $this->getShopifyIntegration();
            try
            {
                $service->webHookApi->get();
            }
            catch (ShopifyInvalidCredentialsException $exception)
            {
                return false;
            }
            return true;
        }
        else if ($this->integratedService->getIntegration()->getId() == IntegrationUtility::SHOPIFY_ID)
        {
            $service                    = $this->getEasyPostIntegration();
            try
            {
                $service->shipmentApi->get();
                return true;
            }
            catch (EasyPostInvalidCredentialsException $exception)
            {
                return false;
            }
        }
        else
            throw new BadRequestHttpException('Integration not supported in CredentialService');
    }

    /**
     * @return ShopifyIntegration
     * @throws BadRequestHttpException
     */
    public function getShopifyIntegration ()
    {
        if ($this->integratedService->getIntegration()->getId() != IntegrationUtility::SHOPIFY_ID)
            throw new BadRequestHttpException('Integration not supported');

        $apiKey                     = $this->getShopifyApiKey()->getValue();
        $password                   = $this->getShopifyPassword()->getValue();
        $hostName                   = $this->getShopifyHostName()->getValue();

        $shopifyConfiguration       = new ShopifyConfiguration();
        $shopifyConfiguration->setApiKey($apiKey);
        $shopifyConfiguration->setPassword($password);
        $shopifyConfiguration->setHostName($hostName);

        return new ShopifyIntegration($shopifyConfiguration);
    }

    /**
     * @return EasyPostIntegration
     * @throws BadRequestHttpException
     */
    public function getEasyPostIntegration ()
    {
        if ($this->integratedService->getIntegration()->getId() != IntegrationUtility::EASYPOST_ID)
            throw new BadRequestHttpException('Integration not supported');

        $apiKey                     = $this->getEasyPostApiKey()->getValue();

        $easyPostConfiguration      = new EasyPostConfiguration();
        $easyPostConfiguration->setApiKey($apiKey);
        return new EasyPostIntegration($easyPostConfiguration);
    }

}