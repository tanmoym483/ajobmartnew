<!-- ==============================
     MAIN NAVBAR
================================ -->

<header class="aj-navbar">

    <div class="aj-navbar-inner">

        <!-- LOGO -->

        <a
            href="<?= base_url('/') ?>"
            class="aj-logo"
        >

            <span class="aj-logo-icon">

                <img class="ajobmartlogo" src="<?= base_url('public/assets/favicon/android-chrome-512x512.png') ?>">

            </span>

            <span class="aj-logo-text">
                Ajobmart
            </span>

        </a>


        <!-- DESKTOP MENU -->

        <nav class="aj-desktop-menu">

            <a href="<?= base_url('/') ?>">
                Home
            </a>

            <a href="#">
                Categories
            </a>

            <a href="#">
                Offers
            </a>


            
        </nav>

        <!-- <div>
            
        </div> -->

        <!-- RIGHT SIDE -->
         <?php
            if ($isCustomerLoggedIn) {
        ?>
            <a
                href="javascript:void(0);"
                class="aj-coupon-balance"
                aria-label="Available coupon balance"
                data-bs-toggle="modal"
                data-bs-target="#couponModal"
            >
                <span class="aj-coupon-icon">
                    <i class="bi bi-ticket-perforated-fill"></i>
                </span>

                <span class="aj-coupon-details">
                    <small>My Cashback</small>
                    <strong>₹<?= number_format($couponAmount, 0) ?></strong>
                </span>
            </a>
        <?php
            }
        ?>
        <div class="aj-navbar-actions desktop-only">

            <!-- COUPON / CASHBACK BALANCE -->
            

            <!-- CART -->
            <!-- <a
                href="javascript:void(0);"
                class="aj-cart-icon"
                aria-label="Cart"
                data-bs-toggle="modal"
                data-bs-target="#cartModal"
            >
                <i class="bi bi-cart3"></i>

                <span class="aj-cart-count">
                    0
                </span>
            </a> -->

            <a href="javascript:void(0);"
   class="aj-cart-icon aj--cart"
   id="ajCartButton"
   data-aj-shipping-cart-trigger>
    <i class="bi bi-cart3"></i>
    <span class="aj-cart-count">0</span>
</a>
            


            <!-- USER -->

            <!-- Desktop Profile -->
            


            <a
                href="javascript:void(0);"
                class="aj-user-icon"
                aria-label="Profile"
                data-bs-toggle="modal"
                data-bs-target="#profileModal"
                data-profile-source="desktop"
            >
                <?php if ($isCustomerLoggedIn): ?>
                    <img
                        src="<?= esc($customerImage, 'attr') ?>"
                        alt="<?= esc($customerName, 'attr') ?>"
                        class="aj-customer-avatar"
                    >
                <?php else: ?>
                    <i class="bi bi-person-fill"></i>
                <?php endif; ?>
            </a>

        </div>

    </div>

</header>





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

                <h2 class="fw-bold text-success">
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