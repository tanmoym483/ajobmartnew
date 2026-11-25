document.addEventListener("DOMContentLoaded", function () {

    /* =================================================
       STICKY SEARCH
    ================================================= */

    const searchWrapper =
        document.getElementById("ajSearchWrapper");

    const searchPlaceholder =
        document.getElementById("ajSearchPlaceholder");

    const navbar =
        document.querySelector(".aj-navbar");
        


    if (searchWrapper && searchPlaceholder && navbar) {

        let searchStartPosition = 0;


        function calculateSearchPosition() {

            if (window.innerWidth <= 768) {

                searchStartPosition =
                    navbar.offsetHeight + 10;

            } else {

                searchStartPosition = 0;

                searchWrapper.classList.remove(
                    "aj-search-fixed"
                );

                searchPlaceholder.classList.remove(
                    "active"
                );

            }

        }


        calculateSearchPosition();


        window.addEventListener(
            "resize",
            calculateSearchPosition
        );


        window.addEventListener(
            "scroll",
            function () {

                if (window.innerWidth > 768) {

                    return;

                }


                if (
                    window.scrollY >
                    searchStartPosition
                ) {

                    if (
                        !searchWrapper.classList.contains(
                            "aj-search-fixed"
                        )
                    ) {

                        searchPlaceholder.style.height =
                            searchWrapper.offsetHeight + "px";

                        searchPlaceholder.classList.add(
                            "active"
                        );

                        searchWrapper.classList.add(
                            "aj-search-fixed"
                        );

                    }

                } else {

                    searchWrapper.classList.remove(
                        "aj-search-fixed"
                    );

                    searchPlaceholder.classList.remove(
                        "active"
                    );

                    searchPlaceholder.style.height =
                        "0px";

                }

            },
            {
                passive: true
            }
        );

    }



/* =================================================
   CATEGORY NAVIGATION ON PAGE SCROLL
================================================= */

const categoryNav = document.getElementById("ajCategoryNav");

const categorySection = document.querySelector(".aj-v3-category-nav");

if (categoryNav && categorySection && searchWrapper) {

    function updateCategoryNavigation() {

        if (window.innerWidth <= 768) {

            const searchIsFixed =
                searchWrapper.classList.contains("aj-search-fixed");

            if (searchIsFixed) {

                categoryNav.classList.add("compact");

                categorySection.classList.add("aj-category-sticky");

            } else {

                categoryNav.classList.remove("compact");

                categorySection.classList.remove("aj-category-sticky");

            }

        } else {

            categoryNav.classList.remove("compact");

            categorySection.classList.remove("aj-category-sticky");

        }

    }

    window.addEventListener(
        "scroll",
        updateCategoryNavigation,
        { passive: true }
    );

    window.addEventListener(
        "resize",
        updateCategoryNavigation
    );

}



    /* =================================================
       SEARCH FORM
    ================================================= */

    const searchForm =
        document.getElementById("ajSearchForm");

    const searchInput =
        document.getElementById("ajSearchInput");


    if (searchForm && searchInput) {

        searchForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                const searchValue =
                    searchInput.value.trim();


                if (searchValue === "") {

                    searchInput.focus();

                    return;

                }


                /*
                 * Later this can become:
                 *
                 * window.location.href =
                 * base_url + "/products?search="
                 * + encodeURIComponent(searchValue);
                 */


                console.log(
                    "Searching:",
                    searchValue
                );

            }
        );

    }


    /* =================================================
       MOBILE NAVIGATION
    ================================================= */

    const mobileNavItems =
        document.querySelectorAll(
            ".aj-mobile-nav-item"
        );


    mobileNavItems.forEach(function (item) {

        item.addEventListener(
            "click",
            function () {

                mobileNavItems.forEach(
                    function (nav) {

                        nav.classList.remove(
                            "active"
                        );

                    }
                );


                this.classList.add("active");

            }
        );

    });


    /* =================================================
       WISHLIST
    ================================================= */

    const wishlistButtons =
        document.querySelectorAll(
            ".aj-wishlist"
        );


    wishlistButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                const icon =
                    this.querySelector("i");


                if (
                    icon.classList.contains(
                        "bi-heart"
                    )
                ) {

                    icon.classList.remove(
                        "bi-heart"
                    );

                    icon.classList.add(
                        "bi-heart-fill"
                    );

                    this.style.color =
                        "#ef4444";

                } else {

                    icon.classList.remove(
                        "bi-heart-fill"
                    );

                    icon.classList.add(
                        "bi-heart"
                    );

                    this.style.color =
                        "";

                }

            }
        );

    });




    let wishlistCount = 0;

    function updateWishlistCount(count) {

        wishlistCount = count;

        $(".aj-mobile-wishlist-count").text(count);

        if (count <= 0) {
            $(".aj-mobile-wishlist-count").addClass("empty");
        } else {
            $(".aj-mobile-wishlist-count").removeClass("empty");
        }
    }

});


document.addEventListener('DOMContentLoaded', function () {
    const splash = document.getElementById('ajobmart-splash');

    if (!splash) return;

    // Show the splash screen for at least 1.5 seconds.
    setTimeout(function () {
        splash.classList.add('hide');

        setTimeout(function () {
            splash.remove();
        }, 450);
    }, 1500);
});


//To Fetch Populer product


function fetchHomeSection(url, containerId, sectionType, silent = false) {

    const container = $('#' + containerId);

    if (!container.length) {
        return;
    }

    // Show loading only on the initial page load
    if (!silent) {
        container.html(`
            <div class="text-center w-100 py-4">
                <span class="spinner-border spinner-border-sm"></span>
                Loading ${sectionType} products...
            </div>
        `);
    }

    $.ajax({
        url: site_url + url,
        method: 'GET',
        dataType: 'json',
        cache: false,

        success: function (response) {

            if (
                !response.status ||
                !Array.isArray(response.products) ||
                response.products.length === 0
            ) {
                // Don't erase existing products during background refresh
                if (!silent) {
                    container.empty();
                }

                container.closest('.aj-home-section').toggle(
                    Array.isArray(response.products) &&
                    response.products.length > 0
                );

                return;
            }

            container.closest('.aj-home-section').show();

            if (typeof window.ajCartRegisterProducts === 'function') {
                window.ajCartRegisterProducts(response.products);
            }

            let html = '';

            response.products.forEach(function (product) {
                html += renderHomeProductCard(product, sectionType);
            });

            container.html(html);

            if (typeof window.ajCartRefresh === 'function') {
                window.ajCartRefresh();
            }
        },

        error: function (xhr) {

            console.error(
                sectionType + ' loading error:',
                xhr.responseText
            );

            // Keep previously loaded products during background refresh
            if (!silent) {
                container.empty();
            }
        }
    });
}


function renderHomeProductCard(product, sectionType) {

    const name = product.product_name || 'Product';
    const category = product.category || 'General';

    const price = parseFloat(product.listing_price) || 0;
    const mrp = parseFloat(product.mrp) || 0;

    const wowDiscount = 20;
    const wowPrice = Math.floor(price - (price * wowDiscount / 100));

    const imagePath = product.image_path || '';

    const cleanImagePath = imagePath.replace(/^[/\\]+/, '');

    const imageUrl = imagePath
        ? site_url + 'shared-images/' + cleanImagePath
        : '';

    const discount = mrp > price && mrp > 0
        ? Math.round(((mrp - price) / mrp) * 100)
        : 0;

    const safeName = escapeHomeHtml(name);
    const safeCategory = escapeHomeHtml(category);
    const safeImageUrl = escapeHomeHtml(imageUrl);

    const productId = Number(product.id) || 0;

    const productUrl = site_url + 'product-details/' + productId;

    // Section badge
    let sectionBadge = '';

    if (sectionType === 'popular') {
        sectionBadge = 'POPULAR';
    } else if (sectionType === 'new') {
        sectionBadge = 'NEW';
    } else if (sectionType === 'deals') {
        sectionBadge = 'BEST DEAL';
    } else if (sectionType === 'trending') {
        sectionBadge = 'TRENDING';
    }

    return `
        

<div class="aj-product-card"
     data-product-id="${productId}">

    <!-- IMAGE CARD ONLY -->
    <div class="aj-product-image">

        <a href="${productUrl}"
           class="aj-product-details-link"
           aria-label="View ${safeName} details">

            ${imageUrl !== '' ? `
                <img
                    src="${safeImageUrl}"
                    alt="${safeName}"
                    class="aj-popular-product-img"
                    loading="lazy"
                    onerror="this.style.display='none';">
            ` : `
                <div class="aj-product-image-placeholder">
                    <i class="bi bi-box-seam"></i>
                </div>
            `}

        </a>

    </div>

    <!-- DETAILS OUTSIDE IMAGE CARD -->
    <div class="aj-product-info">

                <h3>
                    <a href="${productUrl}"
                    class="aj-product-title-link">
                        ${safeName}
                    </a>
                </h3>

                <div class="aj-product-price">

                    <strong>
                        ₹${price.toLocaleString('en-IN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}
                    </strong>

                    ${mrp > price ? `
                        <del>
                            ₹${mrp.toLocaleString('en-IN', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}
                        </del>
                    ` : ''}

                    

        </div>
        <div class="aj-recent-wow-section">
            <button
                type="button"
                class="aj-price-discount aj-add-cart wowofferbtn"
                data-product-id="${productId}"
                data-wow-offer-applied="1"
                aria-label="Add ${safeName} to cart with 20 percent WOW discount"
            >
                <span class="text-white" style="font-size:6px;">WOW</span>
                ₹${wowPrice.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}
            </button>
        </div>

    </div>

</div>


        `;
}

function escapeHomeHtml(value) {

    return String(value ?? '').replace(/[&<>"']/g, function (char) {

        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];

    });
}


/* =========================================
   AJOBMART - AJAX CATEGORY NAVIGATION
========================================= */

(function () {
    'use strict';

    const homeContent = document.getElementById('ajForYouContent');
    const categoryContent = document.getElementById('ajCategoryContent');

    const categoryTitle = document.getElementById('ajCategoryTitle');
    const categoryProducts = document.getElementById('ajCategoryProducts');
    const categoryLoader = document.getElementById('ajCategoryLoader');
    const categoryEnd = document.getElementById('ajCategoryEnd');
    const categorySentinel = document.getElementById('ajCategorySentinel');

    const categoryNav = document.getElementById('ajCategoryNav');

    if (
        !homeContent ||
        !categoryContent ||
        !categoryProducts ||
        !categoryNav
    ) {
        return;
    }

    let currentCategoryId = null;
    let currentCategoryName = '';
    let currentPage = 1;

    let isLoading = false;
    let hasMoreProducts = false;

    let categoryObserver = null;
    let requestNumber = 0;

    /* -------------------------------------
       ACTIVE NAVIGATION
    ------------------------------------- */

    function updateActiveNavigation(type, slug = '') {

        categoryNav.querySelectorAll('.aj-v3-nav-category')
            .forEach(function (link) {

                link.classList.remove('active');

                if (type === 'home' &&
                    link.dataset.ajNav === 'home') {

                    link.classList.add('active');
                }

                if (
                    type === 'category' &&
                    link.dataset.ajNav === 'category' &&
                    link.dataset.categorySlug === slug
                ) {

                    link.classList.add('active');
                }

            });
    }


    /* -------------------------------------
       CATEGORY LOADING INDICATORS
    ------------------------------------- */

    function showCategoryLoader() {

        categoryLoader.style.display = '';

        categoryEnd.style.display = 'none';
    }

    function hideCategoryLoader() {

        categoryLoader.style.display = 'none';
    }


    /* -------------------------------------
       RESET CATEGORY PAGINATION
    ------------------------------------- */

    function resetCategoryPage() {

        currentPage = 1;
        isLoading = false;
        hasMoreProducts = false;

        requestNumber++;

        if (categoryObserver) {
            categoryObserver.disconnect();
            categoryObserver = null;
        }

        categoryProducts.innerHTML = '';

        categoryEnd.style.display = 'none';

        hideCategoryLoader();
    }


    /* -------------------------------------
       LOAD CATEGORY PRODUCTS
    ------------------------------------- */

    function loadCategoryProducts(append = false) {

        if (!currentCategoryId || isLoading) {
            return;
        }

        if (append && !hasMoreProducts) {
            return;
        }

        isLoading = true;

        const thisRequest = ++requestNumber;

        showCategoryLoader();

        $.ajax({

            url: site_url +
                'home-products/category/' +
                encodeURIComponent(currentCategoryId),

            method: 'GET',

            data: {
                page: currentPage
            },

            dataType: 'json',

            cache: false,

            success: function (response) {

                // Ignore old responses if the customer
                // has already selected another category.
                if (thisRequest !== requestNumber) {
                    return;
                }

                if (
                    !response.status ||
                    !Array.isArray(response.products)
                ) {

                    if (!append) {
                        categoryProducts.innerHTML = `
                            <div class="text-center w-100 py-4">
                                Unable to load products.
                            </div>
                        `;
                    }

                    hasMoreProducts = false;

                    return;
                }

                if (response.products.length === 0) {

                    if (!append) {

                        categoryProducts.innerHTML = `
                            <div class="text-center w-100 py-4">
                                No products found in this category.
                            </div>
                        `;
                    }

                    hasMoreProducts = false;

                    categoryEnd.style.display = 'none';

                    return;
                }

                // Register products for cart functionality.
                if (
                    typeof window.ajCartRegisterProducts === 'function'
                ) {

                    window.ajCartRegisterProducts(
                        response.products
                    );
                }

                let html = '';

                response.products.forEach(function (product) {

                    html += renderHomeProductCard(
                        product,
                        'category'
                    );

                });

                if (append) {

                    categoryProducts.insertAdjacentHTML(
                        'beforeend',
                        html
                    );

                } else {

                    categoryProducts.innerHTML = html;
                }

                // Refresh cart state.
                if (
                    typeof window.ajCartRefresh === 'function'
                ) {

                    window.ajCartRefresh();
                }

                hasMoreProducts = response.has_more === true;

                currentPage++;

                if (!hasMoreProducts) {

                    categoryEnd.style.display = '';
                }

            },

            error: function (xhr) {

                if (thisRequest !== requestNumber) {
                    return;
                }

                console.error(
                    'Category products loading error:',
                    xhr.responseText
                );

                if (!append) {

                    categoryProducts.innerHTML = `
                        <div class="text-center w-100 py-4">
                            Something went wrong while loading products.
                        </div>
                    `;

                }

            },

            complete: function () {

                if (thisRequest !== requestNumber) {
                    return;
                }

                isLoading = false;

                hideCategoryLoader();
            }

        });

    }


    /* -------------------------------------
       CATEGORY INFINITE SCROLL
    ------------------------------------- */

    function startCategoryObserver() {

        if (categoryObserver) {
            categoryObserver.disconnect();
        }

        if (!categorySentinel) {
            return;
        }

        categoryObserver = new IntersectionObserver(

            function (entries) {

                entries.forEach(function (entry) {

                    if (
                        entry.isIntersecting &&
                        hasMoreProducts &&
                        !isLoading
                    ) {

                        loadCategoryProducts(true);
                    }

                });

            },

            {
                root: null,
                rootMargin: '300px',
                threshold: 0
            }

        );

        categoryObserver.observe(categorySentinel);
    }


    /* -------------------------------------
       OPEN CATEGORY
    ------------------------------------- */

    function openCategory(categoryId, slug, name, updateHistory = true) {

        if (!categoryId || !slug) {
            return;
        }

        currentCategoryId = Number(categoryId);
        currentCategoryName = name || 'Products';

        homeContent.style.display = 'none';
        categoryContent.style.display = '';

        categoryTitle.textContent = currentCategoryName;

        updateActiveNavigation('category', slug);

        resetCategoryPage();

        if (updateHistory) {

            const url = new URL(window.location.href);

            url.searchParams.set('category', slug);

            window.history.pushState(
                {
                    page: 'category',
                    slug: slug
                },
                '',
                url.pathname + url.search + url.hash
            );
        }

        loadCategoryProducts(false);

        startCategoryObserver();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    /* -------------------------------------
       OPEN FOR YOU / HOMEPAGE
    ------------------------------------- */

    function openHome(updateHistory = true) {

        currentCategoryId = null;
        currentCategoryName = '';

        resetCategoryPage();

        categoryContent.style.display = 'none';
        homeContent.style.display = '';

        updateActiveNavigation('home');

        if (updateHistory) {

            const url = new URL(window.location.href);

            url.searchParams.delete('category');

            window.history.pushState(
                {
                    page: 'home'
                },
                '',
                url.pathname + url.search + url.hash
            );
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    /* -------------------------------------
       CATEGORY NAVIGATION CLICK
    ------------------------------------- */

    categoryNav.addEventListener('click', function (event) {

        const link = event.target.closest('.aj-v3-nav-category');

        if (!link) {
            return;
        }

        if (link.dataset.ajNav === 'home') {

            event.preventDefault();

            openHome(true);

            return;
        }

        if (link.dataset.ajNav === 'category') {

            event.preventDefault();

            openCategory(
                link.dataset.categoryId,
                link.dataset.categorySlug,
                link.dataset.categoryName,
                true
            );

        }

    });


    /* -------------------------------------
       BROWSER BACK / FORWARD
    ------------------------------------- */

    window.addEventListener('popstate', function () {

        loadPageFromUrl();
    });


    /* -------------------------------------
       LOAD CATEGORY FROM URL
    ------------------------------------- */

    function loadPageFromUrl() {

        const params = new URLSearchParams(
            window.location.search
        );

        const slug = params.get('category');

        if (!slug) {

            openHome(false);

            return;
        }

        const link = categoryNav.querySelector(
            '[data-aj-nav="category"][data-category-slug="' +
            CSS.escape(slug) +
            '"]'
        );

        if (!link) {

            openHome(false);

            return;
        }

        openCategory(
            link.dataset.categoryId,
            link.dataset.categorySlug,
            link.dataset.categoryName,
            false
        );
    }


    /* -------------------------------------
       INITIAL PAGE
    ------------------------------------- */

    loadPageFromUrl();

})();


/* =========================================
   1. POPULAR PRODUCTS
========================================= */

function loadPopularProducts(silent = false) {

    fetchHomeSection(
        'fetchpopularproduct',
        'popularProductsContainer',
        'popular',
        silent
    );
}


/* =========================================
   2. NEW ARRIVALS
========================================= */

function loadNewArrivals(silent = false) {

    fetchHomeSection(
        'home-products/new-arrivals',
        'ajNewArrivals',
        'new',
        silent
    );
}


/* =========================================
   3. BEST DEALS
========================================= */

function loadBestDeals(silent = false) {

    fetchHomeSection(
        'home-products/best-deals',
        'ajBestDeals',
        'deals',
        silent
    );
}


/* =========================================
   4. TRENDING PRODUCTS
========================================= */

function loadTrendingProducts(silent = false) {

    fetchHomeSection(
        'home-products/trending',
        'ajTrendingProducts',
        'trending',
        silent
    );
}



/* BUDGET SECTIONS */

function loadUnder99(silent = false) {
    fetchHomeSection(
        'home-products/under-99',
        'ajUnder99',
        'under99',
        silent
    );
}

function loadUnder199(silent = false) {
    fetchHomeSection(
        'home-products/under-199',
        'ajUnder199',
        'under199',
        silent
    );
}

function loadDiscount30(silent = false) {
    fetchHomeSection(
        'home-products/discount-30',
        'ajDiscount30',
        'discount30',
        silent
    );
}

function loadDiscount50(silent = false) {
    fetchHomeSection(
        'home-products/discount-50',
        'ajDiscount50',
        'discount50',
        silent
    );
}




/* =========================================
   AJOBMART HOMEPAGE AUTO REFRESH
========================================= */

function refreshAllHomeSections(silent = true) {

    console.log(
        '[Ajobmart] Refreshing homepage products:',
        new Date().toLocaleTimeString()
    );

    loadPopularProducts(silent);
    loadNewArrivals(silent);
    loadBestDeals(silent);
    loadTrendingProducts(silent);

    loadUnder99(silent);
    loadUnder199(silent);

    loadDiscount30(silent);
    loadDiscount50(silent);
}


/* =========================================
   AUTO REFRESH TIMER
========================================= */

(function () {

    // Prevent multiple timers
    if (window.ajHomeRefreshTimer) {
        clearTimeout(window.ajHomeRefreshTimer);
    }

    function scheduleNextRefresh() {

        window.ajHomeRefreshTimer = setTimeout(function () {

            if (document.visibilityState === 'visible') {

                refreshAllHomeSections(true);

            } else {

                console.log(
                    '[Ajobmart] Tab is hidden. Refresh skipped.'
                );

            }

            scheduleNextRefresh();

        }, 60000);
    }


    $(document).ready(function () {

        // Initial product loading
        refreshAllHomeSections(false);

        // Start automatic refresh
        scheduleNextRefresh();

    });


    // Refresh immediately when user returns to the tab
    document.addEventListener('visibilitychange', function () {

        if (document.visibilityState === 'visible') {

            console.log(
                '[Ajobmart] Tab active. Refreshing products.'
            );

            refreshAllHomeSections(true);

        }

    });

})();



/* =========================================
   AJOBMART HOMEPAGE CAROUSEL
========================================= */


(function () {
    'use strict';

    const carousel = document.getElementById('ajHomeCarousel');

    if (!carousel) return;

    const track = carousel.querySelector('.aj-v3-carousel-track');
    const slides = Array.from(
        carousel.querySelectorAll('.aj-v3-carousel-slide')
    );

    const dots = carousel.querySelectorAll('.aj-v3-carousel-dots button');
    const prev = carousel.querySelector('.aj-v3-carousel-prev');
    const next = carousel.querySelector('.aj-v3-carousel-next');

    if (!slides.length) return;

    let currentIndex = 0;
    let autoplay;

    // Position slides horizontally
    slides.forEach((slide, index) => {
        slide.style.transform = `translateX(${index * 100}%)`;
    });

    function showSlide(index) {

        currentIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {

            const position = i - currentIndex;

            slide.style.transform = `translateX(${position * 100}%)`;

            slide.classList.toggle('active', i === currentIndex);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    function startAutoplay() {
        stopAutoplay();

        autoplay = setInterval(function () {
            showSlide(currentIndex + 1);
        }, 3000);
    }

    function stopAutoplay() {
        if (autoplay) {
            clearInterval(autoplay);
            autoplay = null;
        }
    }

    prev?.addEventListener('click', function () {
        showSlide(currentIndex - 1);
        startAutoplay();
    });

    next?.addEventListener('click', function () {
        showSlide(currentIndex + 1);
        startAutoplay();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            showSlide(index);
            startAutoplay();
        });
    });

    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);

    showSlide(0);
    startAutoplay();

})();



/* =========================================
   AJOBMART - MORE PRODUCTS INFINITE SCROLL
========================================= */

(function () {

    let currentPage = 1;
    let isLoading = false;
    let hasMoreProducts = true;

    const firstPageLimit = 12;
    const nextPageLimit = 6;

    const container = $('#moreProductsContainer');
    const loader = $('#ajMoreProductsLoader');
    const endMessage = $('#ajMoreProductsEnd');
    const sentinel = document.getElementById(
        'ajMoreProductsSentinel'
    );

    if (!container.length || !sentinel) {
        return;
    }

    function loadMoreProducts() {

        if (isLoading || !hasMoreProducts) {
            return;
        }

        isLoading = true;
        loader.show();

        $.ajax({
            url: site_url + 'home-products/more-products',
            method: 'GET',
            data: {
                page: currentPage
            },
            dataType: 'json',
            cache: false,

            success: function (response) {

                if (
                    !response.status ||
                    !Array.isArray(response.products)
                ) {
                    hasMoreProducts = false;
                    endMessage.show();
                    return;
                }

                if (response.products.length === 0) {
                    hasMoreProducts = false;

                    if (currentPage === 1) {
                        container.html(`
                            <div class="text-center w-100 py-4">
                                No products available.
                            </div>
                        `);
                    }

                    endMessage.show();
                    return;
                }

                // Register products for cart functionality.
                if (
                    typeof window.ajCartRegisterProducts === 'function'
                ) {
                    window.ajCartRegisterProducts(
                        response.products
                    );
                }

                let html = '';

                response.products.forEach(function (product) {

                    html += renderHomeProductCard(
                        product,
                        'more'
                    );

                });

                // Append instead of replacing existing products.
                container.append(html);

                // Refresh cart state.
                if (
                    typeof window.ajCartRefresh === 'function'
                ) {
                    window.ajCartRefresh();
                }

                currentPage++;

                hasMoreProducts = response.has_more === true;

                if (!hasMoreProducts) {
                    endMessage.show();
                }
            },

            error: function (xhr) {

                console.error(
                    'More products loading error:',
                    xhr.responseText
                );

                // Show retry option.
                loader.html(`
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            id="ajRetryMoreProducts">
                        Retry loading products
                    </button>
                `).show();

            },

            complete: function () {

                isLoading = false;

                if (hasMoreProducts) {
                    loader.hide();
                }

            }
        });
    }

    // First load: 12 products.
    loadMoreProducts();

    // Load the next batch when the customer scrolls near the bottom.
    const observer = new IntersectionObserver(
        function (entries) {

            entries.forEach(function (entry) {

                if (
                    entry.isIntersecting &&
                    hasMoreProducts &&
                    !isLoading
                ) {
                    loadMoreProducts();
                }

            });

        },
        {
            root: null,
            rootMargin: '300px',
            threshold: 0
        }
    );

    observer.observe(sentinel);

    // Retry failed requests.
    $(document).on(
        'click',
        '#ajRetryMoreProducts',
        function () {

            loader.html(`
                <span class="spinner-border spinner-border-sm"></span>
                <span class="ms-2">Loading more products...</span>
            `);

            loadMoreProducts();

        }
    );

    /* =========================================
   AJOBMART - RECENTLY VIEWED PRODUCTS
========================================= */

function loadRecentlyViewedProducts() {

    const section = document.getElementById(
        'ajRecentlyViewedSection'
    );

    const container = document.getElementById(
        'ajRecentlyViewed'
    );

    if (!section || !container) return;

    let recentlyViewed = [];

    try {
        recentlyViewed = JSON.parse(
            localStorage.getItem('aj_recently_viewed') || '[]'
        );
    } catch (error) {
        recentlyViewed = [];
    }

    if (!Array.isArray(recentlyViewed) || recentlyViewed.length === 0) {
        section.style.display = 'none';
        return;
    }

    // Remove invalid products
    recentlyViewed = recentlyViewed.filter(function (product) {
        return product && product.id;
    });

    if (recentlyViewed.length === 0) {
        section.style.display = 'none';
        return;
    }

    // Register products for existing cart functionality
    if (typeof window.ajCartRegisterProducts === 'function') {
        window.ajCartRegisterProducts(recentlyViewed);
    }

    let html = '';

    recentlyViewed.forEach(function (product) {

        html += renderHomeProductCard(
            product,
            'recent'
        );

    });

    container.innerHTML = html;

    section.style.display = '';

    // Refresh existing cart buttons
    if (typeof window.ajCartRefresh === 'function') {
        window.ajCartRefresh();
    }
}



/* Save recently viewed product */

function ajSaveRecentlyViewed(product) {

    if (!product || !product.id) return;

    let recentlyViewed = [];

    try {
        recentlyViewed = JSON.parse(
            localStorage.getItem('aj_recently_viewed') || '[]'
        );
    } catch (error) {
        recentlyViewed = [];
    }

    // Remove duplicate product
    recentlyViewed = recentlyViewed.filter(function (item) {
        return String(item.id) !== String(product.id);
    });

    // Add latest viewed product first
    recentlyViewed.unshift(product);

    // Maximum 12 products
    recentlyViewed = recentlyViewed.slice(0, 12);

    localStorage.setItem(
        'aj_recently_viewed',
        JSON.stringify(recentlyViewed)
    );

}


// Load on homepage
loadRecentlyViewedProducts();

})();

/* Save current product to Recently Viewed */


/* =========================================
   AJOBMART - SAVE RECENTLY VIEWED PRODUCT
========================================= */

(function () {

    function saveCurrentProduct() {

        const product = window.AJ_CURRENT_PRODUCT;

        if (!product || !product.id) {
            console.warn('Recently Viewed: Product data not found.');
            return;
        }

        let recentlyViewed = [];

        try {
            recentlyViewed = JSON.parse(
                localStorage.getItem('aj_recently_viewed') || '[]'
            );
        } catch (error) {
            recentlyViewed = [];
        }

        // Remove duplicate product
        recentlyViewed = recentlyViewed.filter(function (item) {
            return String(item.id) !== String(product.id);
        });

        // Save current product first
        recentlyViewed.unshift({
            id: Number(product.id),
            product_name: product.product_name || 'Product',
            listing_price: Number(product.listing_price) || 0,
            mrp: Number(product.mrp) || 0,
            image_path: product.image_path || ''
        });

        // Maximum 12 products
        recentlyViewed = recentlyViewed.slice(0, 12);

        localStorage.setItem(
            'aj_recently_viewed',
            JSON.stringify(recentlyViewed)
        );

        console.log(
            'Ajobmart Recently Viewed: Product saved',
            product
        );

    }

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            saveCurrentProduct,
            { once: true }
        );

    } else {

        saveCurrentProduct();

    }

})();
