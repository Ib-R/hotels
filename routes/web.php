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

$router->post('/', ['as' => 'hotels', 'middleware' => 'limit:10,1', 'uses' => 'HotelController@index']);

// Mocking the providers APIs
$router->post('/tophotels', ['as' => 'top-hotels', 'uses' => 'TopHotelController@index']);
$router->post('/besthotel', ['as' => 'best-hotel', 'uses' => 'BestHotelController@index']);
