
(function ($) {
    'use strict';

    const config = window.AJ_CART_CONFIG;
    if (!config) return;

    const STORAGE_KEY = 'ajobmart_guest_cart_v1';

    let cartItems = [];
    let productCatalog = {};
    let cartRequestRunning = false;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[char];
        });
    }

    function money(value) {
        return '₹' + (Number(value) || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function readGuestCart() {
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            return Array.isArray(saved) ? saved : [];
        } catch (error) {
            return [];
        }
    }

    function saveGuestCart() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cartItems));
    }

    function findItem(productId) {
        return cartItems.find(item =>
            Number(item.product_id) === Number(productId)
        );
    }

    function cartCount() {
        return cartItems.reduce((sum, item) =>
            sum + Math.max(0, Number(item.quantity) || 0), 0
        );
    }

    function updateBadge() {
        const count = cartCount();

        $('.aj-mobile-cart-count').text(count).show();
        $('.aj-cart-count').text(count).show();

        $('.aj-cart-modal-items').text(
            count + (count === 1 ? ' Item' : ' Items')
        );
    }

    function normalizeGuestItem(item) {
        return {
            product_id: Number(item.product_id ?? item.productId ?? item.id),
            quantity: Number(item.quantity ?? item.qty) || 1,
            product_name: item.product_name || 'Product',

            listing_price: Number(item.listing_price) || 0,

            shipping_allowance: Number(
                item.shipping_allowance ??
                item.product_shipping_allowance ??
                item.shippingAllowance
            ) || 0,

            selling_price: Number(item.selling_price) || 0,
            mrp: Number(item.mrp) || 0,

            wow_offer_applied:
                Number(item.wow_offer_applied) === 1 ? 1 : 0,

            wow_offer_percent: Number(item.wow_offer_percent) || 20,

            wow_offer_discount: Number(item.wow_offer_discount) || 0,

            image_path: item.image_path || '',
            image_url: item.image_url || '',

            min_order_quantity: Math.max(
                1,
                Number(item.min_order_quantity) || 1
            ),

            stock_quantity: Number(item.stock_quantity) || 0
        };
    }

    window.AJ_CASHBACK_BALANCE = 0;

    async function ajLoadCashbackBalance() {


        if (!config.loggedIn || !config.cashbackBalanceUrl) {
            window.AJ_CASHBACK_BALANCE = 0;
            return;
        }

        try {
            const response = await fetch(config.cashbackBalanceUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.status) {
                window.AJ_CASHBACK_BALANCE =
                    Math.max(0, Number(data.cashback_balance) || 0);
            }

        } catch (error) {
            console.error('Cashback balance error:', error);
            window.AJ_CASHBACK_BALANCE = 0;
        }

        // Recalculate the existing cart totals
        if (typeof window.ajUpdateCartTotals === 'function') {
            window.ajUpdateCartTotals(
                Number(window.AJ_SHIPPING_CHARGE) || 0
            );
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        ajLoadCashbackBalance();
    });

    window.ajUpdateHeaderCashback = function (remainingBalance) {

        const element = document.getElementById(
            'ajHeaderCashbackAmount'
        );

        const elementmodal = document.getElementById(
            'ajmodalCashbackAmount'
        );
        

        if (!element) return;

        const amount = Math.max(
            0,
            Number(remainingBalance) || 0
        );

        element.textContent = '₹' + amount.toFixed(2);
        elementmodal.textContent = '₹' + amount.toFixed(2);

    };

    
function syncCardControls() {
    $('.aj-product-cart-action').each(function () {

        const wrapper = $(this);
        const productId = Number(wrapper.data('product-id'));
        const item = findItem(productId);

        // PRODUCT DETAILS PAGE
        if (wrapper.attr('id') === 'ajProductCartAction') {

            const addButton = wrapper.find('#ajProductAddCart');
            const goToCart = wrapper.find('#ajProductGoToCart');

            if (!item) {
                if (addButton.length) {
                    addButton.show();
                }

                if (goToCart.length) {
                    goToCart.hide();
                }

                return;
            }

            if (addButton.length) {
                addButton.hide();
            }

            if (goToCart.length) {
                goToCart.show();
            }

            return;
        }

        // PRODUCT LISTING CARDS
        if (!item) {
            wrapper.html(`
                <button type="button"
                        class="aj-add-cart"
                        data-product-id="${productId}">
                    <i class="bi bi-cart-plus"></i>
                    Add to Cart
                </button>
            `);

            return;
        }

        const minimum = Math.max(
            1, Number(item.min_order_quantity) || 1
        );

        wrapper.html(`
            <div class="aj-cart-quantity"
                 data-product-id="${productId}">
                <button type="button"
                        class="aj-cart-qty-btn"
                        data-action="decrement"
                        data-product-id="${productId}"
                        aria-label="Decrease quantity">−</button>

                <span class="aj-cart-qty-value">
                    ${Number(item.quantity)}
                </span>

                <button type="button"
                        class="aj-cart-qty-btn"
                        data-action="increment"
                        data-product-id="${productId}"
                        aria-label="Increase quantity">+</button>
            </div>
        `);
    });
}

// Update product detail Add to Cart button amount
window.ajUpdateProductAddCartAmount = function (amount) {
    const amountElement = document.getElementById('ajAddCartAmount');

    if (!amountElement) {
        return;
    }

    const price = Number(amount);

    if (!Number.isFinite(price)) {
        return;
    }

    amountElement.textContent = '₹' + price.toFixed(2);
};
// function syncCardControls() {
//         $('.aj-product-cart-action').each(function () {
//             const wrapper = $(this);
//             const productId = Number(wrapper.data('product-id'));
//             const item = findItem(productId);

//             if (!item) {
//                 wrapper.html(`
//                     <button type="button"
//                             class="aj-add-cart"
//                             data-product-id="${productId}">
//                         <i class="bi bi-cart-plus"></i>
//                         Add to Cart
//                     </button>
//                 `);
//                 return;
//             }

//             const minimum = Math.max(
//                 1, Number(item.min_order_quantity) || 1
//             );

//             wrapper.html(`
//                 <div class="aj-cart-quantity"
//                      data-product-id="${productId}">
//                     <button type="button"
//                             class="aj-cart-qty-btn"
//                             data-action="decrement"
//                             data-product-id="${productId}"
//                             aria-label="Decrease quantity">−</button>

//                     <span class="aj-cart-qty-value">
//                         ${Number(item.quantity)}
//                     </span>

//                     <button type="button"
//                             class="aj-cart-qty-btn"
//                             data-action="increment"
//                             data-product-id="${productId}"
//                             aria-label="Increase quantity">+</button>
//                 </div>
//             `);
//         });
//     }

    

function renderCart() {
    const container = $('#ajCartProducts');

    updateBadge();
    syncCardControls();

    const subtotalElement = document.getElementById('ajCartSubtotal');
    const deliveryElement = document.getElementById('ajCartDeliveryFee');
    const totalElement = document.getElementById('ajCartTotal');

    const hasItems = Array.isArray(cartItems) && cartItems.length > 0;

    // Show or hide cart sections.
    const productsSection = document.getElementById(
        'ajCartProductsSection'
    );

    const summarySection = document.getElementById(
        'ajCartSummarySection'
    );

    const trustSection = document.querySelector('.aj-cart-trust');
    const emptyCart = document.getElementById('ajEmptyCart');

    if (productsSection) {
        productsSection.style.display = hasItems ? '' : 'none';
    }

    if (summarySection) {
        summarySection.style.display = hasItems ? '' : 'none';
    }

    if (trustSection) {
        trustSection.style.display = hasItems ? '' : 'none';
    }

    if (emptyCart) {
        emptyCart.style.display = hasItems ? 'none' : '';
    }

    // EMPTY CART
    if (!hasItems) {
        container.empty();

        $('#ajCartSubtotal').text(money(0));
        $('#ajCartDeliveryFee').text('—');
        $('#ajCartCouponDiscount').text('-' + money(0));
        $('#ajCartPlatformFee').text(money(0));
        $('#ajCartTotal').text(money(0));

        if (subtotalElement) {
            delete subtotalElement.dataset.baseSubtotal;
        }

        if (totalElement) {
            delete totalElement.dataset.shippingAmount;
            delete totalElement.dataset.actualShipping;
            delete totalElement.dataset.shippingPincode;
        }

        window.AJ_SHIPPING_CHARGE = 0;

        if (typeof window.ajUpdateHeaderCashback === 'function') {
            window.ajUpdateHeaderCashback(
                Number(window.AJ_CASHBACK_BALANCE) || 0
            );
        }

        return;
    }

    // CART HAS PRODUCTS
    let totalMrp = 0;
    let subtotal = 0;
    let wowDiscount = 0;
    let html = '';

    cartItems.forEach(function (item) {
        const id = Number(item.product_id);
        const quantity = Number(item.quantity) || 1;
        const price = Number(item.listing_price) || 0;
        const lineTotal = price * quantity;
        const mrp = Number(item.mrp) || 0;
        const mrpTotal = mrp * quantity;

        totalMrp += mrpTotal;

        subtotal += lineTotal;
        // WOW offer discount: 20% per eligible cart item
        // WOW offer discount for eligible guest cart products
        // WOW offer discount per product
        item.wow_offer_discount = 0;

        if (Number(item.wow_offer_applied) === 1) {

            const wowPercent = Math.max(
                0,
                Number(item.wow_offer_percent) || 20
            );

            const itemDiscount = Math.floor(
                price * wowPercent / 100
            );

            item.wow_offer_discount = itemDiscount * quantity;

            wowDiscount += item.wow_offer_discount;
        }
        

        const imagePath = String(item.image_path || '')
            .replace(/^[\\/]+/, '')
            .replace(/^shared-images\//, '');

        const siteBase = String(
            config.siteUrl || window.site_url || ''
        ).trim();

        let image = '';

        if (item.image_url && /^https?:\/\//i.test(item.image_url)) {
            image = item.image_url;
        } else if (imagePath && siteBase) {
            image = new URL(
                'shared-images/' + imagePath,
                siteBase.replace(/\/?$/, '/')
            ).href;
        }

        html += `
            <div class="aj-cart-product" data-product-id="${id}">

                <div class="aj-cart-product-image">
                    ${image
                        ? `<img src="${escapeHtml(image)}"
                                alt="${escapeHtml(item.product_name)}"
                                onerror="this.style.display='none'">`
                        : `<div class="aj-product-image-placeholder">📦</div>`
                    }
                </div>

                <div class="aj-cart-product-details">

                    <!-- LEFT: PRODUCT INFORMATION -->
                    <div class="aj-cart-product-info">

                        <h6>${escapeHtml(item.product_name || 'Product')}</h6>

                        <span class="aj-cart-product-price">
                            ${money(price)}
                        </span>

                        <div class="aj-cart-product-bottom">

                            <div class="aj-cart-quantity">

                                <button type="button"
                                        class="aj-cart-qty-btn"
                                        data-action="decrement"
                                        data-product-id="${id}">−</button>

                                <span>${quantity}</span>

                                <button type="button"
                                        class="aj-cart-qty-btn"
                                        data-action="increment"
                                        data-product-id="${id}">+</button>

                            </div>
                            <!--
                            <button type="button"
                                    class="aj-cart-remove"
                                    data-product-id="${id}"
                                    aria-label="Remove product">
                                <i class="bi bi-trash"></i>
                            </button>
                            -->
                        </div>

                        <strong class="aj-cart-line-total">
                            ${money(lineTotal)}
                        </strong>

                    </div>

                    <!-- RIGHT: WOW OFFER -->
                    <div class="aj-cart-wow-item ${Number(item.wow_offer_applied) === 1 ? 'is-applied' : 'is-available'}"
                        data-wow-product-id="${id}">

                        <!-- Toggle Header -->
                        <button type="button"
                                class="aj-wow-toggle"
                                aria-expanded="false"
                                data-product-id="${id}">

                            <span class="aj-wow-toggle-left">
                                <i class="bi bi-stars"></i>

                                <span>
                                    ${Number(item.wow_offer_applied) === 1
                                        ? 'WOW OFFER APPLIED'
                                        : 'SPECIAL WOW OFFER'}
                                </span>
                            </span>

                            <i class="bi bi-chevron-down aj-wow-arrow"></i>

                        </button>

                        <!-- Expandable Offer Section -->
                        <div class="aj-wow-expand">

                            <div class="aj-wow-expand-inner">

                                <div class="aj-wow-card-description">
                                    ${Number(item.wow_offer_applied) === 1
                                        ? 'Your exclusive WOW discount is active!'
                                        : 'Unlock an exclusive discount on this product.'}
                                </div>

                                ${
                                    Number(item.wow_offer_applied) === 1
                                    ? `
                                        <button type="button"
                                                class="aj-cart-wow-remove text-danger"
                                                data-product-id="${id}">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Remove Offer
                                        </button>
                                    `
                                    : `
                                        <button type="button"
                                                class="aj-cart-wow-apply"
                                                data-product-id="${id}">
                                            <i class="bi bi-lightning-fill"></i>
                                            Apply WOW Offer
                                        </button>
                                    `
                                }

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        `;
    });

    container.html(html);

    $('#ajCartSubtotal').text(money(subtotal));

    // WOW OFFER DISCOUNT DISPLAY
    const wowRow = document.getElementById('ajCartWowDiscount');
    const wowDiscountRow = document.getElementById('ajCartWowDiscountRow');
    const wowPercentElement = document.getElementById('ajCartWowPercent');

    if (wowRow && wowDiscountRow) {

        if (wowDiscount > 0) {

            wowDiscountRow.style.display = '';

            wowRow.textContent = '-' + money(wowDiscount);

        } else {

            wowDiscountRow.style.display = 'none';

            wowRow.textContent = '-' + money(0);
        }
    }

    if (wowPercentElement) {
        wowPercentElement.textContent = 'WOW OFFER';
    }

    // Store values for the existing cart totals calculation
    window.AJ_WOW_DISCOUNT = wowDiscount;
    window.AJ_CART_SUBTOTAL = subtotal;

    const mrpElement = document.getElementById('ajCartMrp');
    const savingsElement = document.getElementById('ajCartTotalSavings');

    const totalSavings =
        
        totalMrp - subtotal;


    if (savingsElement) {
        savingsElement.textContent = '₹' + totalSavings.toFixed(2);
    }

    if (mrpElement) {
        mrpElement.textContent = '₹' + totalMrp.toFixed(2);
    }

    if (subtotalElement) {
        subtotalElement.dataset.baseSubtotal = String(subtotal);
    }

    const shippingPin =
        totalElement?.dataset.shippingPincode || '';

    const savedShipping =
        totalElement?.dataset.shippingAmount;

    const actualShipping =
        Number(totalElement?.dataset.actualShipping);

    const currentPin = (
        document.getElementById('ajShippingPin')?.value || ''
    ).trim();

    const hasSavedShipping =
        shippingPin !== '' &&
        savedShipping !== undefined &&
        Number.isFinite(actualShipping) &&
        shippingPin === currentPin;

    
    if (hasSavedShipping) {

        if (typeof window.ajUpdateCartTotals === 'function') {
            window.ajUpdateCartTotals(actualShipping);
        }

    } else {

        $('#ajCartDeliveryFee').text('Not calculated');
        $('#ajCartCouponDiscount').text('-' + money(0));
        $('#ajCartPlatformFee').text(money(5));
        $('#ajCartTotal').text(money(subtotal + 5));

        window.AJ_SHIPPING_CHARGE = 0;
    }

    // Recalculate shipping after cart changes.
    if (typeof window.ajRefreshShippingAfterCartChange === 'function') {
        window.ajRefreshShippingAfterCartChange();
    }
}

// APPLY WOW OFFER
$(document).on('click', '.aj-cart-wow-apply', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const productId = Number($(this).data('product-id'));

    updateCartWowOffer(productId, 1, true);
});

// REMOVE WOW OFFER
$(document).on('click', '.aj-cart-wow-remove', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const productId = Number($(this).data('product-id'));

    updateCartWowOffer(productId, 0, false);
});

function updateCartWowOffer(productId, applied, celebrate = false) {
    productId = Number(productId);
    applied = Number(applied);

    const item = findItem(productId);

    if (!item || ![0, 1].includes(applied)) {
        return;
    }

    // Guest customer
    if (!config.loggedIn) {
        item.wow_offer_applied = applied;
        item.wow_offer_percent = applied === 1 ? 20 : 0;

        item.wow_offer_discount = applied === 1
            ? Math.floor(Number(item.listing_price) * 20 / 100)
            : 0;

        saveGuestCart();
        renderCart();

        if (celebrate && applied === 1) {
            const updatedItem = findItem(productId);

            showWowCelebration(
                updatedItem?.wow_offer_discount || 0
            );
        }

        return;
    }

    // Logged-in customer
    request(config.wowOfferUrl, {
        product_id: productId,
        applied: applied
    })
    .then(function (response) {

        if (!response || response.status !== true) {
            Swal.fire(
                'Error',
                response?.message || 'Unable to update WOW Offer.',
                'error'
            );
            return;
        }

        if (Array.isArray(response.items)) {
            applyServerCart(response);
        } else {
            item.wow_offer_applied = applied;
            item.wow_offer_percent = applied === 1 ? 20 : 0;
            renderCart();
        }

        if (celebrate && applied === 1) {
            const updatedItem = findItem(productId);

            showWowCelebration(
                updatedItem?.wow_offer_discount || 0
            );
        }

    })
    .catch(function () {
        Swal.fire(
            'Error',
            'Unable to update WOW Offer.',
            'error'
        );
    });
}

function showWowCelebration(discountAmount) {

    discountAmount = Number(discountAmount) || 0;

    if (typeof confetti === 'function') {
        confetti({
            particleCount: 160,
            spread: 100,
            startVelocity: 45,
            origin: { y: 0.65 }
        });

        setTimeout(function () {
            confetti({
                particleCount: 80,
                spread: 120,
                startVelocity: 35,
                origin: { y: 0.6 }
            });
        }, 250);
    }

    Swal.fire({
        icon: 'success',
        title: '🎊 WOW! YOU WON! 🏆',
        html: `
            <div class="aj-wow-swal-content">

                <div class="aj-wow-swal-emojis">
                    🎉 ✨ 🥳 💰 🎁
                </div>

                <div class="aj-wow-swal-amount">
                    ₹${discountAmount.toFixed(2)}
                </div>

                <div class="aj-wow-swal-message">
                    🥳 Congratulations! You saved money!
                </div>

                <div class="aj-wow-swal-emojis">
                    💃 🎊 ⭐ 🎉
                </div>

            </div>
        `,
        position: 'bottom',
        toast: true,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true
    });
}

$(document).on('click', '.aj-wow-toggle', function (e) {
    e.preventDefault();

    const productId = Number($(this).data('product-id'));

    const card = document.querySelector(
        `.aj-cart-wow-item[data-wow-product-id="${productId}"]`
    );

    if (!card) {
        return;
    }

    const isOpen = card.classList.toggle('is-open');

    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
});


    function applyServerCart(response) {
        if (!response || !response.status) {
            throw new Error(response?.message || 'Cart update failed.');
        }

        if (response.csrfHash) {
            config.csrfHash = response.csrfHash;
        }

        cartItems = (response.items || []).map(normalizeGuestItem);

        renderCart();
    }



    function request(url, data) {
        const payload = Object.assign({}, data || {});

        if (config.csrfName && config.csrfHash) {
            payload[config.csrfName] = config.csrfHash;
        }

        return $.ajax({
            url: url,
            type: 'POST',
            data: payload,
            dataType: 'json'
        }).then(function (response) {
            if (response.csrfHash) {
                config.csrfHash = response.csrfHash;
            }
            return response;
        });
    }

    document.addEventListener('aj:wow-offer-change', function (event) {
    const detail = event.detail || {};

    const productId = Number(detail.product_id);
    const applied = Number(detail.applied);

    if (!productId || ![0, 1].includes(applied)) {
        return;
    }

    // Guest cart: update localStorage.
    if (!config.loggedIn) {
        const item = findItem(productId);

        if (item) {

            item.wow_offer_applied = applied;

            item.wow_offer_percent = applied === 1 ? 20 : 0;

            item.wow_offer_discount = applied === 1
                ? Math.floor(
                    Number(item.listing_price) * 20 / 100
                )
                : 0;

            saveGuestCart();
            renderCart();
        }

        return;
    }

    // Logged-in customer: update the database through the controller.
    request(config.wowOfferUrl, {
        product_id: productId,
        applied: applied
    })
    .then(function (response) {
        if (response && response.status) {
            if (Array.isArray(response.items)) {
                applyServerCart(response);
            }
        } else {
            console.error(
                'Unable to update WOW offer:',
                response?.message || 'Unknown error'
            );
        }
    })
    .catch(function (error) {
        console.error('WOW offer update failed:', error);
    });
});

    function showError(message) {
        if (window.Swal) {
            Swal.fire({
                icon: 'warning',
                text: message
            });
        } else {
            alert(message);
        }
    }

    
    function addGuestProduct(productId) {
        const product = productCatalog[productId];

        if (!product) {
            showError('Please wait for products to finish loading.');
            return;
        }

        const minimum = Math.max(
            1, Number(product.min_order_quantity) || 1
        );

        const stock = Number(product.stock_quantity) || 0;

        let item = findItem(productId);

        const newQuantity = item
            ? Number(item.quantity) + minimum
            : minimum;

        if (newQuantity > stock) {
            showError('Maximum available stock reached.');
            return;
        }

        // Read WOW offer status from product details page.
        const wowElement = document.getElementById('ajWowOffer');

        const wowApplied =
            wowElement?.dataset.offerApplied === 'true' ? 1 : 0;

        const wowPercent = wowApplied === 1 ? 20 : 0;

        const wowDiscount = wowApplied === 1
            ? Math.floor(
                Number(product.listing_price) * wowPercent / 100
            )
            : 0;

        if (!item) {
            item = normalizeGuestItem({
                ...product,
                product_id: productId,
                quantity: minimum,

                wow_offer_applied: wowApplied,
                wow_offer_percent: wowPercent,
                wow_offer_discount: wowDiscount
            });

            cartItems.push(item);

        } else {
            item.quantity = newQuantity;

            item.wow_offer_applied = wowApplied;
            item.wow_offer_percent = wowPercent;
            item.wow_offer_discount = wowDiscount;
        }

        saveGuestCart();
        renderCart();
    }


    function addProduct(productId) {
        if (cartRequestRunning) return;

        if (!config.loggedIn) {
            addGuestProduct(productId);
            return;
        }

        cartRequestRunning = true;

        request(config.addUrl, { product_id: productId, wow_offer_applied: (
        document.getElementById('ajWowOffer')?.dataset.offerApplied === 'true'
            ? 1
            : 0
    ) })
            .done(function (response) {
                applyServerCart(response);
            })
            .fail(function (xhr) {
                showError(
                    xhr.responseJSON?.message ||
                    'Unable to add this product.'
                );
            })
            .always(function () {
                cartRequestRunning = false;
            });
    }

    function changeQuantity(productId, direction) {
        const item = findItem(productId);
        if (!item || cartRequestRunning) return;

        const minimum = Math.max(
            1, Number(item.min_order_quantity) || 1
        );

        const stock = Number(item.stock_quantity) || 0;
        const current = Number(item.quantity);
        const next = current + direction * minimum;

        // Decreasing below the MOQ removes the product.
        if (next < minimum) {
            removeProduct(productId);
            return;
        }

        if (next > stock) {
            showError('Maximum available stock reached.');
            return;
        }

        if (!config.loggedIn) {
            item.quantity = next;
            saveGuestCart();
            renderCart();
            return;
        }

        cartRequestRunning = true;

        request(config.updateUrl, {
            product_id: productId,
            quantity: next
        })
        .done(applyServerCart)
        .fail(function (xhr) {
            showError(
                xhr.responseJSON?.message ||
                'Unable to update quantity.'
            );
        })
        .always(function () {
            cartRequestRunning = false;
        });
    }

    function removeProduct(productId) {
        if (cartRequestRunning) return;

        if (!config.loggedIn) {
            cartItems = cartItems.filter(item =>
                Number(item.product_id) !== Number(productId)
            );

            saveGuestCart();
            renderCart();
            return;
        }

        cartRequestRunning = true;

        request(config.removeUrl, {
            product_id: productId
        })
        .done(applyServerCart)
        .fail(function (xhr) {
            showError(
                xhr.responseJSON?.message ||
                'Unable to remove product.'
            );
        })
        .always(function () {
            cartRequestRunning = false;
        });
    }

    // Popular products are dynamically replaced, so use delegated events.
    $(document).on('click', '.aj-add-cart', function () {
        addProduct(Number($(this).data('product-id')));
    });

    $(document).on('click', '.aj-cart-qty-btn', function () {
        const id = Number($(this).data('product-id'));
        const action = $(this).data('action');

        changeQuantity(id, action === 'increment' ? 1 : -1);
    });

    $(document).on('click', '.aj-cart-remove', function () {
        removeProduct(Number($(this).data('product-id')));
    });

    // Capture product information from your existing product cards.
    // $(document).on('click', '.aj-add-cart', function () {
    //     const id = Number($(this).data('product-id'));
    //     const card = $(this).closest('.aj-product-card');

    //     if (productCatalog[id]) return;

    //     productCatalog[id] = {
    //         product_id: id,
    //         product_name: card.find('h3').text().trim() || 'Product',
    //         listing_price: 0,
    //         mrp: 0,
    //         stock_quantity: 0,
    //         min_order_quantity: 1,
    //         image_path: '',
    //         image_url: card.find('.aj-popular-product-img').attr('src') || ''
    //     };
    // });

    // Call this from your existing AJAX success callback with response.products.
    window.ajCartRegisterProducts = function (products) {

        (products || []).forEach(function (product) {

            const id = Number(product.id);
            if (!id) return;

            const imagePath = product.image_path || '';

            productCatalog[id] = {
                product_id: id,
                product_name: product.product_name || 'Product',

                listing_price: Number(product.listing_price) || 0,

                shipping_allowance: Number(
                    product.shipping_allowance ??
                    product.product_shipping_allowance ??
                    product.shippingAllowance
                ) || 0,

                selling_price: Number(product.selling_price) || 0,

                mrp: Number(product.mrp) || 0,

                stock_quantity: Number(product.stock_quantity) || 0,

                min_order_quantity: Math.max(
                    1,
                    Number(product.min_order_quantity) || 1
                ),

                image_path: imagePath,

                image_url: imagePath
                    ? (window.site_url || '') +
                    'shared-images/' +
                    String(imagePath).replace(/^[\\/]+/, '')
                    : ''
            };
        });

        // Update existing guest cart with registered product information.
        if (!window.AJ_CART_CONFIG?.loggedIn) {

            let guestCart = readGuestCart();

            guestCart = guestCart.map(function (item) {

                const id = Number(
                    item.product_id || item.productId || item.id
                );

                const product = productCatalog[id];

                if (!product) return item;

                return {
                    ...item,
                    product_name: product.product_name,
                    listing_price: product.listing_price,
                    selling_price: product.selling_price,
                    shipping_allowance: product.shipping_allowance,
                    mrp: product.mrp,
                    stock_quantity: product.stock_quantity,
                    min_order_quantity: product.min_order_quantity,
                    image_path: product.image_path,
                    image_url: product.image_url
                };
            });

            cartItems = guestCart.map(normalizeGuestItem);
            saveGuestCart();
            renderCart();
        }

        syncCardControls();
    };

    // Open modal and refresh saved cart from the server.
    $('#cartModal').on('show.bs.modal', function () {
        if (config.loggedIn) {
            $.getJSON(config.fetchUrl)
                .done(function (response) {
                    if (response.csrfHash) {
                        config.csrfHash = response.csrfHash;
                    }
                    applyServerCart(response);
                })
                .fail(function () {
                    showError('Unable to load your saved cart.');
                });
        } else {
            cartItems = readGuestCart().map(normalizeGuestItem);
            renderCart();
        }
    });

    // Initial state.
    if (config.loggedIn) {
        $.getJSON(config.fetchUrl)
            .done(applyServerCart)
            .fail(function () {
                cartItems = [];
                renderCart();
            });
    } else {
        cartItems = readGuestCart().map(normalizeGuestItem);
        renderCart();
    }

    
    window.ajCartRefresh = function () {
        if (config.loggedIn) {
            return $.getJSON(config.fetchUrl).then(function (response) {
                applyServerCart(response);
                return cartItems;
            });
        }

        cartItems = readGuestCart().map(normalizeGuestItem);
        renderCart();

        return Promise.resolve(cartItems);
    };


    window.ajGetShippingCartItems = function () {

        return (cartItems || []).map(function (item) {
            return {
                product_id: Number(
                    item.product_id ?? item.productId ?? item.id
                ),
                quantity: Number(
                    item.quantity ?? item.qty ?? item.cart_quantity ?? 1
                ),
                listing_price: Number(item.listing_price) || 0,

                // Added selling price
                selling_price: Number(
                    item.selling_price ??
                    item.product_selling_price ??
                    0
                ),

                shipping_allowance: Number(item.shipping_allowance) || 0
            };
        }).filter(function (item) {
            return item.product_id > 0 && item.quantity > 0;
        });

    };

    window.ajCalculateCartShipping = async function (address) {

    const config = window.AJ_CART_CONFIG;

    if (!config || !address) {
        return;
    }

    const pincode = String(
        address.pincode || address.pin_code || ''
    ).trim();

    if (!/^[0-9]{6}$/.test(pincode)) {
        console.warn('Invalid delivery PIN code.');
        return;
    }

    const items = window.ajGetShippingCartItems();

    if (!items || !items.length) {
        return;
    }

    const feeElement = document.getElementById('ajCartDeliveryFee');

    if (feeElement) {
        feeElement.textContent = 'Calculating...';
    }

    try {

        const response = await fetch(config.shippingEstimateUrl, {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [config.csrfHeader]: config.csrfHash
            },

            body: JSON.stringify({
                pincode: pincode,
                items: items
            })

        });

        const result = await response.json();

        if (!response.ok || !result.success) {

            throw new Error(
                result.message || 'Unable to calculate delivery charge.'
            );

        }

        // Calculate customer-payable shipping across all sellers.
        const customerShipping = (
            result.seller_shipments || []
        ).reduce(function (total, seller) {

            return total + Number(seller.customer_shipping || 0);

        }, 0);

        window.AJ_SHIPPING_CHARGE = customerShipping;

        if (feeElement) {
            feeElement.textContent =
                '₹' + customerShipping.toFixed(2);
        }

        // Refresh the cart total using your existing calculation.
        if (typeof window.ajUpdateCartTotals === 'function') {
            window.ajUpdateCartTotals();
        }

        console.log('Shipping estimate:', result);

        return result;

    } catch (error) {

        console.error('Shipping calculation error:', error);

        window.AJ_SHIPPING_CHARGE = 0;

        if (feeElement) {
            feeElement.textContent = 'Unavailable';
        }

        return null;
    }
};


// $(async function () {
//     if (
//         sessionStorage.getItem('ajReopenCartAfterLogin') !== '1'
//     ) {
//         return;
//     }

//     // Remove the flag so the modal opens only once.
//     sessionStorage.removeItem('ajReopenCartAfterLogin');

//     try {
//         // The page must now have the authenticated session.
//         if (!window.AJ_CART_CONFIG?.loggedIn) {
//             console.error(
//                 'Login session is not active. Cannot restore cart.'
//             );
//             return;
//         }

//         // Refresh cart from the server.
//         await window.ajCartRefresh();

//         const modalElement = document.getElementById('cartModal');

//         if (!modalElement) {
//             console.error('Cart modal #cartModal was not found.');
//             return;
//         }

//         bootstrap.Modal
//             .getOrCreateInstance(modalElement)
//             .show();

//         console.log('Cart reopened successfully after login.');
//     } catch (error) {
//         console.error('Unable to restore cart after login:', error);
//     }
// });

window.ajToggleCartSections = function (hasItems) {

    const productsSection = document.getElementById(
        'ajCartProductsSection'
    );

    const summarySection = document.getElementById(
        'ajCartSummarySection'
    );

    const trustSection = document.querySelector('.aj-cart-trust');

    const emptyCart = document.getElementById('ajEmptyCart');

    if (productsSection) {
        productsSection.style.display = hasItems ? '' : 'none';
    }

    if (summarySection) {
        summarySection.style.display = hasItems ? '' : 'none';
    }

    if (trustSection) {
        trustSection.style.display = hasItems ? '' : 'none';
    }

    if (emptyCart) {
        emptyCart.style.display = hasItems ? 'none' : '';
    }
};

})(jQuery);
