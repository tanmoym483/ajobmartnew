<style>
 /* =========================================
   AJOBMART TRENDING IMAGE GALLERY
========================================= */

#ajTrendingProducts {
    display: flex;
    gap: 14px;
    overflow-x: auto;
    overflow-y: hidden;

    scroll-snap-type: x mandatory;
    scrollbar-width: none;

    padding: 8px 3px 16px;
}

#ajTrendingProducts::-webkit-scrollbar {
    display: none;
}

/* Remove traditional card appearance */
#ajTrendingProducts .aj-product-card {
    position: relative;

    flex: 0 0 calc((100% - 28px) / 3);
    min-width: 0;

    padding: 0;
    border: 0;
    border-radius: 16px;

    background: transparent;
    box-shadow: none;

    overflow: hidden;
    scroll-snap-align: start;
}

/* Image gallery tile */
#ajTrendingProducts .aj-product-image {
    position: relative;

    width: 100%;
    aspect-ratio: 4 / 5;

    padding: 0;
    overflow: hidden;

    border-radius: 16px;

    background:
        linear-gradient(
            145deg,
            #e8edff,
            #f9e7e9,
            #fce9cc
        );

    box-shadow:
        0 6px 16px rgba(20, 30, 60, .14);
}

/* Full image area */
#ajTrendingProducts .aj-product-details-link {
    display: flex;

    width: 100%;
    height: 100%;

    align-items: center;
    justify-content: center;
}

/* Make product images larger */
#ajTrendingProducts .aj-popular-product-img {
    width: 100%;
    height: 100%;

    object-fit: cover;

    transition: transform .4s ease;
}

/* Floating discount */
#ajTrendingProducts .aj-trending-discount-badge {
    position: absolute;

    top: 10px;
    left: 10px;

    z-index: 5;

    padding: 7px 11px;

    border-radius: 30px;

    background: linear-gradient(
        135deg,
        #ffdf73,
        #ff9e27
    );

    color: #382000;

    font-size: 11px;
    font-weight: 900;

    box-shadow: 0 4px 12px rgba(0,0,0,.17);
}

/* Hover */
@media (hover: hover) {
    #ajTrendingProducts .aj-product-card:hover img {
        transform: scale(1.08);
    }
}

/* Mobile */
@media(max-width:767px) {

    #ajTrendingProducts {
        gap: 11px;
    }

    #ajTrendingProducts .aj-product-card {
        flex: 0 0 68%;
    }

    #ajTrendingProducts .aj-product-image {
        border-radius: 13px;

    }

    #ajTrendingProducts .aj-trending-discount-badge {
        top: 7px;
        left: 7px;

        padding: 5px 7px;
        font-size: 9px;
    }
}

/* #aj-trending {
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
} */

/* =========================================
   TRENDING - LARGE FULL PRODUCT IMAGES
========================================= */

#ajTrendingProducts {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    padding: 12px 3px 15px;
}

#ajTrendingProducts::-webkit-scrollbar {
    display: none;
}

/* Frameless cards */
#ajTrendingProducts .aj-product-card {
    position: relative;
    flex: 0 0 32%;
    min-width: 0;
    padding: 0;
    margin: 0;
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    scroll-snap-align: start;
    overflow: visible;
}

/* Larger image area */
#ajTrendingProducts .aj-product-image {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;

    padding: 0;
    margin: 0;

    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    overflow: visible;
}

/* Image link */
#ajTrendingProducts .aj-product-details-link {
    display: flex;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
}

/* Large image without cropping */
#ajTrendingProducts .aj-popular-product-img {
    display: block;
    width: 100%;
    height: 100%;

    object-fit: contain !important;
    object-position: center;

    transform: scale(1.15);
    transform-origin: center;

    filter: drop-shadow(0 6px 8px rgba(0,0,0,.12));
    transition: transform .3s ease;
}

/* Discount badge */
#ajTrendingProducts .aj-trending-discount-badge {
    position: absolute;
    top: 3px;
    right: 0;
    z-index: 5;

    padding: 7px 10px;
    border-radius: 30px;

    background: linear-gradient(135deg, #ffe477, #ffad27);
    color: #392300;

    font-size: 11px;
    font-weight: 900;
}

/* Mobile */
@media(max-width:767px) {

    #ajTrendingProducts .aj-product-card {
        flex: 0 0 44%;
        background: transparent;
    }

    #ajTrendingProducts .aj-product-image {
        aspect-ratio: 1 / 1;
    }

    #ajTrendingProducts .aj-popular-product-img {
        transform: scale(1.12);
    }

    #ajTrendingProducts .aj-trending-discount-badge {
        font-size: 9px;
        padding: 5px 7px;
    }
}

/* Hide product details only in Trending */
#ajTrendingProducts .aj-product-info {
    display: none !important;
}

/* Ensure only image cards remain */
#ajTrendingProducts .aj-product-card {
    padding-bottom: 0 !important;
}

/* Trending product image rounded corners */
#ajTrendingProducts .aj-product-image {
    border-radius: 18px !important;
    overflow: hidden;
}

/* Product images */
#ajTrendingProducts .aj-popular-product-img {
    border-radius: 18px !important;
}

/* Discount badge */
#ajTrendingProducts .aj-trending-discount-badge {
    border-radius: 30px !important;
}

/* Product card */
#ajTrendingProducts .aj-product-card {
    border-radius: 18px !important;
}

/* Trending discount - full width bottom overlay */
#ajTrendingProducts .aj-trending-discount-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    top: auto;

    z-index: 5;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    width: 100%;
    padding: 10px 6px;

    border-radius: 0 0 18px 18px;

    background: linear-gradient(
        90deg,
        #ffb52e,
        #ffe17a,
        #ffb52e
    );

    color: #382000;

    font-size: 13px;
    font-weight: 900;
    letter-spacing: .5px;

    box-shadow: 0 -3px 12px rgba(0,0,0,.10);
}

#ajTrendingProducts .aj-product-image {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
}

#ajTrendingProducts .aj-product-image {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
}

#ajTrendingProducts .aj-trending-discount-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 5;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    width: 100%;
    box-sizing: border-box;
    padding: 10px 6px;

    background: linear-gradient(90deg, #ffb52e, #ffe17a, #ffb52e);
    color: #382000;

    font-size: 13px;
    font-weight: 900;
    letter-spacing: .5px;

    border-radius: 0 0 18px 18px;
    box-shadow: 0 -3px 12px rgba(0, 0, 0, .10);
}


/* =========================================
   AJOBMART TRENDING - 3D PRODUCT EFFECT
========================================= */

#ajTrendingProducts .aj-product-card {
    perspective: 900px;
}

#ajTrendingProducts .aj-product-image {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
    transform-style: preserve-3d;
    transition: transform .4s ease, box-shadow .4s ease;
}

/* Floating product image */
#ajTrendingProducts .aj-popular-product-img {
    animation: ajTrendingFloat 3.5s ease-in-out infinite;
    transform-origin: center;
    filter:
        drop-shadow(0 9px 5px rgba(0, 0, 0, .12))
        drop-shadow(0 18px 12px rgba(0, 0, 0, .08));
    transition: filter .4s ease;
}

/* 3D hover tilt */
@media (hover: hover) and (pointer: fine) {
    #ajTrendingProducts .aj-product-card:hover .aj-product-image {
        transform: rotateY(-7deg) rotateX(5deg) scale(1.025);
        box-shadow: 0 15px 25px rgba(0, 0, 0, .16);
    }

    #ajTrendingProducts .aj-product-card:hover .aj-popular-product-img {
        filter:
            drop-shadow(0 14px 9px rgba(0, 0, 0, .18))
            drop-shadow(0 22px 15px rgba(0, 0, 0, .10));
    }
}

/* Floating animation */
@keyframes ajTrendingFloat {
    0%, 100% {
        transform: translateY(0) scale(1.12);
    }

    50% {
        transform: translateY(-6px) scale(1.12);
    }
}

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce) {
    #ajTrendingProducts .aj-popular-product-img {
        animation: none;
    }

    #ajTrendingProducts .aj-product-image {
        transition: none;
    }
}


/* =========================================
   AJOBMART TRENDING SECTION - PREMIUM BACKGROUND
========================================= */

#aj-trending {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding: 24px 16px 28px;
    border-radius: 22px;

    background:
        radial-gradient(
            circle at 10% 15%,
            rgba(66, 133, 244, .35),
            transparent 35%
        ),
        radial-gradient(
            circle at 90% 85%,
            rgba(153, 76, 255, .30),
            transparent 38%
        ),
        linear-gradient(
            135deg,
            #07152f 0%,
            #102c59 45%,
            #211b50 100%
        );

    box-shadow:
        0 12px 35px rgba(10, 20, 55, .22),
        inset 0 1px 0 rgba(255, 255, 255, .15);
}

/* Soft decorative glow */
#aj-trending::before {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    top: -130px;
    right: -70px;
    border-radius: 50%;
    background: rgba(255, 190, 70, .15);
    filter: blur(55px);
    z-index: -1;
    pointer-events: none;
}

#aj-trending::after {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    bottom: -120px;
    left: 15%;
    border-radius: 50%;
    background: rgba(75, 160, 255, .20);
    filter: blur(55px);
    z-index: -1;
    pointer-events: none;
}

/* Trending heading */
#aj-trending .aj-v3-heading {
    position: relative;
    z-index: 1;
}

#aj-trending .aj-v3-heading h2 {
    color: #ffffff;
    text-shadow: 0 2px 12px rgba(0, 0, 0, .25);
}

#aj-trending .aj-v3-heading p,
#aj-trending .aj-v3-label {
    color: #d8e5ff;
}

/* Keep product cards above background effects */
#aj-trending #ajTrendingProducts {
    position: relative;
    z-index: 1;
}

/* Mobile */
@media (max-width: 767px) {
    #aj-trending {
        padding: 20px 12px 24px;
        border-radius: 17px;
    }

    #aj-trending .aj-v3-product-grid {
        
        background: transparent;
    }
}


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

            <div class="aj-v3-heading">
                <div>
                    <span class="aj-v3-label">LIMITED TIME OFFERS</span>
                    <h2>
                        Mega Deals
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

