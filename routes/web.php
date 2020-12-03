<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Getting secret key for env
$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

$router->group(['prefix' => 'api/'], function ($router) {
    $router->get('/login','UserController@authenticate');
	$router->group(['middleware' => 'auth'], function ($router) {
	    $router->get('/event', 'EventController@index');
	    $router->get('/event/{id}', 'EventController@show');
	    $router->post('/event', 'EventController@store');
	    $router->put('/event/{id}', 'EventController@update');
	    $router->delete('/event/{id}', 'EventController@delete');
	});
});