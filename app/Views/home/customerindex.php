

<!-- =========================================
     HERO SECTION
========================================= -->

<section class="aj-container aj-hero-section">

    <div class="aj-hero">

        <div class="aj-hero-content">

            <span class="aj-hero-small-title">
                WELCOME TO AJOBMART
            </span>

            <h1>
                Everything you need,
                <br>
                all in one place.
            </h1>

            <p>
                Discover amazing products, great deals
                and everyday essentials at the best prices.
            </p>

            <div class="aj-hero-buttons">

                <a
                    href="#products"
                    class="aj-primary-button"
                >
                    Shop Now
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a
                    href="#categories"
                    class="aj-secondary-button"
                >
                    Explore Categories
                </a>

            </div>

        </div>


        <div class="aj-hero-visual">

            <div class="aj-hero-circle">

                <i class="bi bi-bag-heart-fill"></i>

            </div>

        </div>

    </div>

</section>



<!-- =========================================
     CATEGORY SECTION
========================================= -->

<section
    class="aj-container aj-section"
    id="categories"
>


<a href="<?= site_url('qr-code/download-pdf') ?>"
   class="btn btn-success"
   target="_blank">
    <i class="fas fa-qrcode"></i>
    Download Welcome QR Code PDF
</a>

    <div class="aj-section-heading">

        <div>

            <span class="aj-section-label">
                EXPLORE
            </span>

            <h2>
                Shop by Category
            </h2>

        </div>

        <a href="#" class="aj-view-all">
            View All
            <i class="bi bi-arrow-right"></i>
        </a>

    </div>


    <div class="aj-category-list">


        <!-- CATEGORY -->

        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-phone"></i>
            </div>

            <span>
                Electronics
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-basket2"></i>
            </div>

            <span>
                Grocery
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-person-standing"></i>
            </div>

            <span>
                Fashion
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-capsule"></i>
            </div>

            <span>
                Medicine
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-house"></i>
            </div>

            <span>
                Home
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-controller"></i>
            </div>

            <span>
                Gaming
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-watch"></i>
            </div>

            <span>
                Accessories
            </span>

        </a>


        <a href="#" class="aj-category-card">

            <div class="aj-category-icon">
                <i class="bi bi-grid"></i>
            </div>

            <span>
                More
            </span>

        </a>

    </div>

</section>



<!-- =========================================
     OFFER BANNER
========================================= -->

<section class="aj-container aj-section">

    <div class="aj-offer-banner">

        <div>

            <span class="aj-offer-label">
                SPECIAL OFFER
            </span>

            <h2>
                Great products.
                <br>
                Great prices.
            </h2>

            <p>
                Shop today's best deals before they're gone.
            </p>

            <a href="#" class="aj-offer-button">
                Explore Deals
            </a>

        </div>


        <div class="aj-offer-icon">

            <i class="bi bi-lightning-charge-fill"></i>

        </div>

    </div>

</section>



<!-- =========================================
     PRODUCTS
========================================= -->

<section class="aj-container aj-section" id="products">

    <div class="aj-section-heading">
        <div>
            <span class="aj-section-label">SHOP NOW</span>
            <h2>Popular Products</h2>
        </div>

        <a href="#" class="aj-view-all">
            View All
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div id="popularRefreshStatus"
        class="d-none align-items-center gap-2 mb-2 text-muted small">
        <!-- <span class="spinner-border spinner-border-sm text-primary"></span>
        <span>Updating products...</span> -->
    </div>

    <div class="aj-product-grid" id="popularProductsContainer">
        <div class="text-center w-100 py-4">
            <span class="spinner-border spinner-border-sm"></span>
            Loading popular products...
        </div>
    </div>

</section>



<!-- =========================================
     WHY AJOBMART
========================================= -->

<section class="aj-container aj-section">

    <div class="aj-benefits">


        <div class="aj-benefit">

            <div class="aj-benefit-icon">
                <i class="bi bi-truck"></i>
            </div>

            <div>

                <h4>
                    Fast Delivery
                </h4>

                <p>
                    Get your products delivered quickly.
                </p>

            </div>

        </div>


        <div class="aj-benefit">

            <div class="aj-benefit-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>

                <h4>
                    Secure Shopping
                </h4>

                <p>
                    Your payments and data are protected.
                </p>

            </div>

        </div>


        <div class="aj-benefit">

            <div class="aj-benefit-icon">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <div>

                <h4>
                    Easy Returns
                </h4>

                <p>
                    Simple and convenient return process.
                </p>

            </div>

        </div>


        <div class="aj-benefit">

            <div class="aj-benefit-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div>

                <h4>
                    Customer Support
                </h4>

                <p>
                    We're here whenever you need us.
                </p>

            </div>

        </div>

    </div>

</section>


