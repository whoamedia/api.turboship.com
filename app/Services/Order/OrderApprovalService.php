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
use App\Services\Address\ProvidedAddressService;
use App\Services\Address\USPSAddressService;
use App\Services\MappingExceptionService;
use App\Utilities\CRMSourceUtility;
use App\Utilities\OrderStatusUtility;
use EntityManager;

class OrderApprovalService
{

    /**
     * @var OrderStatusUtility
     */
    private $orderStatusUtility;

    /**
     * @var ProvidedAddressService
     */
    private $providedAddressService;

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
        $this->providedAddressService   = new ProvidedAddressService();
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
        if (!$this->processProvidedAddress($order))
            return $order;

        if (!$this->validateToAddress($order))
            return $order;

        if (!$this->mapOrderItemSkus($order))
            return $order;


        $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::PENDING_FULFILLMENT_ID);
        $order->addStatus($status);
        return $order;
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function processProvidedAddress (Order $order)
    {
        if (!is_null($order->getToAddress()))
            return true;

        $address                        = !is_null($order->getToAddress()) ? $order->getToAddress() : new Address();

        $street1                        = $order->getProvidedAddress()->getStreet1();
        if (is_null($street1) || empty(trim($street1)))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STREET_ID);
            $order->addStatus($status);
            return false;
        }
        $address->setStreet1($street1);
        $address->setStreet2($order->getProvidedAddress()->getStreet2());

        /**
         * Validate that the provided country is not empty and map it
         */
        $providedCountry                = $order->getProvidedAddress()->getCountry();
        if (is_null($providedCountry) || empty(trim($providedCountry)))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_COUNTRY_ID);
            $order->addStatus($status);
            return false;
        }
        else
        {
            $country                    = $this->countryRepo->getOneByWildCard($providedCountry);
            if (is_null($country))
            {
                $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_COUNTRY_ID);
                $order->addStatus($status);
                return false;
            }
        }

        /**
         * Validate the provided Subdivision is not empty and map it
         */
        $providedSubdivision            = $order->getProvidedAddress()->getSubdivision();
        if (is_null($providedSubdivision) || empty(trim($providedSubdivision)))
        {
            $status                     = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STATE_ID);
            $order->addStatus($status);
            return false;
        }
        else
        {
            $subdivision                = $this->subdivisionRepo->getOneByWildCard($providedSubdivision, $country->getId());
            if (is_null($subdivision))
            {
                $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STATE_ID);
                $order->addStatus($status);
                return false;
            }
            $address->setSubdivision($subdivision);
        }

        $address->setFirstName($order->getProvidedAddress()->getFirstName());
        $address->setLastName($order->getProvidedAddress()->getLastName());
        $address->setCompany($order->getProvidedAddress()->getCompany());
        $address->setCity($order->getProvidedAddress()->getCity());
        $address->setPostalCode($order->getProvidedAddress()->getPostalCode());
        $address->setPhone($order->getProvidedAddress()->getPhone());
        $address->setEmail($order->getProvidedAddress()->getEmail());

        $order->setToAddress($address);

        return true;
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function validateToAddress (Order $order)
    {
        //  Only ran in production
        if (config('app.env') != 'production')
            return true;

        $uspsAddressService             = new USPSAddressService();

        try
        {
            $uspsAddressService->validateAddress($order->getToAddress());

            return true;
        }
        catch (USPSApiErrorException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_ADDRESS_ID);
            $order->addStatus($status);
            return false;
        }
        catch (InvalidCityException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_CITY_ID);
            $order->addStatus($status);
            return false;
        }
        catch (InvalidSubdivisionException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_STATE_ID);
            $order->addStatus($status);
            return false;
        }
        catch (AddressNotFoundException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::INVALID_ADDRESS_ID);
            $order->addStatus($status);
            return false;
        }
        catch (MultipleAddressesFoundException $ex)
        {
            $status                 = $this->orderStatusRepo->getOneById(OrderStatusUtility::MULTIPLE_ADDRESSES_FOUND_ID);
            $order->addStatus($status);
            return false;
        }
    }

    /**
     * @param   Order $order
     * @return  bool
     */
    public function mapOrderItemSkus (Order $order)
    {
        $mappingExceptionService    = new MappingExceptionService();
        $mappingFailure             = false;
        foreach ($order->getItems() AS $orderItem)
        {
            $sku                    = $orderItem->getSku();

            if ($order->getCRMSource()->getId() == CRMSourceUtility::SHOPIFY_ID)
                $sku                = $mappingExceptionService->getShopifySku($order->getClient(), $sku, $orderItem->getExternalVariantTitle());

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