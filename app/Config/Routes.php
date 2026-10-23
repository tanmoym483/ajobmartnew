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

$routes->get('fetchpopularproduct', 'Product::fetchpopularproduct');

$routes->get('shared-images/(:any)', 'SharedImages::show/$1');


$routes->get('welcome-register', 'WelcomeRegistration::index');

$routes->post(
    'welcome-registration/send-otp',
    'WelcomeRegistration::sendOtp'
);

$routes->post(
    'welcome-registration/verify-otp',
    'WelcomeRegistration::verifyOtp'
);

$routes->post(
    'welcome-registration/register',
    'WelcomeRegistration::register'
);


$routes->get(
    'qr-code/download-pdf',
    'QrCodeController::downloadPdf'
);

$routes->group('cart', static function ($routes) {
    $routes->get('fetch', 'Cart::fetch');
    $routes->post('add', 'Cart::add');
    $routes->post('update', 'Cart::update');
    $routes->post('remove', 'Cart::remove');
});


$routes->get(
    'product-details/(:num)',
    'ProductDetails::index/$1'
);
