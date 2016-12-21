<?php

namespace App\Http\Controllers\WebHooks;


use Illuminate\Http\Request;

class ShopifyOrderController extends BaseShopifyController
{

    public function __construct (Request $request)
    {
        parent::__construct($request);
    }


    public function createOrder (Request $request)
    {
        try
        {

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setSuccess(false);
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function cancelOrder (Request $request)
    {
        try
        {

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setSuccess(false);
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function deleteOrder (Request $request)
    {
        try
        {

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setSuccess(false);
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function orderPaid (Request $request)
    {
        try
        {

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setSuccess(false);
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function orderUpdated (Request $request)
    {
        try
        {

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setSuccess(false);
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

}