
<div class="modal fade" id="couponModal" tabindex="-1"
     aria-labelledby="couponModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">
                    <i class="bi bi-ticket-perforated-fill text-warning"></i>
                    My Coupons & Cashback
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body text-center py-4">

                <p class="text-muted mb-2">Your available balance</p>

                <h2 class="fw-bold text-success" id="ajmodalCashbackAmount">
                    ₹<?= number_format($couponAmount, 2) ?>
                </h2>

                <p class="text-muted small mb-0">
                    Use your eligible coupons and cashback
                    during checkout.
                </p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

                <a href="<?= base_url('coupons') ?>"
                   class="btn btn-primary">
                    View Coupons
                </a>
            </div>

        </div>
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
