<?php

return [
    'credentials'   => [
        'key'       => env('AWS_ACCESS_KEY_ID'),
        'secret'    => env('AWS_SECRET_ACCESS_KEY'),
    ],
    'region'        => env('AWS_REGION'),
    'version'       => 'latest',

    'bucket'        => env('AWS_S3_BUCKET_' . strtoupper(config('app.env'))),

    // You can override settings for specific services
    'Ses' => [
        'region' => 'us-east-1',
    ],
];