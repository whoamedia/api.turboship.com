<?php

namespace App\Services\EasyPost;


use App\Exceptions\Integrations\IntegrationNotRespondingException;
use jamesvweston\EasyPost\EasyPostConfiguration;
use jamesvweston\EasyPost\EasyPostClient;
use App\Models\Integrations\IntegratedShippingApi;
use App\Services\CredentialService;
use jamesvweston\EasyPost\Exceptions\EasyPostServiceUnavailableException;
use jamesvweston\EasyPost\Exceptions\EasyPostUnableToVoidShippedOrderException;
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
    }
}