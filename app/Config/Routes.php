<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */


$routes->post('api/createdata', 'SampleDetails::create_data',['namespace' => 'App\Controllers\Offerdata']);
$routes->post('api/offerstatus', 'SampleDetails::updateoffer_status',['namespace' => 'App\Controllers\Offerdata']);


$routes->post('api/login', 'Login::login',['namespace' => 'App\Controllers\User']);






