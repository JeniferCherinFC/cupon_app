<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */


// $routes->post('api/createdata', 'SampleDetails::create_data',['namespace' => 'App\Controllers\Offerdata']);
// $routes->post('api/offerstatus', 'SampleDetails::updateoffer_status',['namespace' => 'App\Controllers\Offerdata']);

//user
$routes->post('api/login', 'UserController::login',['namespace' => 'App\Controllers\User']);
$routes->post('api/getcoupondetails', 'UserController::get_coupondetails',['namespace' => 'App\Controllers\User']);
$routes->post('api/getAcoupons', 'UserController::get_couponAvailable_details',['namespace' => 'App\Controllers\User']);
$routes->post('api/getUcoupons', 'UserController::get_couponUsed_details',['namespace' => 'App\Controllers\User']);
$routes->post('api/getSubdetails', 'UserController::get_subscription_details',['namespace' => 'App\Controllers\User']);
$routes->post('api/changepassword', 'UserController::changepassword',['namespace' => 'App\Controllers\User']);
$routes->post('api/logout', 'UserController::logout',['namespace' => 'App\Controllers\User']);

//admin
$routes->post('api/adlogin', 'AdminController::admin_login',['namespace' => 'App\Controllers\Admin']);
$routes->post('api/usercreation', 'AdminController::create_user',['namespace' => 'App\Controllers\Admin']);



//biller
$routes->post('api/bilogin', 'BillerController::biller_login',['namespace' => 'App\Controllers\Biller']);



//captain
$routes->post('api/caplogin', 'CaptainController::captain_login',['namespace' => 'App\Controllers\Captain']);


$routes->get('api/qrcode', 'AdminController::generateQRCode',['namespace' => 'App\Controllers\Admin']);



