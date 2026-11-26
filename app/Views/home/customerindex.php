
<style>


</style>

<!-- CATEGORY NAVIGATION -->
<!-- =========================================
     AJOBMART - CLEAN MARKETPLACE HOMEPAGE
========================================= -->
<div id="ajForYouContent">
<div class="aj-container aj-home-v3">


    <!-- =====================================
         TOP PROMOTIONAL CAROUSEL
    ====================================== -->

            
        <?php if (!empty($banners)): ?>

        <section class="aj-v3-hero-carousel" id="ajHomeCarousel">

            <div class="swiper aj-home-banner-swiper">

                <div class="swiper-wrapper">

                    <?php foreach ($banners as $banner): ?>

                        <?php
                            $imageUrl = site_url(
                                'shared-images/' . ltrim($banner['image'], '/')
                            );
                        ?>

                        <div class="swiper-slide">

                            <a href="<?= esc($banner['target_url'] ?: '#') ?>"
                            <?= !empty($banner['open_new_tab'])
                                    ? 'target="_blank" rel="noopener noreferrer"'
                                    : '' ?>>

                                <img
                                    src="<?= esc($imageUrl) ?>"
                                    alt="<?= esc($banner['title']) ?>"
                                    loading="lazy"
                                >

                            </a>

                        </div>

                    <?php endforeach; ?>

                </div>

                <div class="swiper-pagination"></div>

            </div>

        </section>

        <?php endif; ?>



    <section class="aj-v3-section aj-home-section"
         id="ajRecentlyViewedSection"
         >

        <div class="aj-v3-heading">
            <div>
                <!-- <span class="aj-v3-label">YOUR BROWSING HISTORY</span> -->
                <h2>Recently Viewed</h2>
                <!-- <p>Continue exploring products you viewed.</p> -->
            </div>
        </div>

        <div id="ajRecentlyViewed"
            class="aj-v3-product-grid">
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
            2. MEGA DEALS
        ====================================== -->

        
<section class="aj-v3-section aj-section-deals aj-home-section" id="aj-deals">

    <div class="aj-deals-heading">

        <div>
            <span class="aj-deals-label">
                LIMITED TIME OFFERS
            </span>

            <h2>
                Mega Deals
                <i class="bi bi-lightning-fill"></i>
            </h2>

            <p>Unmissable offers. Limited time.</p>
        </div>

        <a href="#" class="aj-deals-view-all">
            Explore All
            <i class="bi bi-arrow-right"></i>
        </a>

    </div>

    <div class="aj-deals-showcase">

        <div class="aj-deals-grid" id="ajBestDeals">
        </div>

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



        <!-- <section class="aj-extra-section aj-brand-section">

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

        </section> -->



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

</div>


<div id="ajCategoryContent" style="display:none;">

    <div class="aj-container aj-home-v3">

                        <?php if (!empty($banners)): ?>

        <section class="aj-v3-hero-carousel" id="ajHomeCarousel">

            <div class="swiper aj-home-banner-swiper">

                <div class="swiper-wrapper">

                    <?php foreach ($banners as $banner): ?>

                        <?php
                            $imageUrl = site_url(
                                'shared-images/' . ltrim($banner['image'], '/')
                            );
                        ?>

                        <div class="swiper-slide">

                            <a href="<?= esc($banner['target_url'] ?: '#') ?>"
                            <?= !empty($banner['open_new_tab'])
                                    ? 'target="_blank" rel="noopener noreferrer"'
                                    : '' ?>>

                                <img
                                    src="<?= esc($imageUrl) ?>"
                                    alt="<?= esc($banner['title']) ?>"
                                    loading="lazy"
                                >

                            </a>

                        </div>

                    <?php endforeach; ?>

                </div>

                <div class="swiper-pagination"></div>

            </div>

        </section>

        <?php endif; ?>


        <section class="aj-home-section">

            <div class="aj-section-header">
                <h2 id="ajCategoryTitle">Products</h2>
            </div>

            <div id="ajCategoryProducts"
                 class="aj-v3-product-grid">
            </div>

            <div id="ajCategoryLoader"
                 class="text-center py-4"
                 style="display:none;">

                <span class="spinner-border spinner-border-sm"></span>
                <span class="ms-2">Loading products...</span>

            </div>

            <div id="ajCategoryEnd"
                 class="text-center py-4"
                 style="display:none;">

                No more products available.

            </div>

            <div id="ajCategorySentinel"
                 style="height:10px;">
            </div>

        </section>

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

