<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/oauth/access_token',
    ['uses' => 'OAuthController@issueAccessToken',]);


Route::group(['middleware' => ['oauth', 'oAuthUser']], function ()
{

    //  Client Operations
    Route::get('/clients', 'ClientController@index');
    Route::get('/clients/{id}', 'ClientController@show');
    Route::put('/clients/{id}', 'ClientController@update');
    Route::post('/clients', 'ClientController@store');
    Route::get('/clients/{id}/integrations/shipping', 'ClientController@getShippingIntegrations');
    Route::post('/clients/{id}/integrations/shipping', 'ClientController@createShippingIntegration');

    Route::get('/clients/{id}/integrations/eCommerce', 'ClientController@getECommerceIntegrations');
    Route::post('/clients/{id}/integrations/eCommerce', 'ClientController@createECommerceIntegration');



    //  ClientIntegration Operations
    Route::get('/clientIntegrations/{id}/webHooks', 'ClientIntegrationController@getWebHooks');
    Route::get('/clientIntegrations/{id}/availableWebHooks', 'ClientIntegrationController@getAvailableWebHooks');
    Route::post('/clientIntegrations/{id}/webHooks', 'ClientIntegrationController@createWebHook');
    Route::delete('/clientIntegrations/{id}/webHooks/{clientWebHookId}', 'ClientIntegrationController@deleteWebHook');


    //  Country Operations
    Route::get('/countries', 'CountryController@index');
    Route::get('/countries/{id}', 'CountryController@show');
    Route::get('/countries/{id}/subdivisions', 'CountryController@getCountrySubdivisions');


    //  Integration Operations
    Route::get('/integrations', 'IntegrationController@index');
    Route::get('/integrations/{id}/webHooks', 'IntegrationController@getWebHooks');


    //  Order Operations
    Route::get('/orders', 'OrderController@index');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::get('/orders/{id}/approve', 'OrderController@approveIndividualOrder');


    //  Product Operations
    Route::get('/products', 'ProductController@index');


    //  Subdivision Operations
    Route::get('/subdivisions', 'SubdivisionController@index');
    Route::get('/subdivisions/{id}', 'SubdivisionController@show');


    //  User Operations
    Route::get('/users', 'UserController@index');
    Route::get('/users/me', 'UserController@me');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{id}', 'UserController@update');
    Route::post('/users', 'UserController@store');
    Route::put('/users/{id}/password', 'UserController@updatePassword');

});


Route::post('/webhooks/shopify/{id}/orders/cancelled',
    'ShopifyWebHooks\ShopifyOrderController@cancelOrder');

Route::post('/webhooks/shopify/{id}/orders/create',
    'ShopifyWebHooks\ShopifyOrderController@createOrder');

Route::post('/webhooks/shopify/{id}/orders/delete',
    'ShopifyWebHooks\ShopifyOrderController@deleteOrder');

Route::post('/webhooks/shopify/{id}/orders/paid',
    'ShopifyWebHooks\ShopifyOrderController@orderPaid');

Route::post('/webhooks/shopify/{id}/orders/updated',
    'ShopifyWebHooks\ShopifyOrderController@orderUpdated');

Route::post('/webhooks/shopify/{id}/products/create',
    'ShopifyWebHooks\ShopifyProductController@createProduct');

Route::post('/webhooks/shopify/{id}/products/delete',
    'ShopifyWebHooks\ShopifyProductController@deleteProduct');

Route::post('/webhooks/shopify/{id}/products/update',
    'ShopifyWebHooks\ShopifyProductController@updateProduct');
