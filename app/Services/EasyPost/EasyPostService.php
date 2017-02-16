<?php

namespace App\Services\EasyPost;


use App\Exceptions\Integrations\IntegrationInvalidCredentialsException;
use App\Exceptions\Integrations\IntegrationNotRespondingException;
use App\Exceptions\Integrations\IntegrationThrottledException;
use jamesvweston\EasyPost\EasyPostConfiguration;
use jamesvweston\EasyPost\EasyPostClient;
use App\Models\Integrations\IntegratedShippingApi;
use App\Services\CredentialService;
use jamesvweston\EasyPost\Exceptions\EasyPostCustomsInfoException;
use jamesvweston\EasyPost\Exceptions\EasyPostInvalidCredentialsException;
use jamesvweston\EasyPost\Exceptions\EasyPostServiceUnavailableException;
use jamesvweston\EasyPost\Exceptions\EasyPostUnableToVoidShippedOrderException;
use jamesvweston\EasyPost\Exceptions\EasyPostUserThrottledException;
use jamesvweston\EasyPost\Models\Requests\CreateEasyPostShipment;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EasyPostService
{

    /**
     * @var EasyPostClient
     */
    protected $easyPostClient;

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi= $integratedShippingApi;
        $credentialService          = new CredentialService($this->integratedShippingApi);
        $apiKey                     = $credentialService->getEasyPostApiKey()->getValue();

        $easyPostConfiguration      = new EasyPostConfiguration();
        $easyPostConfiguration->setApiKey($apiKey);
        $this->easyPostClient       = new EasyPostClient($easyPostConfiguration);
    }


    /**
     * @param   CreateEasyPostShipment $createEasyPostShipment
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     */
    public function rateShipment (CreateEasyPostShipment $createEasyPostShipment)
    {
        try
        {
            return $this->easyPostClient->shipmentApi->create($createEasyPostShipment);
        }
        catch (EasyPostServiceUnavailableException $exception)
        {
            throw new IntegrationNotRespondingException($exception->getMessage());
        }
        catch (EasyPostUserThrottledException $exception)
        {
            throw new IntegrationThrottledException('The EasyPost API user has been throttled');
        }
        catch (EasyPostCustomsInfoException $exception)
        {
            \Bugsnag::leaveBreadcrumb('request', null, $createEasyPostShipment->jsonSerialize());
            throw $exception;
        }

    }

    /**
     * @param   string      $easyPostShipmentId
     * @param   string      $easyPostRateId
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     */
    public function purchasePostage ($easyPostShipmentId, $easyPostRateId)
    {
        try
        {
            return $this->easyPostClient->shipmentApi->buy($easyPostShipmentId, $easyPostRateId);
        }
        catch (EasyPostServiceUnavailableException $exception)
        {
            throw new IntegrationNotRespondingException($exception->getMessage());
        }
        catch (EasyPostUserThrottledException $exception)
        {
            throw new IntegrationThrottledException('The EasyPost API user has been throttled');
        }
        catch (EasyPostInvalidCredentialsException $exception)
        {
            throw new IntegrationInvalidCredentialsException('Invalid EasyPost credentials');
        }
        catch (EasyPostCustomsInfoException $exception)
        {
            \Bugsnag::leaveBreadcrumb('request', null,
                [
                    'easyPostShipmentId' => $easyPostShipmentId,
                    'easyPostRateId' => $easyPostRateId
                ]);
            throw $exception;
        }
    }

    /**
     * @param   string      $easyPostShipmentId
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     * @throws  BadRequestHttpException
     */
    public function voidPostage ($easyPostShipmentId)
    {
        try
        {
            return $this->easyPostClient->shipmentApi->refund($easyPostShipmentId);
        }
        catch (EasyPostUnableToVoidShippedOrderException $exception)
        {
            throw new BadRequestHttpException($exception->getMessage());
        }
        catch (EasyPostServiceUnavailableException $exception)
        {
            throw new IntegrationNotRespondingException($exception->getMessage());
        }
        catch (EasyPostUserThrottledException $exception)
        {
            throw new IntegrationThrottledException('The EasyPost API user has been throttled');
        }
        catch (EasyPostInvalidCredentialsException $exception)
        {
            throw new IntegrationInvalidCredentialsException('Invalid EasyPost credentials');
        }
    }

    /**
     * @param   string    $easyPostShipmentId
     * @param   string    $format
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     */
    public function updateLabelFormat ($easyPostShipmentId, $format = 'ZPL')
    {
        try
        {
            return $this->easyPostClient->shipmentApi->convertLabel($easyPostShipmentId, $format);
        }
        catch (EasyPostServiceUnavailableException $exception)
        {
            throw new IntegrationNotRespondingException($exception->getMessage());
        }
        catch (EasyPostUserThrottledException $exception)
        {
            throw new IntegrationThrottledException('The EasyPost API user has been throttled');
        }
        catch (EasyPostInvalidCredentialsException $exception)
        {
            throw new IntegrationInvalidCredentialsException('Invalid EasyPost credentials');
        }
    }
}