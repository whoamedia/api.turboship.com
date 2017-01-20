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

    //  Carrier operations
    Route::get('/carriers', 'CarrierController@index');
    Route::get('/carriers/{id}', 'CarrierController@show');
    Route::get('/carriers/{id}/services', 'CarrierController@getServices');


    //  Client Operations
    Route::get('/clients', 'ClientController@index');
    Route::post('/clients', 'ClientController@store');
    Route::get('/clients/{id}', 'ClientController@show');
    Route::put('/clients/{id}', 'ClientController@update');
    Route::get('/clients/{id}/options', 'ClientController@getOptions');
    Route::put('/clients/{id}/options', 'ClientController@updateOptions');
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
    Route::get('/integratedShoppingCarts/{id}/credentials', 'IntegratedShoppingCartController@getCredentials');
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


    //  Organization Operations
    Route::get('/organizations/{id}', 'OrganizationController@show');
    Route::put('/organizations/{id}', 'OrganizationController@update');


    //  Order Operations
    Route::get('/orders', 'OrderController@index');
    Route::get('/orders/lexicon', 'OrderController@getLexicon');
    Route::get('/orders/approve', 'OrderController@approveOrders');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::get('/orders/{id}/statusHistory', 'OrderController@getStatusHistory');
    Route::get('/orders/{id}/approve', 'OrderController@approveIndividualOrder');



    //  Product Operations
    Route::get('/products', 'ProductController@index');
    Route::get('/products/{id}/aliases', 'ProductController@getAliases');
    Route::get('/products/{id}/images', 'ProductController@getImages');
    Route::get('/products/{id}/variants', 'ProductController@getVariants');
    Route::get('/products/{id}', 'ProductController@show');


    //  Service operations
    Route::get('/services', 'ServiceController@index');
    Route::get('/services/{id}', 'ServiceController@show');


    //  Shipment Operations
    Route::get('/shipments', 'ShipmentController@index');
    Route::get('/shipments/lexicon', 'ShipmentController@getLexicon');
    Route::get('/shipments/{id}', 'ShipmentController@show');
    Route::put('/shipments/{id}', 'ShipmentController@update');
    Route::get('/shipments/{id}/images', 'ShipmentController@getImages');
    Route::get('/shipments/{id}/rates', 'ShipmentController@getRates');
    Route::post('/shipments/{id}/rates', 'ShipmentController@createRates');
    Route::get('/shipments/{id}/postage', 'ShipmentController@getPostage');
    Route::delete('/shipments/{id}/postage', 'ShipmentController@voidPostage');
    Route::post('/shipments/{id}/postage/{rateId}', 'ShipmentController@purchasePostage');


    //  Shipper Operations
    Route::get('/shippers', 'ShipperController@index');
    Route::get('/shippers/{id}', 'ShipperController@show');
    Route::put('/shippers/{id}', 'ShipperController@update');
    Route::get('/shippers/{id}/address', 'ShipperController@showAddress');
    Route::get('/shippers/{id}/returnAddress', 'ShipperController@showReturnAddress');
    Route::get('/shippers/{id}/integratedShippingApis', 'ShipperController@getShippingApis');
    Route::get('/shippers/{id}/clients', 'ShipperController@getClients');
    Route::post('/shippers/{id}/clients/{clientId}', 'ShipperController@addClient');
    Route::delete('/shippers/{id}/clients/{clientId}', 'ShipperController@removeClient');


    //  ShippingContainer operations
    Route::get('/shippingContainers', 'ShippingContainerController@index');
    Route::get('/shippingContainers/{id}', 'ShippingContainerController@show');
    Route::put('/shippingContainers/{id}', 'ShippingContainerController@update');
    Route::post('/shippingContainers', 'ShippingContainerController@create');


    //  Shopify Operations
    Route::post('/shopify/{id}/orders/download', 'ShopifyController@downloadOrders');
    Route::post('/shopify/{id}/products/download', 'ShopifyController@downloadProducts');


    //  Subdivision Operations
    Route::get('/subdivisions', 'SubdivisionController@index');
    Route::get('/subdivisions/{id}', 'SubdivisionController@show');


    //  Support Operations
    Route::get('/support/sources', 'SupportController@getSources');
    Route::get('/support/orderStatuses', 'SupportController@getOrderStatuses');
    Route::get('/support/shipmentStatuses', 'SupportController@getShipmentStatuses');
    Route::get('/support/subdivisionTypes', 'SupportController@getSubdivisionTypes');


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
