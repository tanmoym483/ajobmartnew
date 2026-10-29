<?php
$isCustomerLoggedIn = session()->get('ajobmart_customer_logged_in');
$customerName = session()->get('ajobmart_customer_name') ?? 'Customer';

// Use a default image when the customer has no uploaded profile image.
$customerImage = session()->get('ajobmart_customer_image')
    ?: base_url('assets/images/default-user.png');
?>

<?php
// Coupon / cashback balance
$couponAmount = 0;

if ($isCustomerLoggedIn) {
    // Replace this with the actual database balance when available.
    $couponAmount = (float) (session()->get('ajobmart_customer_coupon_amount') ?? 0);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= esc($title ?? 'Ajobmart') ?>
    </title>

    <link rel="icon" type="image/png"
      href="<?= base_url('public/assets/favicon/android-chrome-512x512.png') . '?v=2'; ?>">

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Ajobmart CSS -->
    <!-- <link
        rel="stylesheet"
        href="<?= base_url('public/assets/css/ajobmart.css') ?>"
    > -->
    <style>
    
#ajOnlinePaymentOptions label {
    transition: border-color .2s, background-color .2s;
    border-color: #dee2e6 !important;
}

#ajOnlinePaymentOptions label:has(input:checked) {
    border-color: #0d6efd !important;
    background-color: #f0f6ff;
}

#ajOnlinePaymentOptions img {
    flex-shrink: 0;
    border-radius: 6px;
}



/* ==========================================
   AJOBMART - ANIMATED CHECKOUT BACKGROUND
========================================== */

body {
    background: linear-gradient(
        -45deg,
        #eef4ff,
        #f8f3ff,
        #eafaf7,
        #fff5eb
    );
    background-size: 400% 400%;
    animation: ajCheckoutBackground 16s ease infinite;
    color: #212529;
}

@keyframes ajCheckoutBackground {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Checkout cards */
.container.py-4 .card {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 16px;
    box-shadow: 0 8px 28px rgba(40, 55, 90, 0.08);
    overflow: hidden;
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        border-color 0.3s ease;
    animation: ajCardAppear 0.65s ease both;
}

/* Gentle hover effect */
@media (hover: hover) {
    .container.py-4 .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 34px rgba(40, 55, 90, 0.13);
        border-color: rgba(99, 102, 241, 0.22);
    }
}

@keyframes ajCardAppear {
    from {
        opacity: 0;
        transform: translateY(14px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Card headings */
.container.py-4 .card-header {
    background: linear-gradient(110deg, #ffffff, #f5f7ff);
    color: #26344a;
    border-bottom: 1px solid #edf0f5;
    padding: 16px 18px;
}

/* Card content */
.container.py-4 .card-body {
    padding: 18px;
}

/* Animated gateway selection cards */
#ajOnlinePaymentOptions label,
#ajPaymentMethodCard label[for="ajPaymentCod"] {
    background: #ffffff;
    border: 1px solid #e1e7f0 !important;
    border-radius: 12px;
    transition:
        background 0.25s ease,
        border-color 0.25s ease,
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

#ajOnlinePaymentOptions label:hover,
#ajPaymentMethodCard label[for="ajPaymentCod"]:hover {
    background: #f7f9ff;
    border-color: #a5b4fc !important;
    transform: translateX(3px);
}

#ajOnlinePaymentOptions label:has(input:checked) {
    background: linear-gradient(110deg, #eef4ff, #f8f5ff);
    border-color: #6366f1 !important;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.12);
}

/* Gateway logos */
#ajOnlinePaymentOptions img {
    flex-shrink: 0;
    border-radius: 8px;
    background: #fff;
    padding: 3px;
}

/* Product image thumbnails */
.container.py-4 .card-body img {
    background: #fff;
}

/* Order summary emphasis */
.container.py-4 .card-body hr {
    border-color: #e7eaf0;
    opacity: 1;
}

/* Checkout button */
#ajCheckoutForm button[type="submit"] {
    min-height: 48px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    background: linear-gradient(100deg, #2563eb, #6d5ce8);
    box-shadow: 0 5px 14px rgba(59, 91, 219, 0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

#ajCheckoutForm button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 91, 219, 0.28);
}

/* Mobile layout */
@media (max-width: 576px) {
    .container.py-4 {
        padding-top: 16px !important;
    }

    .container.py-4 .card {
        border-radius: 13px;
    }

    .container.py-4 .card-body {
        padding: 14px;
    }
}

/* Accessibility: respect reduced-motion preferences */
@media (prefers-reduced-motion: reduce) {
    body,
    .container.py-4 .card {
        animation: none !important;
    }

    .container.py-4 .card,
    #ajOnlinePaymentOptions label,
    #ajPaymentMethodCard label[for="ajPaymentCod"],
    #ajCheckoutForm button[type="submit"] {
        transition: none !important;
    }
}



</style>

    <style>

        #ajobmart-splash {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.4s ease, visibility 0.4s ease;
}

#ajobmart-splash.hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.splash-content {
    text-align: center;
    animation: splashAppear 0.7s ease;
}

.splash-logo {
    width: 110px;
    height: 110px;
    object-fit: contain;
    border-radius: 20px;
}

.splash-content h1 {
    margin: 15px 0 5px;
    font-size: 32px;
    font-weight: 800;
    color: #ed6b23;
}

.splash-content p {
    color: #666;
    font-size: 14px;
}

.splash-loader {
    width: 35px;
    height: 35px;
    margin: 22px auto 0;
    border: 4px solid #f3d9ca;
    border-top-color: #ed6b23;
    border-radius: 50%;
    animation: splashSpin 0.8s linear infinite;
}

@keyframes splashSpin {
    to { transform: rotate(360deg); }
}

@keyframes splashAppear {
    from {
        opacity: 0;
        transform: scale(0.92);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
    </style>
</head>

<body>





<div>
<?= $content ?>
</div>




<div id="ajobmart-splash">
    <div class="splash-content">
        <img
            src="<?= base_url('public/assets/favicon/android-chrome-512x512.png') ?>"
            alt="Ajobmart Logo"
            class="splash-logo"
        >
        <h1>Ajobmart</h1>
        <p>Your shopping destination</p>

        <div class="splash-loader"></div>
    </div>
</div>


<script>
const site_url = <?= json_encode(rtrim(site_url(), '/') . '/') ?>;

window.AJ_CART_CONFIG = {
    loggedIn: <?= session()->get('ajobmart_customer_logged_in') ? 'true' : 'false' ?>,

    fetchUrl: <?= json_encode(site_url('cart/fetch')) ?>,
    addUrl: <?= json_encode(site_url('cart/add')) ?>,
    updateUrl: <?= json_encode(site_url('cart/update')) ?>,
    removeUrl: <?= json_encode(site_url('cart/remove')) ?>,
    checkoutUrl: <?= json_encode(site_url('checkout')) ?>,

    shippingCustomerAddressUrl:
        <?= json_encode(site_url('shipping/customer-address')) ?>,

    shippingCustomerAddressesUrl:
    <?= json_encode(site_url('shipping/customer-addresses')) ?>,

    shippingEstimateUrl:
        <?= json_encode(site_url('shipping/estimate')) ?>,

    shippingSaveAddressUrl:
        <?= json_encode(site_url('shipping/save-address')) ?>,

    csrfName: <?= json_encode(csrf_token()) ?>,
    csrfHash: <?= json_encode(csrf_hash()) ?>,
    csrfHeader: 'X-CSRF-TOKEN'
};
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= base_url('public/assets/js/ajobmart-cart.js') ?>"></script>
<script src="<?= base_url('public/assets/js/ajobmart.js') ?>"></script>
<script src="<?= base_url('public/assets/js/aj-shipping.js') ?>"></script>


</body>

</html>