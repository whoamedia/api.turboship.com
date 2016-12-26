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
    Route::get('/clients/{id}/services', 'ClientController@getServices');
    Route::post('/clients/{id}/services', 'ClientController@addService');
    Route::delete('/clients/{id}/services/{serviceId}', 'ClientController@removeService');


    //  Country Operations
    Route::get('/countries', 'CountryController@index');
    Route::get('/countries/{id}', 'CountryController@show');
    Route::get('/countries/{id}/subdivisions', 'CountryController@getCountrySubdivisions');


    //  IntegratedShoppingCart Operations
    Route::get('/integratedShoppingCarts', 'IntegratedShoppingCartController@index');
    Route::get('/integratedShoppingCarts/{id}', 'IntegratedShoppingCartController@show');
    Route::get('/integratedShoppingCarts/{id}/webHooks', 'IntegratedShoppingCartController@getWebHooks');
    Route::get('/integratedShoppingCarts/{id}/webHooks/available', 'IntegratedShoppingCartController@getAvailableWebHooks');
    Route::post('/integratedShoppingCarts/{id}/webHooks', 'IntegratedShoppingCartController@createWebHook');
    Route::delete('/integratedShoppingCarts/{id}/webHooks/{integratedWebHookId}', 'IntegratedShoppingCartController@deleteIntegratedWebHook');


    //  Integration Operations
    Route::get('/integrations/shippingApis', 'IntegrationController@getShippingApis');
    Route::get('/integrations/shoppingCarts', 'IntegrationController@getShoppingCarts');
    Route::get('/integrations/{id}', 'IntegrationController@show');
    Route::get('/integrations/{id}/credentials', 'IntegrationController@showIntegrationCredentials');
    Route::get('/integrations/{id}/webHooks', 'IntegrationController@getWebHooks');
    Route::get('/integrations/shippingApis/{id}/carriers', 'IntegrationController@getShippingApiCarriers');
    Route::get('/integrations/shippingApis/{id}/services', 'IntegrationController@getShippingApiServices');


    //  Order Operations
    Route::get('/orders', 'OrderController@index');
    Route::get('/orders/statuses', 'OrderController@getStatuses');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::get('/orders/{id}/approve', 'OrderController@approveIndividualOrder');



    //  Product Operations
    Route::get('/products', 'ProductController@index');


    //  Shipment Operations
    Route::get('/shipments', 'ShipmentController@index');
    Route::post('/shipments/jobs/create', 'ShipmentController@createShipmentsJob');


    //  Shipper Operations
    Route::get('/shippers', 'ShipperController@index');
    Route::get('/shippers/{id}', 'ShipperController@show');
    Route::get('/shippers/{id}/address', 'ShipperController@showAddress');
    Route::get('/shippers/{id}/returnAddress', 'ShipperController@showReturnAddress');
    Route::get('/shippers/{id}/clients', 'ShipperController@getClients');
    Route::post('/shippers/{id}/clients/{clientId}', 'ShipperController@addClient');
    Route::delete('/shippers/{id}/clients/{clientId}', 'ShipperController@removeClient');


    //  Shopify Operations
    Route::post('/shopify/{id}/orders/download', 'ShopifyController@downloadOrders');
    Route::post('/shopify/{id}/products/download', 'ShopifyController@downloadProducts');


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
