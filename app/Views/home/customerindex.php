
<!-- CATEGORY NAVIGATION -->
<?= view('layouts/category_navigation') ?>
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


    <section class="aj-v3-section aj-home-section"
         id="ajRecentlyViewedSection"
         style="display:none;">

        <div class="aj-v3-heading">
            <div>
                <span class="aj-v3-label">YOUR BROWSING HISTORY</span>
                <h2>Recently Viewed</h2>
                <p>Continue exploring products you viewed.</p>
            </div>
        </div>

        <div id="ajRecentlyViewed"
            class="aj-v3-product-grid">
        </div>

    </section>


    <!-- =====================================
         1. POPULAR PRODUCTS - FIRST SECTION
    ====================================== -->

    <section class="aj-v3-section aj-home-section" id="products">

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
     2. BEST DEALS
====================================== -->

<section class="aj-v3-section aj-section-deals aj-home-section" id="aj-deals">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">LIMITED TIME OFFERS</span>
            <h2>
                Best Deals
                <i class="bi bi-lightning-fill"></i>
            </h2>
            <p>Amazing products at special prices.</p>
        </div>

        <a href="#" class="aj-v3-view-all">
            View All <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div id="ajBestDeals"
         class="aj-v3-product-grid aj-deals-grid">
    </div>

</section>



<!-- =====================================
     BUDGET PRODUCTS - UNDER ₹99
====================================== -->

<section class="aj-v3-section aj-home-section aj-budget-section"
         id="aj-under-99-section">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">LOW PRICE COLLECTION</span>
            <h2>Everything Under ₹99</h2>
            <p>Small prices, smart shopping.</p>
        </div>

        <a href="<?= site_url('products/under-99') ?>"
           class="aj-v3-view-all">View All →</a>
    </div>

    <div id="ajUnder99"
         class="aj-v3-product-grid"></div>

</section>


<!-- =====================================
     BUDGET PRODUCTS - UNDER ₹199
====================================== -->

<section class="aj-v3-section aj-home-section aj-budget-section"
         id="aj-under-199-section">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">VALUE SHOPPING</span>
            <h2>Under ₹199</h2>
            <p>More products within your budget.</p>
        </div>

        <a href="<?= site_url('products/under-199') ?>"
           class="aj-v3-view-all">View All →</a>
    </div>

    <div id="ajUnder199"
         class="aj-v3-product-grid"></div>

</section>


<!-- =====================================
     30% OFF PRODUCTS
====================================== -->

<section class="aj-v3-section aj-home-section aj-discount-section"
         id="aj-discount-30-section">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">EXTRA SAVINGS</span>
            <h2>30% OFF & MORE</h2>
            <p>Discover products with bigger discounts.</p>
        </div>

        <a href="<?= site_url('offers/30-percent-off') ?>"
           class="aj-v3-view-all">View All →</a>
    </div>

    <div id="ajDiscount30"
         class="aj-v3-product-grid"></div>

</section>


<!-- =====================================
     50% OFF PRODUCTS
====================================== -->

<section class="aj-v3-section aj-home-section aj-discount-section"
         id="aj-discount-50-section">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">MEGA SAVINGS</span>
            <h2>50% OFF & MORE</h2>
            <p>Grab amazing discounts on selected products.</p>
        </div>

        <a href="<?= site_url('offers/50-percent-off') ?>"
           class="aj-v3-view-all">View All →</a>
    </div>

    <div id="ajDiscount50"
         class="aj-v3-product-grid"></div>

</section>



<section class="aj-extra-section aj-brand-section">

    <div class="aj-extra-heading">
        <div>
            <span>TOP BRANDS</span>
            <h2>Explore Popular Brands</h2>
            <p>Shop products from brands you love.</p>
        </div>
    </div>

    <div class="aj-brand-grid">

        <a href="<?= site_url('brand/samsung') ?>">Samsung</a>
        <a href="<?= site_url('brand/apple') ?>">Apple</a>
        <a href="<?= site_url('brand/boat') ?>">boAt</a>
        <a href="<?= site_url('brand/patanjali') ?>">Patanjali</a>
        <a href="<?= site_url('brand/amul') ?>">Amul</a>
        <a href="<?= site_url('brand/dabur') ?>">Dabur</a>

    </div>

</section>



<!-- =====================================
     3. NEW ARRIVALS
====================================== -->

<section class="aj-v3-section aj-section-new aj-home-section" id="aj-new-arrivals">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">FRESH FROM OUR COLLECTION</span>
            <h2>New Arrivals</h2>
            <p>Discover what's new at Ajobmart.</p>
        </div>

        <a href="#" class="aj-v3-view-all">
            Explore <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div id="ajNewArrivals"
         class="aj-v3-product-grid aj-new-grid">
    </div>

</section>


<section class="aj-extra-section aj-collection-section">

    <div class="aj-extra-heading">
        <div>
            <span>HANDPICKED FOR YOU</span>
            <h2>Featured Collections</h2>
            <p>Explore collections selected for you.</p>
        </div>
    </div>

    <div class="aj-collection-grid">

        <a href="<?= site_url('collections/daily-essentials') ?>"
           class="aj-collection-card collection-green">
            <div>
                <small>EVERYDAY NEEDS</small>
                <h3>Daily Essentials</h3>
                <span>Shop Now →</span>
            </div>
            <i class="bi bi-basket2"></i>
        </a>

        <a href="<?= site_url('collections/healthy-living') ?>"
           class="aj-collection-card collection-orange">
            <div>
                <small>LIVE BETTER</small>
                <h3>Healthy Living</h3>
                <span>Explore Now →</span>
            </div>
            <i class="bi bi-heart-pulse"></i>
        </a>

        <a href="<?= site_url('collections/home-kitchen') ?>"
           class="aj-collection-card collection-purple">
            <div>
                <small>MAKE IT BETTER</small>
                <h3>Home & Kitchen</h3>
                <span>Discover More →</span>
            </div>
            <i class="bi bi-house-heart"></i>
        </a>

    </div>

</section>

<!-- =====================================
     4. TRENDING PRODUCTS
====================================== -->

<section class="aj-v3-section aj-section-trending aj-home-section" id="aj-trending">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">CUSTOMER FAVOURITES</span>
            <h2>
                Trending Products
                <i class="bi bi-fire"></i>
            </h2>
            <p>See what everyone is shopping for.</p>
        </div>

        <a href="#" class="aj-v3-view-all">
            View All <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div id="ajTrendingProducts"
         class="aj-v3-product-grid aj-trending-grid">
    </div>

</section>





<section class="aj-v3-section aj-home-section" id="moreproducts">

    <div class="aj-v3-heading">
        <div>
            <span class="aj-v3-label">EXPLORE OUR COLLECTION</span>
            <h2>More Products</h2>
            <p>Discover more products from Ajobmart.</p>
        </div>
    </div>

    <div id="moreProductsContainer"
         class="aj-v3-product-grid">

    </div>

    <!-- Loading indicator -->
    <div id="ajMoreProductsLoader"
         class="text-center py-4"
         style="display:none;">

        <span class="spinner-border spinner-border-sm"></span>
        <span class="ms-2">Loading more products...</span>

    </div>

    <!-- End message -->
    <div id="ajMoreProductsEnd"
         class="text-center py-4 text-muted small"
         style="display:none;">

        You've viewed all available products.

    </div>

    <!-- Scroll detection target -->
    <div id="ajMoreProductsSentinel"
         style="height:20px;">
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

