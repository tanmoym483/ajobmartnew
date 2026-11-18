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
    <link
        rel="stylesheet"
        href="<?= base_url('public/assets/css/ajobmart.css') ?>"
    >
    <link
        rel="stylesheet"
        href="<?= base_url('public/assets/css/ajobmarthome.css') ?>"
    >
    <link
        rel="stylesheet"
        href="<?= base_url('public/assets/css/style.css') ?>"
    >

    <style>
        .aj-global-delivery-bar {
    width: 100%;
    background: transparent;
}

.aj-global-delivery-bar .aj-delivery-card {
    max-width: 1400px;
    margin: 0 auto !important;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: transparent;
    box-shadow: 0 2px 5px rgba(0,0,0,0.04);
} 
    
    .aj-delivery-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px 20px;
    background: linear-gradient(110deg, #ffffff, #f5f7ff);
}

.aj-delivery-icon {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 20px;
}

    .aj-address-changes {
        cursor: pointer;
        display: block;
        text-decoration: none;
        color: inherit;
        width: 100%;
    }

.aj-address-changes:hover {
    color: inherit;
    text-decoration: none;
}
.aj-global-delivery-bar .aj-delivery-card {
    transition: background 0.2s ease, box-shadow 0.2s ease;
}

.aj-address-changes:hover .aj-delivery-card {
    background: #f9fafb;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}


.aj-google-shipping-modal .modal-body {
    overflow-x: hidden;
}

.aj-google-map-wrapper {
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #f8f9fa;
}

#ajGoogleMap {
    width: 100%;
    height: 280px;
}

.aj-google-map-suggestions {
    position: relative;
    z-index: 1085;
    max-height: 230px;
    overflow-y: auto;
    margin-top: 4px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(0,0,0,.10);
}

.aj-google-map-suggestions button {
    display: block;
    width: 100%;
    padding: 11px 13px;
    text-align: left;
    border: 0;
    border-bottom: 1px solid #f0f0f0;
    background: #fff;
    color: #202a35;
}

.aj-google-map-suggestions button:hover {
    background: #f7f7f7;
}

.aj-google-map-suggestions button:last-child {
    border-bottom: 0;
}

#ajGoogleMapSearch {
    min-width: 0;
}

#ajShippingMessage {
    overflow-wrap: anywhere;
}

    </style>
</head>

<body>

<?= view('layouts/header', [
    'isCustomerLoggedIn' => $isCustomerLoggedIn,
    'customerName'       => $customerName,
    'customerImage'      => $customerImage,
    'couponAmount'       => $couponAmount,
]) ?>

<!-- DELIVERY ADDRESS SECTION -->
<div class="aj-global-delivery-bar">

    <a id="ajCartDeliverySelector"
      class="aj-address-changes">

        <div class="aj-cart-card aj-delivery-card mb-3">

            <div class="aj-delivery-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>

            <div class="flex-grow-1">
                <span class="aj-section-label">DELIVER TO</span>

                <div id="ajCartDeliveryAddress">
                    Select delivery address
                </div>
            </div>

            <i class="bi bi-chevron-right"></i>

        </div>
    </a>
</div>

<!-- SEARCH HEADER -->
<?= view('layouts/searchheader') ?>

<div>
    <?= $content ?>
</div>

<?= view('layouts/footer') ?>

<?= view('layouts/mobile_bottom_nav', [
    'isCustomerLoggedIn' => $isCustomerLoggedIn,
    'customerName'       => $customerName,
    'customerImage'      => $customerImage,
    'couponAmount'       => $couponAmount,
]) ?>


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

<div
    class="modal fade aj-profile-modal"
    id="profileModal"
    tabindex="-1"
    aria-labelledby="profileModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">

        <div class="modal-content">

            
            <?php
            $isCustomerLoggedIn = session()->get('ajobmart_customer_logged_in');
            $customerName = session()->get('ajobmart_customer_name') ?? 'Customer';
            $customerEmail = session()->get('ajobmart_customer_email') ?? '';
            ?>

            <div class="aj-profile-modal-header">
                <div>
                    <h5 id="profileModalLabel">
                        <?= $isCustomerLoggedIn
                            ? 'Hello, ' . esc($customerName)
                            : 'My Account' ?>
                    </h5>

                    <p>
                        <?= $isCustomerLoggedIn
                            ? esc($customerEmail)
                            : 'Manage your Ajobmart account' ?>
                    </p>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>


            <div class="aj-profile-modal-body">

            <?php if (!$isCustomerLoggedIn): ?>

                <a
                    href="javascript:void(0);"
                    class="aj-profile-option"
                    id="ajLoginRegisterBtn"
                >

                    <div class="aj-profile-option-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>

                    <div class="aj-profile-option-content">

                        <strong>
                            Login / Register
                        </strong>

                        <span>
                            Access your Ajobmart account
                        </span>

                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>
                <?php endif; ?>



                <a href="<?= site_url('my-orders')?>" class="aj-profile-option">

                    <div class="aj-profile-option-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div class="aj-profile-option-content">
                        <strong>My Orders</strong>

                        <span>
                            View your orders and purchases
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>


                <a
                    href="javascript:void(0);"
                    class="aj-profile-option"
                    data-bs-dismiss="modal"
                    data-bs-toggle="modal"
                    data-bs-target="#wishlistModal"
                >

                    <div class="aj-profile-option-icon">
                        <i class="bi bi-heart"></i>
                    </div>

                    <div class="aj-profile-option-content">

                        <strong>
                            Wishlist
                        </strong>

                        <span>
                            Your saved products
                        </span>

                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>


                <a href="#" class="aj-profile-option">

                    <div class="aj-profile-option-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="aj-profile-option-content">
                        <strong>My Addresses</strong>

                        <span>
                            Manage delivery addresses
                        </span>
                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>

                
                <?php if ($isCustomerLoggedIn): ?>

                    <a
                        href="<?= site_url('auth/customerLogout') ?>"
                        class="aj-profile-option aj-logout-option"
                        id="ajCustomerLogoutBtn"
                    >
                        <div class="aj-profile-option-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>

                        <div class="aj-profile-option-content">
                            <strong>Logout</strong>
                            <span>Sign out of your Ajobmart account</span>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </div>
</div>




<div class="modal fade" id="ajShippingModal" tabindex="-1"
     aria-labelledby="ajShippingModalTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content aj-google-shipping-modal">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="ajShippingModalTitle">
                        Choose your delivery location
                    </h5>
                    <small class="text-muted">
                        Find your location to check delivery availability.
                    </small>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div id="ajShippingMessage"
                     class="alert alert-info d-none" role="alert"></div>

                <!-- Search location -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Search your location
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               id="ajGoogleMapSearch"
                               class="form-control"
                               placeholder="Search area, street, landmark..."
                               autocomplete="off">
                    </div>
                    <div id="ajGoogleMapSuggestions"
                        class="aj-google-map-suggestions d-none"></div>
                </div>

                <!-- Current location button -->
                <button type="button"
                        id="ajDetectCurrentLocation"
                        class="btn btn-outline-primary w-100 mb-3">

                    <i class="bi bi-crosshair me-2"></i>
                    Use my current location
                </button>

                <!-- Google Map -->
                <div class="aj-google-map-wrapper mb-3">
                    <div id="ajGoogleMap"
                         style="width:100%;height:280px;">
                    </div>
                </div>

                <!-- Selected location -->
                <div class="aj-selected-location mb-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-danger mt-1"></i>

                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                Selected location
                            </div>

                            <div id="ajSelectedMapAddress"
                                 class="small text-muted">
                                Search or select your location on the map.
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Address form -->
                <form id="ajShippingForm">

                    <div class="mb-3">
                        <label for="ajShippingPin" class="form-label">
                            PIN code
                        </label>

                        <input type="hidden" id="ajShippingLatitude" name="latitude">
                        <input type="hidden" id="ajShippingLongitude" name="longitude">
                        <input type="text"
                               id="ajShippingPin"
                               name="pincode"
                               class="form-control"
                               inputmode="numeric"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               required
                               placeholder="Enter 6-digit PIN code">
                    </div>

                    <div id="ajShippingAddressFields">

                        <p class="fw-semibold mb-3">
                            Delivery address details
                        </p>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingName">
                                Recipient name
                            </label>

                            <input class="form-control"
                                   id="ajShippingName"
                                   name="recipient_name"
                                   autocomplete="name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingMobile">
                                Mobile number
                            </label>

                            <input class="form-control"
                                   id="ajShippingMobile"
                                   name="mobile"
                                   inputmode="numeric"
                                   maxlength="10"
                                   autocomplete="tel">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingAddress1">
                                Address line 1
                            </label>

                            <input class="form-control"
                                   id="ajShippingAddress1"
                                   name="address_line1"
                                   autocomplete="address-line1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingAddress2">
                                Address line 2 (optional)
                            </label>

                            <input class="form-control"
                                   id="ajShippingAddress2"
                                   name="address_line2"
                                   autocomplete="address-line2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingLandmark">
                                Landmark (optional)
                            </label>

                            <input class="form-control"
                                   id="ajShippingLandmark"
                                   name="landmark">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" for="ajShippingCity">
                                    City
                                </label>

                                <input class="form-control"
                                       id="ajShippingCity"
                                       name="city"
                                       autocomplete="address-level2">
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label" for="ajShippingState">
                                    State
                                </label>

                                <input class="form-control"
                                       id="ajShippingState"
                                       name="state"
                                       autocomplete="address-level1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"
                                   for="ajShippingAddressType">
                                Address type
                            </label>

                            <select class="form-select"
                                    id="ajShippingAddressType"
                                    name="address_type">
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                    </div>

                    <button type="submit"
                            id="ajShippingSubmit"
                            class="btn btn-primary w-100">

                        Confirm delivery location
                    </button>

                </form>

                <p class="small text-muted mt-3 mb-0">
                    Please verify your PIN code and address before confirming.
                </p>

            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="ajAddressSelectModal" tabindex="-1"
     aria-labelledby="ajAddressSelectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="ajAddressSelectTitle">
                        Choose delivery address
                    </h5>
                    <small class="text-muted">
                        Select where you want your order delivered.
                    </small>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div id="ajSavedAddressList">
                    <div class="text-muted small">
                        Loading saved addresses...
                    </div>
                </div>

                <button type="button"
                        class="btn btn-outline-primary w-100 mt-3"
                        id="ajAddNewAddress">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add a new address
                </button>
            </div>

        </div>
    </div>
</div>




<!-- =========================================
     WISHLIST MODAL
========================================= -->

<div
    class="modal fade aj-wishlist-modal"
    id="wishlistModal"
    tabindex="-1"
    aria-labelledby="wishlistModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- ================================
                 HEADER
            ================================= -->

            <div class="aj-wishlist-modal-header">

                <div>

                    <h5 id="wishlistModalLabel">
                        My Wishlist
                    </h5>

                    <span class="aj-wishlist-modal-items">
                        0 Items
                    </span>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <!-- ================================
                 BODY
            ================================= -->

            <div class="aj-wishlist-modal-body">


                <!-- EMPTY WISHLIST -->

                <div
                    class="aj-wishlist-empty"
                    id="ajWishlistEmpty"
                >

                    <div class="aj-wishlist-empty-icon">

                        <i class="bi bi-heart"></i>

                    </div>

                    <h4>
                        Your wishlist is empty
                    </h4>

                    <p>
                        Save your favourite products here
                        and come back to them anytime.
                    </p>

                    <a
                        href="#"
                        class="aj-wishlist-shop-btn"
                        data-bs-dismiss="modal"
                    >
                        Explore Products
                    </a>

                </div>


                <!-- WISHLIST PRODUCTS -->

                <div
                    class="aj-wishlist-products"
                    id="ajWishlistProducts"
                >


                    <!-- PRODUCT 1 -->

                    <div class="aj-wishlist-product">

                        <div class="aj-wishlist-product-image">

                            <img
                                src="https://via.placeholder.com/100"
                                alt="Product"
                            >

                        </div>


                        <div class="aj-wishlist-product-details">

                            <h6>
                                Samsung Smartphone
                            </h6>

                            <span class="aj-wishlist-product-price">
                                ₹15,999
                            </span>


                            <div class="aj-wishlist-product-actions">

                                <button
                                    type="button"
                                    class="aj-wishlist-add-cart"
                                >
                                    <i class="bi bi-cart3"></i>

                                    Add to Cart
                                </button>


                                <button
                                    type="button"
                                    class="aj-wishlist-remove"
                                    aria-label="Remove from wishlist"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>

                        </div>

                    </div>


                    <!-- PRODUCT 2 -->

                    <div class="aj-wishlist-product">

                        <div class="aj-wishlist-product-image">

                            <img
                                src="https://via.placeholder.com/100"
                                alt="Product"
                            >

                        </div>


                        <div class="aj-wishlist-product-details">

                            <h6>
                                Wireless Headphones
                            </h6>

                            <span class="aj-wishlist-product-price">
                                ₹1,499
                            </span>


                            <div class="aj-wishlist-product-actions">

                                <button
                                    type="button"
                                    class="aj-wishlist-add-cart"
                                >
                                    <i class="bi bi-cart3"></i>

                                    Add to Cart
                                </button>


                                <button
                                    type="button"
                                    class="aj-wishlist-remove"
                                    aria-label="Remove from wishlist"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================
                 FOOTER
            ================================= -->

            <div class="aj-wishlist-modal-footer">

                <button
                    type="button"
                    class="aj-wishlist-cart-all"
                >
                    <i class="bi bi-cart-plus"></i>

                    Move All to Cart
                </button>

            </div>


        </div>

    </div>

</div>

<script>
const site_url = <?= json_encode(rtrim(site_url(), '/') . '/') ?>;


window.AJ_CART_CONFIG = {
    loggedIn: <?= session()->get('ajobmart_customer_logged_in') ? 'true' : 'false' ?>,

    siteUrl: <?= json_encode(rtrim(site_url(), '/') . '/') ?>,

    fetchUrl: <?= json_encode(site_url('cart/fetch')) ?>,
    addUrl: <?= json_encode(site_url('cart/add')) ?>,
    updateUrl: <?= json_encode(site_url('cart/update')) ?>,
    removeUrl: <?= json_encode(site_url('cart/remove')) ?>,
    checkoutUrl: <?= json_encode(site_url('checkout')) ?>,
    cashbackBalanceUrl: <?= json_encode(site_url('cart/cashback-balance')) ?>,

    shippingCustomerAddressUrl:
        <?= json_encode(site_url('shipping/customer-address')) ?>,

    shippingCustomerAddressesUrl:
    <?= json_encode(site_url('shipping/customer-addresses')) ?>,

    shippingEstimateUrl:
        <?= json_encode(site_url('shipping/estimate')) ?>,

    shippingSaveAddressUrl:
        <?= json_encode(site_url('shipping/save-address')) ?>,

    shippingSaveDetectedAddressUrl : <?= json_encode(site_url('customer/shipping/save-detected-address')) ?>,
    
    wowOfferUrl: <?= json_encode(site_url('cart/update-wow-offer')) ?>,
    // cartUrl: '<?= site_url("cart") ?>',

    googleMapsApiKey:
    <?= json_encode(env('GOOGLE_MAPS_BROWSER_KEY') ?: '') ?>,

    csrfName: <?= json_encode(csrf_token()) ?>,
    csrfHash: <?= json_encode(csrf_hash()) ?>,
    csrfHeader: 'X-CSRF-TOKEN'
};
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

<script src="<?= base_url('public/assets/js/ajobmart-cart.js') ?>"></script>
<script src="<?= base_url('public/assets/js/ajobmart.js') ?>"></script>
<script src="<?= base_url('public/assets/js/aj-shipping.js') ?>"></script>
<script src="<?= base_url('public/assets/js/mobilenav.js') ?>"></script>
<script src="<?= base_url('public/assets/js/aj-google-maps.js') ?>"></script>



<script>

$(document).on('click', '#ajLoginRegisterBtn', function (e) {

    e.preventDefault();

    // Close profile modal first
    const profileModalElement =
        document.getElementById('profileModal');

    if (profileModalElement) {

        const profileModal =
            bootstrap.Modal.getInstance(profileModalElement);

        if (profileModal) {
            profileModal.hide();
        }
    }

    // Wait for profile modal to close
    setTimeout(function () {

        showLoginPopup();

    }, 700);

});


/* =========================================================
   LOGIN POPUP
========================================================= */


function showLoginPopup() {
    Swal.fire({
        title: 'Welcome to Ajobmart',

        html: `
            <div class="aj-login-scroll">
                <div class="aj-login-form">

                    <div class="aj-login-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>

                    <p class="aj-login-subtitle">
                        Login to your Ajobmart account
                    </p>

                    <div class="aj-login-input-group">
                        <i class="bi bi-person"></i>
                        <input
                            type="text"
                            id="ajLoginUser"
                            class="aj-login-input"
                            placeholder="User ID / Email / Mobile"
                            autocomplete="username"
                        >
                    </div>

                    <div class="aj-login-input-group">
                        <i class="bi bi-lock"></i>
                        <input
                            type="password"
                            id="ajLoginPassword"
                            class="aj-login-input"
                            placeholder="Enter password"
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="aj-password-toggle"
                            id="ajTogglePassword"
                            aria-label="Show password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <div class="aj-login-forgot">
                        <a
                            href="javascript:void(0);"
                            id="ajForgotPassword"
                        >
                            Forgot Password?
                        </a>
                    </div>
                </div>
            </div>
        `,

        showCloseButton: true,
        closeButtonHtml: '<i class="bi bi-x-lg"></i>',
        closeButtonAriaLabel: 'Close login',

        confirmButtonText:
            '<i class="bi bi-box-arrow-in-right"></i> Login',

        showCancelButton: false,
        focusConfirm: false,
        allowOutsideClick: () => !Swal.isLoading(),
        allowEscapeKey: true,
        showLoaderOnConfirm: true,

        showClass: {
            popup: 'aj-login-slide-in'
        },

        customClass: {
            popup: 'aj-login-swal',
            closeButton: 'aj-login-close',
            confirmButton: 'aj-login-confirm'
        },

        preConfirm: function () {
            const user = $('#ajLoginUser').val().trim();
            const password = $('#ajLoginPassword').val();

            if (!user) {
                Swal.showValidationMessage(
                    'Please enter your User ID, Email or Mobile number'
                );
                return false;
            }

            if (!password) {
                Swal.showValidationMessage(
                    'Please enter your password'
                );
                return false;
            }

            const postData = {
                user: user,
                password: password
            };

            // Include a CSRF token if CodeIgniter CSRF protection is enabled.
            postData['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';

            return $.ajax({
                url: '<?= site_url('auth/login') ?>',
                type: 'POST',
                dataType: 'json',
                data: postData,

                // Needed if frontend and backend use separate origins.
                xhrFields: {
                    withCredentials: true
                }

            }).then(function (response) {
                if (!response.status) {
                    Swal.showValidationMessage(
                        response.message || 'Customer login failed.'
                    );
                    return false;
                }

                return response;

            }).catch(function (xhr) {
                let message = 'Unable to login. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    message = 'Request rejected. Refresh the page and try again.';
                }

                Swal.showValidationMessage(message);
                return false;
            });
        },

        didOpen: function () {
            const actions = Swal.getActions();

            if (actions) {
                const registerLink = document.createElement('a');

                registerLink.href = 'javascript:void(0);';
                registerLink.id = 'ajCreateAccountLink';
                registerLink.className = 'aj-create-account-link';
                registerLink.innerHTML =
                    'New to Ajobmart? <strong>Create Account</strong>';

                actions.appendChild(registerLink);
            }

            // Show/hide password.
            $('#ajTogglePassword').on('click', function () {
                const input = $('#ajLoginPassword');
                const icon = $(this).find('i');
                const showing = input.attr('type') === 'text';

                input.attr('type', showing ? 'password' : 'text');

                icon.toggleClass('bi-eye', showing);
                icon.toggleClass('bi-eye-slash', !showing);

                $(this).attr(
                    'aria-label',
                    showing ? 'Show password' : 'Hide password'
                );
            });

            // Connect to your existing registration popup, if defined.
            $('#ajCreateAccountLink').on('click', function () {
                Swal.close();

                if (typeof window.showRegisterPopup === 'function') {
                    window.showRegisterPopup();
                }
            });

            // Connect this to your existing forgot-password flow if available.
            $('#ajForgotPassword').on('click', function () {
                if (typeof window.showForgotPasswordPopup === 'function') {
                    Swal.close();
                    window.showForgotPasswordPopup();
                } else {
                    Swal.showValidationMessage(
                        'Forgot password setup is not connected yet.'
                    );
                }
            });
        }

    
    }).then(function (result) {

        if (result.isConfirmed && result.value && result.value.status) {

            // Normal login: preserve existing redirect
            if (window.ajLoginReturnToCart !== true) {
                window.location.href =
                    result.value.redirect || '<?= site_url('/') ?>';
                return;
            }

            // Guest checkout login: synchronize cart first
            ajSyncGuestCartAfterLogin()
                .then(function () {

                    window.ajLoginReturnToCart = false;

                    // Continue to checkout
                    window.location.href = '<?= site_url('checkout') ?>';

                })
                .catch(function (error) {

                    console.error('Guest cart synchronization:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Cart Sync Failed',
                        text: error.message ||
                            'Unable to save your cart. Please try again.'
                    });

                });
        }
    });

}


window.ajSyncGuestCartAfterLogin = function () {

    const guestCartKey = 'ajobmart_guest_cart_v1';

    let guestItems = [];

    try {
        guestItems = JSON.parse(
            localStorage.getItem(guestCartKey) || '[]'
        );
    } catch (error) {
        return Promise.reject(
            new Error('Unable to read your guest cart.')
        );
    }

    // No guest products: nothing to synchronize
    if (!Array.isArray(guestItems) || guestItems.length === 0) {
        return Promise.resolve(true);
    }

    console.log('Guest Cart Data:', guestItems);
console.log('Guest Cart JSON:', JSON.stringify(guestItems, null, 2));
    return $.ajax({
        url: '<?= site_url('cart/sync-guest') ?>',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({
            items: guestItems
        })
    }).then(function (response) {

        if (!response.status) {
            throw new Error(
                response.message || 'Cart synchronization failed.'
            );
        }

        // Remove guest cart only after server confirms success
        localStorage.removeItem(guestCartKey);

        return true;
    });
};




/* =========================================
   CREATE ACCOUNT LINK
========================================= */

$(document).on(
    'click',
    '#ajCreateAccountLink',
    function (e) {

        e.preventDefault();

        Swal.close();

        setTimeout(function () {

            showRegisterPopup();

        }, 250);

    }
);


/* =========================================================
   PASSWORD SHOW / HIDE
========================================================= */

$(document).on(
    'click',
    '#ajTogglePassword',
    function () {

        const input =
            $('#ajLoginPassword');

        const icon =
            $(this).find('i');


        if (
            input.attr('type') ===
            'password'
        ) {

            input.attr(
                'type',
                'text'
            );

            icon
                .removeClass('bi-eye')
                .addClass('bi-eye-slash');

        } else {

            input.attr(
                'type',
                'password'
            );

            icon
                .removeClass('bi-eye-slash')
                .addClass('bi-eye');

        }

    }
);


/* =========================================================
   REGISTER POPUP
========================================================= */

function showRegisterPopup() {

    Swal.fire({

        title: 'Create Ajobmart Account',

        html: `

            <div class="aj-register-scroll">

                <!-- STEP 1 : EMAIL -->
                <div id="ajRegisterStep1">

                    <div class="aj-login-icon">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <p class="aj-login-subtitle">
                        Enter your email address to create your account
                    </p>

                    <div class="aj-login-input-group">

                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="ajRegisterEmail"
                            class="aj-login-input"
                            placeholder="Email address"
                            autocomplete="email"
                        >

                    </div>

                    <button
                        type="button"
                        id="ajSendOtpBtn"
                        class="aj-register-action-btn"
                    >
                        <i class="bi bi-send"></i>
                        Send OTP
                    </button>

                </div>


                <!-- STEP 2 : OTP -->
                <div
                    id="ajRegisterStep2"
                    style="display:none;"
                >

                    <div class="aj-login-icon">

                        <i class="bi bi-shield-check"></i>

                    </div>

                    <p class="aj-login-subtitle">

                        We have sent a verification code to

                        <strong id="ajOtpEmailText"></strong>

                    </p>


                    <div class="aj-login-input-group">

                        <i class="bi bi-key"></i>

                        <input
                            type="text"
                            id="ajRegisterOtp"
                            class="aj-login-input"
                            placeholder="Enter 6-digit OTP"
                            maxlength="6"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                        >

                    </div>


                    <button
                        type="button"
                        id="ajVerifyOtpBtn"
                        class="aj-register-action-btn"
                    >

                        <i class="bi bi-check-circle"></i>

                        Verify OTP

                    </button>


                    <div class="aj-otp-resend">

                        <span id="ajOtpTimer">
                            Didn't receive the OTP?
                        </span>

                        <a
                            href="javascript:void(0);"
                            id="ajResendOtpBtn"
                            style="display:none;"
                        >
                            Resend OTP
                        </a>

                    </div>

                </div>


                <!-- STEP 3 : ACCOUNT DETAILS -->
                <div
                    id="ajRegisterStep3"
                    style="display:none;"
                >

                    <div class="aj-login-icon">

                        <i class="bi bi-person-plus"></i>

                    </div>


                    <p class="aj-login-subtitle">

                        Email verified successfully.
                        Complete your account details.

                    </p>


                    <!-- NAME -->

                    <div class="aj-login-input-group">

                        <i class="bi bi-person"></i>

                        <input
                            type="text"
                            id="ajRegisterName"
                            class="aj-login-input"
                            placeholder="Full name"
                            autocomplete="name"
                        >

                    </div>


                    <!-- MOBILE -->

                    <div class="aj-login-input-group">

                        <i class="bi bi-phone"></i>

                        <input
                            type="tel"
                            id="ajRegisterMobile"
                            class="aj-login-input"
                            placeholder="Mobile number"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="tel"
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="aj-login-input-group">

                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="ajRegisterPassword"
                            class="aj-login-input"
                            placeholder="Create password"
                            autocomplete="new-password"
                        >

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="aj-login-input-group">

                        <i class="bi bi-lock-fill"></i>

                        <input
                            type="password"
                            id="ajRegisterConfirmPassword"
                            class="aj-login-input"
                            placeholder="Confirm password"
                            autocomplete="new-password"
                        >

                    </div>

                </div>

            </div>

        `,


        /* =========================================
           CLOSE BUTTON
        ========================================= */

        showCloseButton: true,

        closeButtonHtml:
            '<i class="bi bi-x-lg"></i>',

        closeButtonAriaLabel:
            'Close registration',


        /* =========================================
           CONFIRM BUTTON
        ========================================= */

        showConfirmButton: false,

        showCancelButton: true,

        cancelButtonText:
            'Back to Login',

        reverseButtons: false,

        focusConfirm: false,

        allowOutsideClick: false,

        allowEscapeKey: true,


        customClass: {

            popup:
                'aj-login-swal aj-register-swal',

            closeButton:
                'aj-login-close',

            cancelButton:
                'aj-login-register'

        },


        didOpen: function () {

            startRegisterEmailStep();

        }

    }).then(function (result) {

        if (
            result.dismiss ===
            Swal.DismissReason.cancel
        ) {

            Swal.close();

            setTimeout(function () {

                showLoginPopup();

            }, 250);

        }

    });

}

function startRegisterEmailStep() {

    $('#ajRegisterStep1').show();

    $('#ajRegisterStep2').hide();

    $('#ajRegisterStep3').hide();

}


$(document).on(
    'click',
    '#ajSendOtpBtn',
    function () {

        const email =
            $('#ajRegisterEmail')
                .val()
                .trim()
                .toLowerCase();


        if (email === '') {

            Swal.showValidationMessage(
                'Please enter your email address'
            );

            return;

        }


        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(email)) {

            Swal.showValidationMessage(
                'Please enter a valid email address'
            );

            return;

        }


        const button =
            $('#ajSendOtpBtn');


        button.prop('disabled', true);

        button.html(
            '<i class="bi bi-hourglass-split"></i> Sending...'
        );


        $.ajax({

            url: "<?= site_url('auth/sendRegistrationOtp') ?>",

            type: 'POST',

            dataType: 'json',

            data: {

                email: email,

                

            },


            success: function (response) {

                if (response.status) {

                    $('#ajRegisterStep1')
                        .hide();

                    $('#ajRegisterStep2')
                        .show();

                    $('#ajOtpEmailText')
                        .text(email);


                    startOtpTimer();


                } else {

                    Swal.showValidationMessage(
                        response.message ||
                        'Unable to send OTP'
                    );

                }

            },


            error: function () {

                Swal.showValidationMessage(
                    'Something went wrong. Please try again.'
                );

            },


            complete: function () {

                button.prop(
                    'disabled',
                    false
                );

                button.html(
                    '<i class="bi bi-send"></i> Send OTP'
                );

            }

        });

    }
);


let ajOtpCountdown = null;


function startOtpTimer() {

    let seconds = 60;


    $('#ajResendOtpBtn')
        .hide();


    $('#ajOtpTimer')
        .show()
        .text(
            'Resend OTP in ' +
            seconds +
            ' seconds'
        );


    clearInterval(
        ajOtpCountdown
    );


    ajOtpCountdown =
        setInterval(function () {

            seconds--;


            $('#ajOtpTimer')
                .text(
                    'Resend OTP in ' +
                    seconds +
                    ' seconds'
                );


            if (seconds <= 0) {

                clearInterval(
                    ajOtpCountdown
                );


                $('#ajOtpTimer')
                    .hide();


                $('#ajResendOtpBtn')
                    .show();

            }

        }, 1000);

}

$(document).on(
    'click',
    '#ajVerifyOtpBtn',
    function () {

        const email =
            $('#ajRegisterEmail')
                .val()
                .trim()
                .toLowerCase();

        const otp =
            $('#ajRegisterOtp')
                .val()
                .trim();


        if (!/^[0-9]{6}$/.test(otp)) {

            Swal.showValidationMessage(
                'Please enter the 6-digit OTP'
            );

            return;

        }


        const button =
            $('#ajVerifyOtpBtn');


        button.prop(
            'disabled',
            true
        );


        button.html(
            '<i class="bi bi-hourglass-split"></i> Verifying...'
        );


        $.ajax({

            url:
                '<?= site_url('auth/verifyRegistrationOtp') ?>',

            type: 'POST',

            dataType: 'json',


            data: {

                email: email,

                otp: otp,

                

            },


            success: function (response) {

                if (response.status) {

                    clearInterval(
                        ajOtpCountdown
                    );


                    $('#ajRegisterStep2')
                        .hide();

                    $('#ajRegisterStep3')
                        .show();


                    /*
                     * Hide Cancel button after
                     * email verification.
                     */

                    $('.aj-register-swal .swal2-cancel')
                        .hide();


                    /*
                     * Show Create Account button.
                     */

                    $('.aj-register-swal .swal2-actions')
                        .prepend(`
                            <button
                                type="button"
                                id="ajFinalCreateAccountBtn"
                                class="swal2-confirm aj-login-confirm"
                            >
                                <i class="bi bi-person-plus"></i>
                                Create Account
                            </button>
                        `);

                } else {

                    Swal.showValidationMessage(
                        response.message ||
                        'Invalid OTP'
                    );

                }

            },


            error: function () {

                Swal.showValidationMessage(
                    'Unable to verify OTP. Please try again.'
                );

            },


            complete: function () {

                button.prop(
                    'disabled',
                    false
                );

                button.html(
                    '<i class="bi bi-check-circle"></i> Verify OTP'
                );

            }

        });

    }
);

$(document).on(
    'click',
    '#ajResendOtpBtn',
    function () {

        const email =
            $('#ajRegisterEmail')
                .val()
                .trim()
                .toLowerCase();


        const button =
            $('#ajResendOtpBtn');


        button
            .prop('disabled', true)
            .text('Sending...');


        $.ajax({

            url:
                '<?= site_url('auth/sendRegistrationOtp') ?>',

            type: 'POST',

            dataType: 'json',

            data: {

                email: email,


            },


            success: function (response) {

                if (response.status) {

                    Swal.showValidationMessage(
                        'A new OTP has been sent to your email.'
                    );


                    startOtpTimer();

                } else {

                    Swal.showValidationMessage(
                        response.message ||
                        'Unable to resend OTP'
                    );

                }

            },


            error: function () {

                Swal.showValidationMessage(
                    'Unable to resend OTP.'
                );

            },


            complete: function () {

                button.prop(
                    'disabled',
                    false
                );

            }

        });

    }
);

$(document).on(
    'click',
    '#ajFinalCreateAccountBtn',
    function () {

        const email =
            $('#ajRegisterEmail')
                .val()
                .trim()
                .toLowerCase();

        const name =
            $('#ajRegisterName')
                .val()
                .trim();

        const mobile =
            $('#ajRegisterMobile')
                .val()
                .trim();

        const password =
            $('#ajRegisterPassword')
                .val();

        const confirmPassword =
            $('#ajRegisterConfirmPassword')
                .val();


        if (name === '') {

            Swal.showValidationMessage(
                'Please enter your name'
            );

            return;

        }


        if (!/^[0-9]{10}$/.test(mobile)) {

            Swal.showValidationMessage(
                'Please enter a valid 10-digit mobile number'
            );

            return;

        }


        if (password.length < 6) {

            Swal.showValidationMessage(
                'Password must be at least 6 characters'
            );

            return;

        }


        if (password !== confirmPassword) {

            Swal.showValidationMessage(
                'Passwords do not match'
            );

            return;

        }


        const button =
            $('#ajFinalCreateAccountBtn');


        button.prop(
            'disabled',
            true
        );


        button.html(
            '<i class="bi bi-hourglass-split"></i> Creating Account...'
        );


        $.ajax({

            url:
                '<?= site_url('auth/register') ?>',

            type: 'POST',

            dataType: 'json',


            data: {

                email: email,

                name: name,

                mobile: mobile,

                password: password,


            },


            success: function (response) {

                if (response.status) {

                    Swal.fire({

                        icon: 'success',

                        title: 'Account Created!',

                        text:
                            response.message ||
                            'Your Ajobmart account has been created successfully.',

                        confirmButtonText:
                            'Login Now',

                        customClass: {

                            popup:
                                'aj-login-swal',

                            confirmButton:
                                'aj-login-confirm'

                        }

                    }).then(function () {

                        showLoginPopup();

                    });

                } else {

                    Swal.showValidationMessage(
                        response.message ||
                        'Unable to create account'
                    );

                    button.prop(
                        'disabled',
                        false
                    );

                    button.html(
                        '<i class="bi bi-person-plus"></i> Create Account'
                    );

                }

            },


            error: function () {

                Swal.showValidationMessage(
                    'Something went wrong. Please try again.'
                );


                button.prop(
                    'disabled',
                    false
                );


                button.html(
                    '<i class="bi bi-person-plus"></i> Create Account'
                );

            }

        });

    }
);


</script>

 


</body>

</html>