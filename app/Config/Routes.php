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
    $routes->get('/', 'Cart::index');
    $routes->get('fetch', 'Cart::fetch');
    $routes->post('add', 'Cart::add');
    $routes->post('update', 'Cart::update');
    $routes->post('remove', 'Cart::remove');
    $routes->get('cashback-balance', 'Cart::cashbackBalance');
});

$routes->post('cart/update-wow-offer', 'Cart::updateWowOffer');
$routes->post('cart/sync-guest', 'Cart::syncGuestCart');


$routes->get(
    'product-details/(:num)',
    'ProductDetails::index/$1'
);


$routes->get(
    'shipping/customer-addresses',
    'Shipping::customerAddresses'
);

$routes->get('shipping/customer-address', 'Shipping::customerAddress');
$routes->post('shipping/estimate', 'Shipping::estimate');
$routes->post('shipping/save-address', 'Shipping::saveAddress');


$routes->get('checkout', 'Checkout::index');
// $routes->post('checkout/payment/verify', 'Checkout::verifyPayment');


$routes->post('checkout/place-order', 'Checkout::placeOrder');
$routes->post('checkout/razorpay/verify', 'Checkout::verifyRazorpayPayment');
$routes->get('checkout/payment-success', 'Checkout::paymentSuccess');
$routes->get('checkout/payment-failed', 'Checkout::paymentFailed');


$routes->get(
    'shipping/testShadowfax',
    'Shipping::testShadowfax'
);



$routes->get('my-orders', 'Orders::index');


$routes->group('home-products', static function ($routes) {
    $routes->get('new-arrivals', 'HomeProducts::newArrivals');
    $routes->get('best-deals', 'HomeProducts::bestDeals');
    $routes->get('trending', 'HomeProducts::trending');

    $routes->get('under-99', 'HomeProducts::under99');
    $routes->get('under-199', 'HomeProducts::under199');
    $routes->get('discount-30', 'HomeProducts::discount30');
    $routes->get('discount-50', 'HomeProducts::discount50');
    $routes->get('more-products', 'HomeProducts::moreProducts');
});
$routes->get('home-products/category/(:num)', 'HomeProducts::byCategory/$1');