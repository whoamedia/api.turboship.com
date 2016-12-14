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


    //  Country Operations
    Route::get('/countries', 'CountryController@index');
    Route::get('/countries/{id}', 'CountryController@show');
    Route::get('/countries/{id}/subdivisions', 'CountryController@getCountrySubdivisions');


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
