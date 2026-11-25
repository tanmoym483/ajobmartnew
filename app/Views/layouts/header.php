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
                aria-label="Cashback deducted"
                data-bs-toggle="modal"
                data-bs-target="#couponModal"
            >
                <span class="aj-coupon-icon">
                    <i class="bi bi-ticket-perforated-fill"></i>
                </span>

                <span class="aj-coupon-details">
                    <small>Cashback Used</small>
                    <strong id="ajHeaderCashbackAmount">
                        ₹<?= number_format($couponAmount, 2) ?>
                    </strong>
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

            <a href="<?= site_url('cart/') ?>"
                class="aj-cart-icon aj--cart"
                id=""
                >
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





