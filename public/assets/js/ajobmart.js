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

    let cartCount = 0;


    const cartCountDesktop =
        document.querySelector(
            ".aj-cart-count"
        );


    const cartCountMobile =
        document.querySelector(
            ".aj-mobile-cart-count"
        );


    const addCartButtons =
        document.querySelectorAll(
            ".aj-add-cart"
        );


    addCartButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function () {

                cartCount++;


                if (cartCountDesktop) {

                    cartCountDesktop.textContent =
                        cartCount;

                }


                if (cartCountMobile) {

                    cartCountMobile.textContent =
                        cartCount;

                }


                const originalHTML =
                    this.innerHTML;


                this.innerHTML =
                    '<i class="bi bi-check-lg"></i> Added';


                this.style.background =
                    "#15803d";


                setTimeout(
                    () => {

                        this.innerHTML =
                            originalHTML;

                        this.style.background =
                            "";

                    },
                    1000
                );

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

