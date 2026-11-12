
<!-- =========================================
     AJOBMART - CLEAN MARKETPLACE HOMEPAGE
========================================= -->

<div class="aj-container aj-home-v3">


    <!-- =====================================
         TOP PROMOTIONAL CAROUSEL
    ====================================== -->

    <section class="aj-v3-hero-carousel" id="ajHomeCarousel">

        <div class="aj-v3-carousel-track">

            <!-- Banner 1 -->
            <a href="#aj-deals"
               class="aj-v3-carousel-slide active">

                <img src="<?= base_url('public/assets/images/banners/banner-1.webp') ?>"
                     alt="Ajobmart Special Deals">

                <!-- <div class="aj-v3-banner-content">
                    <span>AJOBMART SPECIAL</span>
                    <h2>Big Deals. Better Savings.</h2>
                    <p>Discover amazing offers on selected products.</p>
                    <strong>Explore Deals <i class="bi bi-arrow-right"></i></strong>
                </div> -->

            </a>


            <!-- Banner 2 -->
            <a href="#ajBestDeals"
               class="aj-v3-carousel-slide">

                <img src="<?= base_url('public/assets/images/banners/banner-2.webp') ?>"
                     alt="Ajobmart WOW Offers">

                <!-- <div class="aj-v3-banner-content">
                    <span>WOW OFFERS</span>
                    <h2>More Shopping. More Savings.</h2>
                    <p>Explore exclusive WOW deals at Ajobmart.</p>
                    <strong>Shop Now <i class="bi bi-arrow-right"></i></strong>
                </div> -->

            </a>


            <!-- Banner 3 -->
            <a href="#ajNewArrivals"
               class="aj-v3-carousel-slide">

                <img src="<?= base_url('public/assets/images/banners/banner-3.webp') ?>"
                     alt="New Arrivals at Ajobmart">

                <!-- <div class="aj-v3-banner-content">
                    <span>JUST ARRIVED</span>
                    <h2>Discover What's New</h2>
                    <p>Find the latest additions to our collection.</p>
                    <strong>Explore Now <i class="bi bi-arrow-right"></i></strong>
                </div> -->

            </a>

        </div>


        <!-- Previous / Next -->
        <!-- <button type="button"
                class="aj-v3-carousel-arrow aj-v3-carousel-prev"
                aria-label="Previous banner">
            <i class="bi bi-chevron-left"></i>
        </button>

        <button type="button"
                class="aj-v3-carousel-arrow aj-v3-carousel-next"
                aria-label="Next banner">
            <i class="bi bi-chevron-right"></i>
        </button> -->


        <!-- Indicators -->
        <div class="aj-v3-carousel-dots">
            <button class="active" data-slide="0" aria-label="Slide 1"></button>
            <button data-slide="1" aria-label="Slide 2"></button>
            <button data-slide="2" aria-label="Slide 3"></button>
        </div>

    </section>


    <!-- =====================================
         1. POPULAR PRODUCTS - FIRST SECTION
    ====================================== -->

    <section class="aj-v3-section" id="products">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">EXPLORE OUR COLLECTION</span>
                <h2>Popular Products</h2>
                <p>Discover products customers love.</p>
            </div>

            <a href="#" class="aj-v3-view-all">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div id="popularRefreshStatus"
             class="d-none align-items-center gap-2 mb-2 text-muted small">
        </div>

        <div id="popularProductsContainer"
             class="aj-v3-product-grid">

            <div class="text-center w-100 py-4">
                <span class="spinner-border spinner-border-sm"></span>
                Loading popular products...
            </div>

        </div>

    </section>


    <!-- =====================================
         2. SHOP BY CATEGORY
    ====================================== -->

    <section class="aj-v3-category-section" id="categories">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">SHOP YOUR WAY</span>
                <h2>Categories</h2>
            </div>

            <a href="#" class="aj-v3-view-all">
                Explore All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="aj-v3-category-list">

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-blue">
                    <i class="bi bi-phone"></i>
                </span>
                <span>Electronics</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-green">
                    <i class="bi bi-basket2"></i>
                </span>
                <span>Grocery</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-pink">
                    <i class="bi bi-handbag"></i>
                </span>
                <span>Fashion</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-red">
                    <i class="bi bi-capsule"></i>
                </span>
                <span>Medicine</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-orange">
                    <i class="bi bi-house"></i>
                </span>
                <span>Home</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-purple">
                    <i class="bi bi-controller"></i>
                </span>
                <span>Gaming</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-cyan">
                    <i class="bi bi-watch"></i>
                </span>
                <span>Accessories</span>
            </a>

            <a href="#" class="aj-v3-category">
                <span class="aj-v3-cat-icon cat-gray">
                    <i class="bi bi-grid"></i>
                </span>
                <span>More</span>
            </a>

        </div>

    </section>


    <!-- =====================================
         3. COMPACT WOW OFFER
    ====================================== -->

    <section class="aj-v3-wow-strip">

        <div class="aj-v3-wow-left">
            <span class="aj-v3-wow-icon">
                <i class="bi bi-lightning-charge-fill"></i>
            </span>

            <div>
                <strong>AJOBMART WOW DEALS</strong>
                <small>Discover exclusive savings on selected products.</small>
            </div>
        </div>

        <a href="#ajBestDeals">
            Explore Deals <i class="bi bi-arrow-right"></i>
        </a>

    </section>


    <!-- =====================================
         4. BEST DEALS
    ====================================== -->

    <section class="aj-v3-section" id="aj-deals">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">SPECIAL PRICES</span>
                <h2>Best Deals</h2>
                <p>Great products at attractive prices.</p>
            </div>

            <a href="#" class="aj-v3-view-all">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div id="ajBestDeals"
             class="aj-v3-product-grid">
        </div>

    </section>


    <!-- =====================================
         5. NEW ARRIVALS
    ====================================== -->

    <section class="aj-v3-section">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">JUST ARRIVED</span>
                <h2>New Arrivals</h2>
                <p>Explore the latest additions to Ajobmart.</p>
            </div>

            <a href="#" class="aj-v3-view-all">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div id="ajNewArrivals"
             class="aj-v3-product-grid">
        </div>

    </section>


    <!-- =====================================
         6. TRENDING PRODUCTS
    ====================================== -->

    <section class="aj-v3-section">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">POPULAR CHOICES</span>
                <h2>Trending Products</h2>
                <p>See what's getting attention.</p>
            </div>

            <a href="#" class="aj-v3-view-all">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div id="ajTrendingProducts"
             class="aj-v3-product-grid">
        </div>

    </section>



    <!-- =====================================
         8. WELCOME QR LINK
    ====================================== -->

    <div class="aj-v3-qr">

        <div>
            <i class="bi bi-qr-code"></i>
            <span>Welcome to Ajobmart?</span>
        </div>

        <a href="<?= site_url('qr-code/download-pdf') ?>"
           target="_blank">
            Download Welcome QR
            <i class="bi bi-download"></i>
        </a>

    </div>

</div>



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

