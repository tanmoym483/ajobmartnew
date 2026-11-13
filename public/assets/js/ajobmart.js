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

        <button class="aj-wishlist"
                type="button"
                data-product-id="${productId}"
                aria-label="Add to wishlist">
            <i class="bi bi-heart"></i>
        </button>

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

                <span class="aj-price-discount">
                    ${discount}% off
                </span>
            ` : ''}

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

})();
