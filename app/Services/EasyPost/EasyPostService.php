<?php

namespace App\Services\EasyPost;


use jamesvweston\EasyPost\EasyPostConfiguration;
use jamesvweston\EasyPost\EasyPostClient;
use App\Models\Integrations\IntegratedShippingApi;
use App\Services\CredentialService;
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
        return $this->easyPostClient->shipmentApi->create($createEasyPostShipment);

    }

    /**
     * @param   string      $easyPostShipmentId
     * @param   string      $easyPostRateId
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     */
    public function purchasePostage ($easyPostShipmentId, $easyPostRateId)
    {
        return $this->easyPostClient->shipmentApi->buy($easyPostShipmentId, $easyPostRateId);
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
    }

    /**
     * @param   string    $easyPostShipmentId
     * @param   string    $format
     * @return  \jamesvweston\EasyPost\Models\Responses\EasyPostShipment
     */
    public function updateLabelFormat ($easyPostShipmentId, $format = 'ZPL')
    {
        return $this->easyPostClient->shipmentApi->convertLabel($easyPostShipmentId, $format);
    }
}