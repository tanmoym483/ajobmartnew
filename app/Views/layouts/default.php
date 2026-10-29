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


<?= view('layouts/header', [
    'isCustomerLoggedIn' => $isCustomerLoggedIn,
    'customerName'       => $customerName,
    'customerImage'      => $customerImage,
    'couponAmount'       => $couponAmount,
]) ?>

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



                <a href="#" class="aj-profile-option">

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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="ajShippingModalTitle">
                        Check delivery availability
                    </h5>
                    <small class="text-muted">
                        Enter your PIN code to estimate shipping.
                    </small>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="ajShippingMessage"
                     class="alert alert-info d-none" role="alert"></div>

                <form id="ajShippingForm">
                    <div class="mb-3">
                        <label for="ajShippingPin" class="form-label">
                            PIN code
                        </label>
                        <input type="text" id="ajShippingPin"
                               name="pincode" class="form-control"
                               inputmode="numeric" maxlength="6"
                               pattern="[0-9]{6}" required
                               placeholder="Enter 6-digit PIN code">
                    </div>

                    <div id="ajShippingAddressFields" class="d-none">
                        <hr>
                        <p class="fw-semibold mb-3">Delivery address</p>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingName">
                                Recipient name
                            </label>
                            <input class="form-control" id="ajShippingName"
                                   name="recipient_name" autocomplete="name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingMobile">
                                Mobile number
                            </label>
                            <input class="form-control" id="ajShippingMobile"
                                   name="mobile" inputmode="numeric"
                                   maxlength="10" autocomplete="tel">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingAddress1">
                                Address line 1
                            </label>
                            <input class="form-control" id="ajShippingAddress1"
                                   name="address_line1"
                                   autocomplete="address-line1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingAddress2">
                                Address line 2 (optional)
                            </label>
                            <input class="form-control" id="ajShippingAddress2"
                                   name="address_line2"
                                   autocomplete="address-line2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingLandmark">
                                Landmark (optional)
                            </label>
                            <input class="form-control" id="ajShippingLandmark"
                                   name="landmark">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" for="ajShippingCity">
                                    City
                                </label>
                                <input class="form-control" id="ajShippingCity"
                                       name="city" autocomplete="address-level2">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label" for="ajShippingState">
                                    State
                                </label>
                                <input class="form-control" id="ajShippingState"
                                       name="state" autocomplete="address-level1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ajShippingAddressType">
                                Address type
                            </label>
                            <select class="form-select" id="ajShippingAddressType"
                                    name="address_type">
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="ajShippingSubmit"
                            class="btn btn-primary w-100">
                        Check shipping
                    </button>
                </form>

                <p class="small text-muted mt-3 mb-0">
                    Guests can estimate shipping with a PIN code. You can
                    provide the full delivery address during checkout.
                </p>
            </div>
        </div>
    </div>
</div>



<!-- =========================================
     CART MODAL
========================================= -->

<div
    class="modal fade aj-cart-modal"
    id="cartModal"
    tabindex="-1"
    aria-labelledby="cartModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- ================================
                 CART HEADER
            ================================= -->

            
        <div class="aj-cart-modal-header">
            <div class="flex-grow-1">
                <h5 id="cartModalLabel">My Cart</h5>

                <span class="aj-cart-modal-items">0 Items</span>

                
                <div class="aj-cart-delivery-selector" id="ajCartDeliverySelector">
                    <div class="aj-cart-delivery-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div class="aj-cart-delivery-info">
                        <div class="aj-cart-delivery-label">
                            Deliver to
                        </div>

                        <div id="ajCartDeliveryAddress">
                            <div class="small text-muted">
                                Delivery address not selected
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="aj-cart-address-toggle"
                        id="ajCartAddressToggle"
                        aria-label="Change delivery address"
                        title="Change delivery address">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close">
            </button>
        </div>



            <!-- ================================
                 CART BODY
            ================================= -->

            <div class="aj-cart-modal-body">


                <!-- Empty Cart -->

                <div class="aj-cart-empty" id="ajCartEmpty">
                    <div class="aj-cart-empty-icon">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <h4>Your cart is empty</h4>
                    <p>Looks like you haven't added anything to your cart yet.</p>
                    <a href="#" class="aj-cart-shop-btn" data-bs-dismiss="modal">
                        Start Shopping
                    </a>
                </div>


                <!-- Cart Products -->

                <div
                    class="aj-cart-products"
                    id="ajCartProducts"
                >

                    <!-- Product example -->

                    

                </div>

            </div>


            <!-- ================================
                 CART FOOTER
            ================================= -->

            <div class="aj-cart-modal-footer">

                <div class="aj-cart-summary">
                    <div>
                        <span>Subtotal</span>
                        <strong id="ajCartSubtotal">₹0.00</strong>
                    </div>

                    <div>
                        <span>Delivery</span>
                        <strong id="ajCartDelivery">FREE</strong>
                    </div>

                    <div class="aj-cart-total">
                        <span>Total</span>
                        <strong id="ajCartTotal">₹0.00</strong>
                    </div>
                </div>

                <button type="button" class="aj-cart-checkout-btn" id="ajCartCheckout">
                    Proceed to Checkout
                    <i class="bi bi-arrow-right"></i>
                </button>

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

<script>
// $(document).on('click', '#ajMobileCartButton, #ajCartButton', function (e) {
//     e.preventDefault();

//     console.log('Mobile cart clicked');

//     const shippingModal = document.getElementById('ajShippingModal');
//     const cartModal = document.getElementById('cartModal');

//     if (shippingModal && window.bootstrap) {
//         bootstrap.Modal.getOrCreateInstance(shippingModal).show();
//         console.log('Shipping modal opened');
//     } else if (cartModal && window.bootstrap) {
//         bootstrap.Modal.getOrCreateInstance(cartModal).show();
//         console.error('Shipping modal missing; opened cart as fallback');
//     } else {
//         console.error('Neither modal nor Bootstrap was found');
//     }
// });
</script>
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
            window.location.href =
                result.value.redirect || '<?= site_url('/') ?>';
        }
    });
}



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