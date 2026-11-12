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


    /* =================================================
       DEMO CART
    ================================================= */

    // let cartCount = 0;


    // const cartCountDesktop =
    //     document.querySelector(
    //         ".aj-cart-count"
    //     );


    // const cartCountMobile =
    //     document.querySelector(
    //         ".aj-mobile-cart-count"
    //     );


    // const addCartButtons =
    //     document.querySelectorAll(
    //         ".aj-add-cart"
    //     );


    // addCartButtons.forEach(function (button) {

    //     button.addEventListener(
    //         "click",
    //         function () {

    //             cartCount++;


    //             if (cartCountDesktop) {

    //                 cartCountDesktop.textContent =
    //                     cartCount;

    //             }


    //             if (cartCountMobile) {

    //                 cartCountMobile.textContent =
    //                     cartCount;

    //             }


    //             const originalHTML =
    //                 this.innerHTML;


    //             this.innerHTML =
    //                 '<i class="bi bi-check-lg"></i> Added';


    //             this.style.background =
    //                 "#15803d";


    //             setTimeout(
    //                 () => {

    //                     this.innerHTML =
    //                         originalHTML;

    //                     this.style.background =
    //                         "";

    //                 },
    //                 1000
    //             );

    //         }
    //     );

    // });


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

let popularProductsLoaded = false;
let popularProductsRequestRunning = false;

function loadtofetchpopularProducts() {

    // Prevent overlapping AJAX requests
    if (popularProductsRequestRunning) {
        return;
    }

    const container = $('#popularProductsContainer');
    const refreshStatus = $('#popularRefreshStatus');

    popularProductsRequestRunning = true;

    $.ajax({
        url: site_url + 'fetchpopularproduct',
        method: 'GET',
        dataType: 'json',

        beforeSend: function () {

            // Show loading content only on the first load.
            if (!popularProductsLoaded) {
                container.html(`
                    <div class="text-center w-100 py-4">
                        <span class="spinner-border spinner-border-sm"></span>
                        Loading products...
                    </div>
                `);
            } else {
                // Keep existing products visible during refresh.
                refreshStatus
                    .removeClass('d-none')
                    .addClass('d-flex');
            }
        },

        success: function (response) {

            if (!response.status ||
                !response.products ||
                response.products.length === 0) {

                if (!popularProductsLoaded) {
                    container.html(`
                        <div class="text-center w-100 py-4">
                            No popular products available.
                        </div>
                    `);
                }

                return;
            }

            let html = '';

            response.products.forEach(function (product) {

                const name = product.product_name || 'Product';
                const category = product.category || 'General';
                const price = parseFloat(product.listing_price) || 0;
                const mrp = parseFloat(product.mrp) || 0;
                const imagePath = product.image_path || '';
                // const imageUrl = '';

                const cleanImagePath = imagePath.replace(/^[\\/]+/, '');

                const imageUrl = imagePath
                    ? site_url + 'shared-images/' + cleanImagePath
                    : '';                


                const discount = mrp > price && mrp > 0
                    ? Math.round(((mrp - price) / mrp) * 100)
                    : 0;

                const safeName = $('<div>').text(name).html();
                const safeCategory = $('<div>').text(category).html();

                const productId = Number(product.id) || 0;

                const productUrl = site_url + 'product-details/' + productId;

                html += `
                    <div class="aj-product-card">
                        <div class="aj-product-image">

                            ${discount > 0 ? `
                                <span class="aj-discount">${discount}%</span>
                            ` : ''}

                            <button class="aj-wishlist"
                                    type="button"
                                    aria-label="Add to wishlist">
                                <i class="bi bi-heart"></i>
                            </button>

                            <a href="${productUrl}"
                            class="aj-product-details-link"
                            aria-label="View ${safeName} details">

                                ${imageUrl !== '' ? `
                                    <img
                                        src="${$('<div>').text(imageUrl).html()}"
                                        alt="${safeName}"
                                        class="aj-popular-product-img"
                                        onerror="this.style.display='none';">
                                ` : `
                                    <div class="aj-product-image-placeholder">
                                        📦
                                    </div>
                                `}

                            </a>
                        </div>

                        <div class="aj-product-info">
                            <span class="aj-product-category">
                                ${safeCategory}
                            </span>

                            <h3>
                                <a href="${productUrl}" class="aj-product-title-link">
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
                            <!--
                            <div class="aj-product-cart-action"
                                data-product-id="${Number(product.id) || 0}">

                                <button type="button"
                                        class="aj-add-cart"
                                        data-product-id="${Number(product.id) || 0}">
                                    <i class="bi bi-cart-plus"></i>
                                    Add to Cart
                                </button>

                            </div>
                            -->
                        </div>
                    </div>
                `;
            });

            // Replace products only after fresh data arrives.
            // Register real product prices, stock, MOQ and image information.
            if (typeof window.ajCartRegisterProducts === 'function') {
                window.ajCartRegisterProducts(response.products);
            }

            // Replace products only after fresh data arrives.
            container.html(html);

            popularProductsLoaded = true;

            // Restore quantity controls after the cards are rendered.
            if (typeof window.ajCartRefresh === 'function') {
                window.ajCartRefresh();
            }
        },

        error: function (xhr) {
            console.error(
                'Popular products error:',
                xhr.responseText
            );

            // Don't remove existing products if a refresh fails.
            if (!popularProductsLoaded) {
                container.html(`
                    <div class="text-center w-100 py-4">
                        Unable to load products.
                        <button type="button"
                                class="btn btn-sm btn-outline-primary ms-2"
                                onclick="loadtofetchpopularProducts()">
                            Retry
                        </button>
                    </div>
                `);
            }
        },

        complete: function () {
            popularProductsRequestRunning = false;

            refreshStatus
                .removeClass('d-flex')
                .addClass('d-none');
        }
    });
}

$(document).ready(function () {

    console.log('Document ready');

    // Load products initially.
    loadtofetchpopularProducts();

    // Refresh every 10 seconds.
    // setInterval(function () {

    //     if (popularProductsLoaded) {
    //         $('#popularRefreshStatus')
    //             .removeClass('d-none')
    //             .addClass('d-flex');
    //     }

    //     loadtofetchpopularProducts();

    // }, 10000);

});

function loadHomeProductSection(url, containerId, sectionType) {

    const container = $('#' + containerId);

    $.ajax({
        url: site_url + url,
        method: 'GET',
        dataType: 'json',

        beforeSend: function () {
            container.html(`
                <div class="text-center w-100 py-4">
                    <span class="spinner-border spinner-border-sm"></span>
                    Loading...
                </div>
            `);
        },

        success: function (response) {

            if (!response.status ||
                !Array.isArray(response.products) ||
                response.products.length === 0) {

                container.html('');
                container.closest('.aj-home-section').hide();
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
            console.error(sectionType + ' loading error:', xhr.responseText);
            container.html('');
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

            <div class="aj-product-image">

                

                ${discount > 0 ? `
                    <span class="aj-discount">
                        ${discount}%
                    </span>
                ` : ''}

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
                            📦
                        </div>
                    `}

                </a>

            </div>

            <div class="aj-product-info">

                <span class="aj-product-category">
                    ${safeCategory}
                </span>

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

                <!--
                <div class="aj-product-cart-action"
                     data-product-id="${productId}">

                    <button type="button"
                            class="aj-add-cart"
                            data-product-id="${productId}">

                        <i class="bi bi-cart-plus"></i>
                        Add to Cart

                    </button>

                </div>
                -->

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

$(document).ready(function () {

    // Existing popular products endpoint
    loadHomeProductSection(
        'fetchpopularproduct',
        'popularProductsContainer',
        'popular'
    );

    // New arrivals
    loadHomeProductSection(
        'home-products/new-arrivals',
        'ajNewArrivals',
        'new'
    );

    // Best deals
    loadHomeProductSection(
        'home-products/best-deals',
        'ajBestDeals',
        'deals'
    );

    // Trending
    loadHomeProductSection(
        'home-products/trending',
        'ajTrendingProducts',
        'trending'
    );

});


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

