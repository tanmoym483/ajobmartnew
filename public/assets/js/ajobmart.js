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

                <!-- PRODUCT IMAGE AREA -->
                <div class="aj-product-image">

                    ${discount > 0 && price > 0 && mrp > price ? `
                        <span class="aj-discount-ribbon">
                            <!--<i class="bi bi-lightning-fill"></i>-->
                            ${discount}% OFF
                        </span>
                    ` : ''}

                    <!-- WISHLIST -->
                    <button class="aj-wishlist"
                            type="button"
                            data-product-id="${productId}"
                            aria-label="Add to wishlist">

                        <i class="bi bi-heart"></i>

                    </button>

                    <!-- PRODUCT LINK -->
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

                <!-- PRODUCT INFORMATION -->
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

                    <!-- PRICE -->
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

