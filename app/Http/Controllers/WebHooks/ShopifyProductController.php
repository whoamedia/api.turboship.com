<?php

namespace App\Http\Controllers\WebHooks;


use Illuminate\Http\Request;

class ShopifyProductController extends BaseShopifyController
{

    public function __construct (Request $request)
    {
        parent::__construct($request);
    }


    public function createProduct (Request $request)
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

    public function deleteProduct (Request $request)
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

    public function updateProduct (Request $request)
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