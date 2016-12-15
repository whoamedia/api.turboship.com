<?php

namespace Tests\Factories;


use App\Models\CMS\Validation\ClientValidation;
use App\Models\Locations\ProvidedAddress;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Utilities\OrderSourceUtility;
use App\Utilities\OrderStatusUtility;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Illuminate\Support\Str;

class OrderFactory
{

    /**
     * @var ClientValidation
     */
    private $clientValidation;

    /**
     * @var OrderSourceUtility
     */
    private $orderSourceUtility;

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
        $this->orderSourceUtility       = new OrderSourceUtility();
        $this->orderStatusUtility       = new OrderStatusUtility();
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
    }

    /**
     * @return Order
     */
    public function getValidOrder ()
    {
        $client                         = $this->clientValidation->idExists(1);
        $shopify                        = $this->orderSourceUtility->getShopify();


        $order                          = new Order();
        $order->setExternalId(Str::random(32));
        $order->setSource($shopify);
        $order->setClient($client);

        $numItems                       = rand(1, 5);

        for ($i = 0; $i < $numItems; $i++)
        {
            $order->addItem($this->getValidOrderItem());
        }

        $providedAddress                = $this->getSavannahCivicCenter();
        $order->setProvidedAddress($providedAddress);

        return $order;
    }

    /**
     * @return OrderItem
     */
    public function getValidOrderItem ()
    {
        $orderItem                      = new OrderItem();
        $orderItem->setExternalId(Str::random(32));
        $orderItem->setSku(Str::random(32));
        $orderItem->setQuantity(rand(2, 10));
        $orderItem->setDeclaredValue(rand(20, 100) . '.' . rand(10, 99));

        return $orderItem;
    }

    /**
     * @return ProvidedAddress
     */
    public function getSavannahCivicCenter ()
    {
        $providedAddress                = new ProvidedAddress();
        $providedAddress->setFirstName('John');
        $providedAddress->setLastName('Doe');
        $providedAddress->setStreet1('301 W Oglethorpe Ave');
        $providedAddress->setCity('Savannah');
        $providedAddress->setSubdivision('Georgia');
        $providedAddress->setPostalCode('31402');
        $providedAddress->setCountry('US');

        return $providedAddress;
    }
}