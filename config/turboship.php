<?php

return [

    'address'                   => [

        'usps'                  => [
            'userid'            => '842ATCOS7827',
            'validationEnabled'       => env('USPS_VALIDATION_ENABLED', false),
        ],

        'shopify'                  => [
            'webHookEnabled'    => env('SHOPIFY_WEBHOOKS_ENABLED', false),
            'baseUrl'           => config('app.url') . '/shopify'
        ],

    ],

];