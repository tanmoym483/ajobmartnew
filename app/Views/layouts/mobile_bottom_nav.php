<!-- =========================================
     MOBILE BOTTOM NAVIGATION
========================================= -->

<nav class="aj-mobile-bottom-nav">


    <a
        href="<?= base_url('/') ?>"
        class="aj-mobile-nav-item active"
    >

        <i class="bi bi-house-fill"></i>

        <span>
            Home
        </span>

    </a>


    <a
        href="#categories"
        class="aj-mobile-nav-item"
    >

        <i class="bi bi-grid"></i>

        <span>
            Categories
        </span>

    </a>


    <!-- WISHLIST -->
    <a
        href="javascript:void(0);"
        class="aj-mobile-nav-item aj-mobile-wishlist"
        aria-label="Wishlist"
        data-bs-toggle="modal"
        data-bs-target="#wishlistModal"
    >

        <div class="aj-mobile-nav-icon-wrapper">

            <i class="bi bi-heart"></i>

            <span class="aj-mobile-wishlist-count">
                0
            </span>

        </div>

        <span>
            Wishlist
        </span>

    </a>


    <a
        href="javascript:void(0);"
        class="aj-mobile-nav-item aj-mobile-cart"
        data-bs-toggle="modal"
        data-bs-target="#cartModal"
    >
        <i class="bi bi-cart3"></i>

        <span class="aj-mobile-cart-count">
            0
        </span>

        <span>
            Cart
        </span>
    </a>


    <!-- <a
        href="#"
        class="aj-mobile-nav-item"
    >

        <i class="bi bi-box-seam"></i>

        <span>
            Orders
        </span>

    </a> -->


    <!-- Mobile Profile -->

<a
    href="javascript:void(0);"
    class="aj-mobile-nav-item"
    data-bs-toggle="modal"
    data-bs-target="#profileModal"
    data-profile-source="mobile"
>
    <?php if ($isCustomerLoggedIn): ?>
        <img
            src="<?= esc($customerImage, 'attr') ?>"
            alt="<?= esc($customerName, 'attr') ?>"
            class="aj-customer-avatar aj-mobile-avatar"
        >
    <?php else: ?>
        <i class="bi bi-person"></i>
    <?php endif; ?>

    <span>Profile</span>
</a>

</nav>
