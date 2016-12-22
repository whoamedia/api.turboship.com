<?php

namespace Tests\Factories;


use App\Models\CMS\Validation\ClientValidation;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Utilities\CRMSourceUtility;
use App\Utilities\OrderStatusUtility;
use App\Models\Locations\Address;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Illuminate\Support\Str;

class OrderFactory
{

    /**
     * @var ClientValidation
     */
    private $clientValidation;

    /**
     * @var CRMSourceUtility
     */
    private $crmSourceUtility;

    /**
     * @var OrderStatusUtility
     */
    private $orderStatusUtility;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * OrderFactory constructor.
     */
    public function __construct()
    {
        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $this->crmSourceUtility         = new CRMSourceUtility();
        $this->orderStatusUtility       = new OrderStatusUtility();
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
    }

    /**
     * @return Order
     */
    public function getValidOrder ()
    {
        $client                         = $this->clientValidation->idExists(1);
        $shopify                        = $this->crmSourceUtility->getShopify();


        $order                          = new Order();
        $order->setExternalId(Str::random(32));
        $order->setCRMSource($shopify);
        $order->setClient($client);

        $numItems                       = rand(1, 5);

        for ($i = 0; $i < $numItems; $i++)
        {
            $order->addItem($this->getValidOrderItem());
        }

        $providedAddress                = $this->getSavannahCivicCenter();
        $order->setShippingAddress($providedAddress);

        return $order;
    }

    /**
     * @return OrderItem
     */
    public function getValidOrderItem ()
    {
        $orderItem                      = new OrderItem();
        $orderItem->setExternalId(Str::random(32));
        $orderItem->setExternalProductId(rand(500, 10000));
        $orderItem->setExternalVariantId(rand(500, 10000));
        $orderItem->setSku(Str::random(32));
        $orderItem->setQuantityToFulfill(rand(2, 10));
        $orderItem->setQuantityPurchased($orderItem->getQuantityToFulfill() + rand(1, 2));
        $orderItem->setBasePrice(rand(20, 100) . '.' . rand(10, 99));

        return $orderItem;
    }

    /**
     * @return Address
     */
    public function getSavannahCivicCenter ()
    {
        $providedAddress                = new Address();
        $providedAddress->setFirstName('John');
        $providedAddress->setLastName('Doe');
        $providedAddress->setStreet1('301 W Oglethorpe Ave');
        $providedAddress->setCity('Savannah');
        $providedAddress->setStateProvince('Georgia');
        $providedAddress->setPostalCode('31402');
        $providedAddress->setCountryCode('US');

        return $providedAddress;
    }
}