<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\Models\Requests\BuyEasyPostShipment;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Requests\GetEasyPostShipments;
use App\Integrations\EasyPost\Models\Responses\EasyPostShipment;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShipmentApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/shipments';


    /**
     * @param   GetEasyPostShipments|array     $request
     * @return  EasyPostShipment[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetEasyPostShipments ? $request : new GetEasyPostShipments($request);
        $response                       = parent::makeHttpRequest('get', $this->path, null, $request->jsonSerialize());
        $items                          = AU::get($response['shipments'], []);

        $result                         = [];
        foreach ($items AS $shipment)
        {
            $result[]                   = new EasyPostShipment($shipment);
        }

        return $result;
    }

    /**
     * @see https://www.easypost.com/docs/api.html#shipments
     * @param   CreateEasyPostShipment|array     $request
     * @return  EasyPostShipment
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateEasyPostShipment ? $request : new CreateEasyPostShipment($request);
        $response                       = parent::makeHttpRequest('post', $this->path, $request->jsonSerialize());

        return new EasyPostShipment($response);
    }

    /**
     * @param   string  $id
     * @return  EasyPostShipment
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id);
        return new EasyPostShipment($response);
    }

    /**
     * @param   string      $id
     * @param   string      $rate
     * @param   float|null  $insurance
     * @return  EasyPostShipment
     */
    public function buy ($id, $rate, $insurance = null)
    {
        $request                        = [
            'rate'                      => $rate,
            'insurance'                 => $insurance,
        ];

        $response                       = parent::makeHttpRequest('post', $this->path . '/' . $id . '/buy', $request);
        return new EasyPostShipment($response);
    }

    /***
     * @param   string      $id
     * @param   string  $fileFormat
     * @return  EasyPostShipment
     */
    public function getLabel ($id, $fileFormat = 'PNG')
    {
        $request    = [
            'file_format'               => $fileFormat,
        ];

        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id . '/label', $request);
        return new EasyPostShipment($response);
    }

    /**
     * @param   string      $id
     * @return  EasyPostShipment
     */
    public function getRates ($id)
    {
        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id . '/rates');
        return new EasyPostShipment($response);
    }

    /**
     * @param   string      $id
     * @param   float       $amount
     * @return  EasyPostShipment
     */
    public function addInsurance ($id, $amount)
    {
        $request    = [
            'amount'                    => $amount,
        ];

        $response                       = parent::makeHttpRequest('post', $this->path . '/' . $id . '/insurance', $request);
        return new EasyPostShipment($response);
    }

    /**
     * USPS
     *      Shipping labels can be refunded if requested within 30 days of generation. The processing time is 14 days, after which the funds will return to your EasyPost balance.
     *      EasyPost fees will also be refunded. To qualify, a shipment must not have been scanned by the USPS, or included on a Scan Form.
     * UPS and FedEx
     *      Shipping labels may be refunded within 90 days of creation
     * @param   string      $id
     * @return  EasyPostShipment
     */
    public function refund ($id)
    {
        $response                       = parent::makeHttpRequest('post', $this->path . '/' . $id . '/refund');
        return new EasyPostShipment($response);
    }


}