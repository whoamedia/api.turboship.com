<?php

namespace App\Services\Order;


use App\Exceptions\Address\AddressNotFoundException;
use App\Exceptions\Address\InvalidCityException;
use App\Exceptions\Address\InvalidSubdivisionException;
use App\Exceptions\Address\MultipleAddressesFoundException;
use App\Exceptions\Address\USPSApiErrorException;
use App\Models\Locations\Address;
use App\Models\OMS\Order;
use App\Repositories\Doctrine\Locations\CountryRepository;
use App\Repositories\Doctrine\Locations\SubdivisionRepository;
use App\Repositories\Doctrine\OMS\OrderStatusRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Services\Address\USPSAddressService;
use App\Services\Shopify\Mapping\ShopifyMappingExceptionService;
use App\Utilities\CountryUtility;
use App\Utilities\SourceUtility;
use App\Utilities\OrderStatusUtility;
use Respect\Validation\Validator as v;
use EntityManager;

class OrderApprovalService
{

    /**
     * @var OrderStatusUtility
     */
    private $orderStatusUtility;

    /**
     * @var CountryRepository
     */
    private $countryRepo;

    /**
     * @var SubdivisionRepository
     */
    private $subdivisionRepo;

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepo;

    /**
     * @var VariantRepository
     */
    private $variantRepo;



    public function __construct()
    {
        $this->orderStatusUtility       = new OrderStatusUtility();
        $this->countryRepo              = EntityManager::getRepository('App\Models\Locations\Country');
        $this->subdivisionRepo          = EntityManager::getRepository('App\Models\Locations\Subdivision');
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
    }

    /**
     * @param   Order $order
     * @return  Order
     */
    public function processOrder (Order $order)
    {
        if (!$order->canRunApprovalProcess())
            return $order;

        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "mapping addresses")');

        $this->mapAddresses($order->getProvidedAddress());
        $this->mapAddresses($order->getShippingAddress());
        $this->mapAddresses($order->getBillingAddress());

        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "mapOrderItemSkus")');
        if (!$this->mapOrderItemSkus($order))
            return $order;

        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "processShippingAddress")');
        if (!$this->processShippingAddress($order))
            return $order;

        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "validateShippingAddress")');
        if (!$this->validateShippingAddress($order))
            return $order;

        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "true")');

        $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::PENDING_FULFILLMENT_ID);
        $order->addStatus($status);

        return $order;
    }


    public function mapAddresses (Address $address)
    {
        if (is_null($address->getCountry()))
        {
            $providedCountry                = $address->getCountryCode();
            if (is_null($providedCountry) || empty(trim($providedCountry)))
                return false;
            else
            {
                $country                    = $this->countryRepo->getOneByWildCard($providedCountry);
                if (is_null($country))
                    return false;

                $address->setCountry($country);
            }
        }

        /**
         * Validate the provided Subdivision is not empty and map it
         * Only do this for US orders
         */
        if (is_null($address->getSubdivision()))
        {
            $providedSubdivision            = $address->getStateProvince();
            if ( (is_null($providedSubdivision) || empty(trim($providedSubdivision))) && $address->getCountry()->getId() == CountryUtility::UNITED_STATES)
                return false;

            $subdivision                    = $this->subdivisionRepo->getOneByWildCard($providedSubdivision, $address->getCountry()->getId());
            if (is_null($subdivision))
                return false;

            $address->setSubdivision($subdivision);
        }
        return true;
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function processShippingAddress (Order $order)
    {
        $address                        = $order->getShippingAddress();

        if (is_null($address->getStreet1()) || empty(trim($address->getStreet1())))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STREET_ID);
            $order->addStatus($status);
            return false;
        }


        if (is_null($address->getCountry()))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_COUNTRY_ID);
            $order->addStatus($status);
            return false;
        }

        /**
         * Validate the provided Subdivision is not empty and map it
         * Only do this for US orders
         */
        if ( is_null($address->getSubdivision()) && $address->getCountry()->getId() == CountryUtility::UNITED_STATES)
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STATE_ID);
            $order->addStatus($status);
            return false;
        }

        //  Use ClientOptions for defaultShipToPhone
        if (is_null($address->getPhone()) || empty(trim($address->getPhone())))
            $address->setPhone($order->getClient()->getOptions()->getDefaultShipToPhone());

        //  Ensure that the phone number is always set
        if (is_null($address->getPhone()) || empty(trim($address->getPhone())))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_PHONE_NUMBER);
            $order->addStatus($status);
            return false;
        }

        //  Ensure the phone number is valid
        if (!v::phone()->validate($address->getPhone()))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_PHONE_NUMBER);
            $order->addStatus($status);
            return false;
        }

        $order->setShippingAddress($address);

        return true;
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function validateShippingAddress (Order $order)
    {
        $statusId = config('turboship.address.usps.validationEnabled') == true ? 1 : 0;
        \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $statusId . '")');
        //  Only run in production for US orders
        if (config('turboship.address.usps.validationEnabled') == false)
            return true;
        if ($order->getShippingAddress()->getCountry()->getId() != CountryUtility::UNITED_STATES)
            return true;

        $uspsAddressService             = new USPSAddressService();

        try
        {
            $uspsAddressService->validateAddress($order->getShippingAddress());
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . 0 . '")');
            return true;
        }
        catch (USPSApiErrorException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_ADDRESS_ID);
            $order->addStatus($status);
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $status->getId() . '")');
            return false;
        }
        catch (InvalidCityException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_CITY_ID);
            $order->addStatus($status);
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $status->getId() . '")');
            return false;
        }
        catch (InvalidSubdivisionException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STATE_ID);
            $order->addStatus($status);
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $status->getId() . '")');
            return false;
        }
        catch (AddressNotFoundException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_ADDRESS_ID);
            $order->addStatus($status);
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $status->getId() . '")');
            return false;
        }
        catch (MultipleAddressesFoundException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::MULTIPLE_ADDRESSES_FOUND_ID);
            $order->addStatus($status);
            \DB::insert('INSERT INTO USPS (orderId, message) VALUES (' . $order->getId() . ', "' . $status->getId() . '")');
            return false;
        }
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function mapOrderItemSkus (Order $order)
    {
        $mappingExceptionService    = new ShopifyMappingExceptionService();
        $mappingFailure             = false;
        foreach ($order->getItems() AS $orderItem)
        {
            //  Don't map the Variant if it's already mapped
            if (!is_null($orderItem->getVariant()))
                continue;

            $sku                    = $orderItem->getSku();

            if ($order->getSource()->getId() == SourceUtility::SHOPIFY_ID)
                $sku                = $mappingExceptionService->getShopifySku($order->getClient(), $sku, $orderItem->getExternalVariantId());

            $variantQuery   = [
                'clientIds'         => $order->getClient()->getId(),
                'skus'              => $sku,
            ];

            $variantResult          = $this->variantRepo->where($variantQuery);

            $variant                = sizeof($variantResult) == 1 ? $variantResult[0] : null;

            if (!is_null($variant))
                $orderItem->setVariant($variant);
            else
                $mappingFailure     = true;
        }

        if ($mappingFailure == true)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::UNMAPPED_SKU);
            $order->addStatus($status);
        }

        return !$mappingFailure;
    }



}