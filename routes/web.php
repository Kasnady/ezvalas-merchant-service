<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/version', function () use ($router) {
	// Framework Version
	return $router->app->version();
});

$router->group(['prefix' => 'merchants'], function () use ($router)
{
	$router->get('/nearby-geolocation',		'MerchantController@getNearbyGeolocation');
});