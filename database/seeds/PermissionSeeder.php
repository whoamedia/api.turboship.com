<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Permission')->insert(
            $this->getPermissions()
        );
    }

    private function getPermissions ()
    {
        return [
            [
                'entity'        => 'Client',
                'name'          => 'CLIENT.CREATE',
                'description'   => 'Create Clients',
            ],
            [
                'entity'        => 'Client',
                'name'          => 'CLIENT.EDIT',
                'description'   => 'Edit Clients',
            ],
            [
                'entity'        => 'Client',
                'name'          => 'CLIENT.VIEW',
                'description'   => 'View Clients',
            ],


            [
                'entity'        => 'Inventory',
                'name'          => 'INVENTORY.CREATE',
                'description'   => 'Create Inventory',
            ],
            [
                'entity'        => 'Inventory',
                'name'          => 'INVENTORY.EDIT',
                'description'   => 'Edit Inventory',
            ],
            [
                'entity'        => 'Inventory',
                'name'          => 'INVENTORY.VIEW',
                'description'   => 'View Inventory',
            ],


            [
                'entity'        => 'Order',
                'name'          => 'ORDER.CREATE',
                'description'   => 'Create Orders',
            ],
            [
                'entity'        => 'Order',
                'name'          => 'ORDER.EDIT',
                'description'   => 'Edit Orders',
            ],
            [
                'entity'        => 'Order',
                'name'          => 'ORDER.VIEW',
                'description'   => 'View Orders',
            ],


            [
                'entity'        => 'Product',
                'name'          => 'PRODUCT.CREATE',
                'description'   => 'Create Products',
            ],
            [
                'entity'        => 'Product',
                'name'          => 'PRODUCT.EDIT',
                'description'   => 'Edit Products',
            ],
            [
                'entity'        => 'Product',
                'name'          => 'PRODUCT.VIEW',
                'description'   => 'View Products',
            ],


            [
                'entity'        => 'Shipment',
                'name'          => 'SHIPMENT.CREATE',
                'description'   => 'Create Shipments',
            ],
            [
                'entity'        => 'Shipment',
                'name'          => 'SHIPMENT.EDIT',
                'description'   => 'Edit Shipments',
            ],
            [
                'entity'        => 'Shipment',
                'name'          => 'SHIPMENT.VIEW',
                'description'   => 'View Shipments',
            ],


            [
                'entity'        => 'Shipper',
                'name'          => 'SHIPPER.CREATE',
                'description'   => 'Create Shippers',
            ],
            [
                'entity'        => 'Shipper',
                'name'          => 'SHIPPER.EDIT',
                'description'   => 'Edit Shippers',
            ],
            [
                'entity'        => 'Shipper',
                'name'          => 'SHIPPER.VIEW',
                'description'   => 'View Shippers',
            ],


            [
                'entity'        => 'Shipping API',
                'name'          => 'SHIPPING_API.CREATE',
                'description'   => 'Create Shipping Integrations',
            ],
            [
                'entity'        => 'Shipping API',
                'name'          => 'SHIPPING_API.EDIT',
                'description'   => 'Edit Shipping Integrations',
            ],
            [
                'entity'        => 'Shipping API',
                'name'          => 'SHIPPING_API.VIEW',
                'description'   => 'View Shipping Integrations',
            ],

            [
                'entity'        => 'Shopping Cart',
                'name'          => 'SHOPPING_CART_API.CREATE',
                'description'   => 'Create Shopping Cart Integrations',
            ],
            [
                'entity'        => 'Shopping Cart',
                'name'          => 'SHOPPING_CART_API.EDIT',
                'description'   => 'Edit Shopping Cart Integrations',
            ],
            [
                'entity'        => 'Shopping Cart',
                'name'          => 'SHOPPING_CART_API.VIEW',
                'description'   => 'View Shopping Cart Integrations',
            ],


            [
                'entity'        => 'User',
                'name'          => 'USER.CREATE',
                'description'   => 'Create Users',
            ],
            [
                'entity'        => 'User',
                'name'          => 'USER.EDIT',
                'description'   => 'Edit Users',
            ],
            [
                'entity'        => 'User',
                'name'          => 'USER.VIEW',
                'description'   => 'View Users',
            ],

            [
                'entity'        => 'Staff',
                'name'          => 'STAFF.CREATE',
                'description'   => 'Create Staff',
            ],
            [
                'entity'        => 'Staff',
                'name'          => 'STAFF.EDIT',
                'description'   => 'Edit Staff',
            ],
            [
                'entity'        => 'Staff',
                'name'          => 'STAFF.VIEW',
                'description'   => 'View Staff',
            ],
        ];
    }
}
