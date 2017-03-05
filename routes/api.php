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

    //  ACL operations
    Route::get('/acl/permissions', 'ACLController@getPermissions');
    Route::get('/acl/permissions/lexicon', 'ACLController@getPermissionLexicon');
    Route::get('/acl/permissions/{id}', 'ACLController@showPermission');
    Route::get('/acl/roles', 'ACLController@getRoles');
    Route::post('/acl/roles', 'ACLController@createRole');
    Route::get('/acl/roles/{id}', 'ACLController@showRole');
    Route::put('/acl/roles/{id}', 'ACLController@updateRole');
    Route::get('/acl/roles/{id}/permissions', 'ACLController@getRolePermissions');


    //  Bin operations
    Route::get('/bins', 'BinController@index');
    Route::get('/bins/lexicon', 'BinController@getLexicon');
    Route::post('/bins', 'BinController@store');
    Route::get('/bins/{id}', 'BinController@show');
    Route::put('/bins/{id}', 'BinController@update');
    Route::get('/bins/{id}/inventory', 'BinController@getInventory');


    //  Carrier operations
    Route::get('/carriers', 'CarrierController@index');
    Route::get('/carriers/{id}', 'CarrierController@show');
    Route::get('/carriers/{id}/services', 'CarrierController@getServices');


    //  Cart operations
    Route::get('/carts', 'CartController@index');
    Route::get('/carts/{id}', 'CartController@show');


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
    Route::post('/integratedShoppingCarts', 'IntegratedShoppingCartController@store');
    Route::get('/integratedShoppingCarts/{id}', 'IntegratedShoppingCartController@show');
    Route::put('/integratedShoppingCarts/{id}', 'IntegratedShoppingCartController@update');
    Route::get('/integratedShoppingCarts/{id}/credentials', 'IntegratedShoppingCartController@getCredentials');
    Route::put('/integratedShoppingCarts/{id}/credentials', 'IntegratedShoppingCartController@updateCredentials');
    Route::get('/integratedShoppingCarts/{id}/webHooks', 'IntegratedShoppingCartController@getWebHooks');
    Route::get('/integratedShoppingCarts/{id}/webHooks/available', 'IntegratedShoppingCartController@getAvailableWebHooks');
    Route::post('/integratedShoppingCarts/{id}/webHooks', 'IntegratedShoppingCartController@createWebHook');
    Route::delete('/integratedShoppingCarts/{id}/webHooks/{integratedWebHookId}', 'IntegratedShoppingCartController@deleteIntegratedWebHook');


    //  IntegratedShippingApi Operations
    Route::get('/integratedShippingApis', 'IntegratedShippingApiController@index');
    Route::post('/integratedShippingApis', 'IntegratedShippingApiController@store');
    Route::put('/integratedShippingApis/{id}', 'IntegratedShippingApiController@update');
    Route::get('/integratedShippingApis/{id}', 'IntegratedShippingApiController@show');
    Route::get('/integratedShippingApis/{id}/credentials', 'IntegratedShippingApiController@getCredentials');
    Route::put('/integratedShippingApis/{id}/credentials', 'IntegratedShippingApiController@updateCredentials');
    Route::get('/integratedShippingApis/{id}/webHooks', 'IntegratedShippingApiController@getWebHooks');
    Route::get('/integratedShippingApis/{id}/webHooks/available', 'IntegratedShippingApiController@getAvailableWebHooks');


    //  Integration Operations
    Route::get('/integrations', 'IntegrationController@index');
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
    Route::get('/orders/{id}/providedShippingAddress', 'OrderController@getProvidedShippingAddress');
    Route::get('/orders/{id}/billingAddress', 'OrderController@getBillingAddress');
    Route::put('/orders/{id}/shippingAddress', 'OrderController@updateShippingAddress');
    Route::get('/orders/{id}/statusHistory', 'OrderController@getStatusHistory');
    Route::get('/orders/{id}/approve', 'OrderController@approveIndividualOrder');


    //  PortableBin operations
    Route::get('/portableBins', 'PortableBinController@index');
    Route::post('/portableBins', 'PortableBinController@store');
    Route::get('/portableBins/{id}', 'PortableBinController@show');
    Route::put('/portableBins/{id}', 'PortableBinController@update');
    Route::get('/portableBins/{id}/inventory', 'PortableBinController@getInventory');


    //  Postage Operations
    Route::get('/postage', 'PostageController@index');
    Route::get('/postage/lexicon', 'PostageController@getLexicon');
    Route::get('/postage/{id}', 'PostageController@show');


    //  Printer Operations
    Route::get('/printers', 'PrinterController@index');
    Route::post('/printers', 'PrinterController@store');
    Route::get('/printers/{id}', 'PrinterController@show');
    Route::put('/printers/{id}', 'PrinterController@update');
    Route::get('/printers/{id}/shipments/{shipmentId}/label', 'PrinterController@printShipmentLabel');
    Route::get('/printers/{id}/postage/{postageId}/label', 'PrinterController@printPostageLabel');
    Route::get('/printers/{id}/variants/{variantId}/barCode', 'PrinterController@printVariantBarCode');

    //  Product Operations
    Route::get('/products', 'ProductController@index');
    Route::get('/products/lexicon', 'ProductController@getLexicon');
    Route::get('/products/{id}/aliases', 'ProductController@getAliases');
    Route::get('/products/{id}/images', 'ProductController@getImages');
    Route::get('/products/{id}/variants', 'ProductController@getVariants');
    Route::get('/products/{id}/variants/{variantId}', 'ProductController@showVariant');
    Route::get('/products/{id}', 'ProductController@show');


    //  Reports
    Route::get('/reports/warehouse/activity', 'ReportController@getWarehouseActivityReport');


    //  Scanning operations
    Route::get('/scanning/bins/{barCode}', 'ScanningController@showBin');
    Route::get('/scanning/carts/{barCode}', 'ScanningController@showCart');
    Route::get('/scanning/portableBins/{barCode}', 'ScanningController@showPortableBin');
    Route::get('/scanning/staff/{barCode}', 'ScanningController@showStaff');
    Route::get('/scanning/totes/{barCode}', 'ScanningController@showTote');
    Route::get('/scanning/variants/{barCode}', 'ScanningController@showVariant');


    //  Service operations
    Route::get('/services', 'ServiceController@index');
    Route::get('/services/{id}', 'ServiceController@show');


    //  Shipment Operations
    Route::get('/shipments', 'ShipmentController@index');
    Route::get('/shipments/lexicon', 'ShipmentController@getLexicon');
    Route::get('/shipments/{id}', 'ShipmentController@show');
    Route::put('/shipments/{id}', 'ShipmentController@update');
    Route::get('/shipments/{id}/images', 'ShipmentController@getImages');
    Route::post('/shipments/{id}/images', 'ShipmentController@storeImages');
    Route::delete('/shipments/{id}/images/{imageId}', 'ShipmentController@deleteImage');
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
    Route::put('/shippers/{id}/address', 'ShipperController@updateAddress');
    Route::get('/shippers/{id}/returnAddress', 'ShipperController@showReturnAddress');
    Route::put('/shippers/{id}/returnAddress', 'ShipperController@updateReturnAddress');
    Route::get('/shippers/{id}/integratedShippingApis', 'ShipperController@getShippingApis');
    Route::get('/shippers/{id}/clients', 'ShipperController@getClients');
    Route::post('/shippers/{id}/clients/{clientId}', 'ShipperController@addClient');
    Route::delete('/shippers/{id}/clients/{clientId}', 'ShipperController@removeClient');


    //  ShippingContainer operations
    Route::get('/shippingContainers', 'ShippingContainerController@index');
    Route::get('/shippingContainers/lexicon', 'ShippingContainerController@getLexicon');
    Route::get('/shippingContainers/{id}', 'ShippingContainerController@show');
    Route::put('/shippingContainers/{id}', 'ShippingContainerController@update');
    Route::post('/shippingContainers', 'ShippingContainerController@create');


    //  ShippingContainer operations
    Route::get('/shippingStations', 'ShippingStationController@index');
    Route::get('/shippingStations/{id}', 'ShippingStationController@show');
    Route::put('/shippingStations/{id}', 'ShippingStationController@update');
    Route::post('/shippingStations', 'ShippingStationController@create');
    Route::get('/shippingStations/{id}/shipments/{shipmentId}/label', 'ShippingStationController@printShipmentPostageLabel');

    //  Shopify Operations
    Route::post('/shopify/{id}/orders/download', 'ShopifyController@downloadOrders');
    Route::post('/shopify/{id}/products/download', 'ShopifyController@downloadProducts');


    //  Subdivision Operations
    Route::get('/subdivisions', 'SubdivisionController@index');
    Route::get('/subdivisions/{id}', 'SubdivisionController@show');


    //  Support Operations
    Route::get('/support/sources', 'SupportController@getSources');
    Route::get('/support/orderStatuses', 'SupportController@getOrderStatuses');
    Route::get('/support/printerTypes', 'SupportController@getGetPrinterTypes');
    Route::get('/support/shipmentStatuses', 'SupportController@getShipmentStatuses');
    Route::get('/support/shippingContainerTypes', 'SupportController@getShippingContainerTypes');
    Route::get('/support/subdivisionTypes', 'SupportController@getSubdivisionTypes');


    //  Testing operations
    Route::get('/testing/variants/externalInventory', 'TestingController@syncVariantExternalInventory');
    Route::get('/testing/variants/inventory/bins', 'TestingController@transferVariantInventoryToBins');


    //  Tote operations
    Route::get('/totes', 'ToteController@index');
    Route::post('/totes', 'ToteController@store');
    Route::get('/totes/{id}', 'ToteController@show');
    Route::put('/totes/{id}', 'ToteController@update');
    Route::get('/totes/{id}/inventory', 'ToteController@getInventory');


    //  User Operations
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::get('/users/me', 'UserController@me');
    Route::get('/users/me/permissions', 'UserController@getMyPermissions');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{id}', 'UserController@update');
    Route::post('/users/{id}/image', 'UserController@updateImage');
    Route::get('/users/{id}/permissions', 'UserController@getPermissions');
    Route::post('/users/{id}/permissions', 'UserController@createPermissions');
    Route::put('/users/{id}/permissions', 'UserController@updatePermissions');
    Route::delete('/users/{id}/permissions/{permissionId}', 'UserController@deletePermission');
    Route::get('/users/{id}/roles', 'UserController@getRoles');
    Route::post('/users/{id}/roles', 'UserController@createRoles');
    Route::put('/users/{id}/roles', 'UserController@updateRoles');
    Route::delete('/users/{id}/roles/{roleId}', 'UserController@deleteRole');
    Route::put('/users/{id}/password', 'UserController@updatePassword');


    //  Variant Operations
    Route::get('/variants', 'VariantController@index');
    Route::get('/variants/lexicon', 'VariantController@getLexicon');
    Route::get('/variants/{id}', 'VariantController@show');
    Route::get('/variants/{id}/inventory', 'VariantController@getInventory');
    Route::get('/variants/{id}/inventory/locations', 'VariantController@getInventoryLocations');
    Route::post('/variants/{id}/inventory', 'VariantController@createInventory');
    Route::post('/variants/{id}/inventory/transfer', 'VariantController@transferInventory');


    //  VariantInventory Operations
    Route::get('/variantInventory', 'VariantInventoryController@index');

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
