<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
// $routes->get('home', 'Home::index');

$routes->post(
    'auth/sendRegistrationOtp',
    'Auth::sendRegistrationOtp'
);

$routes->post(
    'auth/verifyRegistrationOtp',
    'Auth::verifyRegistrationOtp'
);

$routes->post(
    'auth/register',
    'Auth::register'
);


$routes->post('auth/login', 'Auth::login');

$routes->get('auth/customerLogout', 'Auth::customerLogout');