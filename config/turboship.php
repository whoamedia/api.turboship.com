<?php

return [

    'usps'                  => [
        'userId'            => '842ATCOS7827',
        'validationEnabled' => env('USPS_VALIDATION_ENABLED', false),
    ],

    'variants'              => [
        'readyInventoryDelay'   => env('VARIANT_READY_INVENTORY_DELAY', 60)
    ]

];