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
                'name'          => 'CLIENT.CREATE',
                'description'   => 'Create Clients',
            ],
            [
                'name'          => 'CLIENT.EDIT',
                'description'   => 'Edit Clients',
            ],
            [
                'name'          => 'CLIENT.VIEW',
                'description'   => 'View Clients',
            ],


            [
                'name'          => 'INVENTORY.CREATE',
                'description'   => 'Create Inventory',
            ],
            [
                'name'          => 'INVENTORY.EDIT',
                'description'   => 'Edit Inventory',
            ],
            [
                'name'          => 'INVENTORY.VIEW',
                'description'   => 'View Inventory',
            ],


            [
                'name'          => 'ORDER.CREATE',
                'description'   => 'Create Orders',
            ],
            [
                'name'          => 'ORDER.EDIT',
                'description'   => 'Edit Orders',
            ],
            [
                'name'          => 'ORDER.VIEW',
                'description'   => 'View Orders',
            ],


            [
                'name'          => 'PRODUCT.CREATE',
                'description'   => 'Create Products',
            ],
            [
                'name'          => 'PRODUCT.EDIT',
                'description'   => 'Edit Products',
            ],
            [
                'name'          => 'PRODUCT.VIEW',
                'description'   => 'View Products',
            ],


            [
                'name'          => 'SHIPMENT.CREATE',
                'description'   => 'Create Shipments',
            ],
            [
                'name'          => 'SHIPMENT.EDIT',
                'description'   => 'Edit Shipments',
            ],
            [
                'name'          => 'SHIPMENT.VIEW',
                'description'   => 'View Shipments',
            ],


            [
                'name'          => 'USER.CREATE',
                'description'   => 'Create Users',
            ],
            [
                'name'          => 'USER.EDIT',
                'description'   => 'Edit Users',
            ],
            [
                'name'          => 'USER.VIEW',
                'description'   => 'View Users',
            ],
        ];
    }
}
