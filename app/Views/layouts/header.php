

<?php
$isCustomerLoggedIn = session()->get('ajobmart_customer_logged_in');
$customerName = session()->get('ajobmart_customer_name') ?? 'Customer';

// Use a default image when the customer has no uploaded profile image.
$customerImage = session()->get('ajobmart_customer_image')
    ?: base_url('assets/images/default-user.png');
?>

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

                <i class="bi bi-bag-heart-fill"></i>

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

        <div class="aj-navbar-actions desktop-only">

            <!-- CART -->
            <a
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


<!-- ==============================
     SEARCH
================================ -->

<div
    class="aj-search-wrapper"
    id="ajSearchWrapper"
>

    <div class="aj-search-container">

        <form
            action="#"
            method="get"
            id="ajSearchForm"
            class="aj-search-box"
        >

            <i class="bi bi-search aj-search-icon"></i>

            <input
                type="text"
                name="search"
                id="ajSearchInput"
                placeholder="Search products, brands and categories..."
                autocomplete="off"
            >

            <button
                type="submit"
                class="aj-search-button"
            >
                Search
            </button>

        </form>

    </div>

</div>


<!--
    Placeholder is activated by JavaScript
    when mobile search becomes fixed.
-->

<div
    id="ajSearchPlaceholder"
    class="aj-search-placeholder"
></div>