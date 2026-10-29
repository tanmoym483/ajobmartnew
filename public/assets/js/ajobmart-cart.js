
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

        // Support both database/API naming conventions.
        shipping_allowance: Number(
            item.shipping_allowance ??
            item.product_shipping_allowance ??
            item.shippingAllowance
        ) || 0,

        selling_price: Number(item.selling_price) || 0,
        mrp: Number(item.mrp) || 0,
        image_path: item.image_path || '',
        image_url: item.image_url || '',
        min_order_quantity: Math.max(
            1,
            Number(item.min_order_quantity) || 1
        ),
        stock_quantity: Number(item.stock_quantity) || 0
    };
}

    function syncCardControls() {
        $('.aj-product-cart-action').each(function () {
            const wrapper = $(this);
            const productId = Number(wrapper.data('product-id'));
            const item = findItem(productId);

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

    
function renderCart() {
    const container = $('#ajCartProducts');
    const empty = $('#ajCartEmpty');

    updateBadge();
    syncCardControls();

    const subtotalElement = document.getElementById('ajCartSubtotal');
    const deliveryElement = document.getElementById('ajCartDelivery');
    const totalElement = document.getElementById('ajCartTotal');

    if (!cartItems.length) {
        container.empty();
        empty.show();

        $('#ajCartSubtotal').text(money(0));
        $('#ajCartDelivery').text('FREE');
        $('#ajCartTotal').text(money(0));

        if (subtotalElement) {
            delete subtotalElement.dataset.baseSubtotal;
        }

        if (totalElement) {
            delete totalElement.dataset.shippingAmount;
            delete totalElement.dataset.shippingPincode;
        }

        window.AJ_SHIPPING_CHARGE = 0;
        return;
    }

    empty.hide();

    let subtotal = 0;
    let html = '';

    cartItems.forEach(function (item) {
        const id = Number(item.product_id);
        const quantity = Number(item.quantity) || 1;
        const price = Number(item.listing_price) || 0;
        const lineTotal = price * quantity;
        subtotal += lineTotal;

        const image = item.image_url ||
            (item.image_path
                ? config.sharedImageBaseUrl
                    ? config.sharedImageBaseUrl +
                      String(item.image_path).replace(/^[\\/]+/, '')
                    : ''
                : '');

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

                        <button type="button"
                                class="aj-cart-remove"
                                data-product-id="${id}"
                                aria-label="Remove product">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <strong class="aj-cart-line-total">
                        ${money(lineTotal)}
                    </strong>
                </div>
            </div>
        `;
    });

    container.html(html);
    $('#ajCartSubtotal').text(money(subtotal));

    // Preserve the latest product subtotal.
    if (subtotalElement) {
        subtotalElement.dataset.baseSubtotal = String(subtotal);
    }

    // Preserve shipping only when the estimate is still for the
    // current cart and its PIN has not been invalidated.
    // Preserve the extra shipping fee calculated for the selected PIN.
const shippingPin = totalElement?.dataset.shippingPincode || '';
const savedShipping = totalElement?.dataset.shippingAmount;

const hasSavedShipping =
    shippingPin !== '' &&
    savedShipping !== undefined &&
    Number.isFinite(Number(savedShipping)) &&
    shippingPin === (
        document.getElementById('ajShippingPin')?.value || ''
    ).trim();

const shipping = hasSavedShipping
    ? Math.max(0, Number(savedShipping))
    : 0;

window.AJ_SHIPPING_CHARGE = shipping;

$('#ajCartDelivery').text(
    hasSavedShipping
        ? (shipping > 0 ? money(shipping) : 'FREE')
        : 'Not calculated'
);

$('#ajCartTotal').text(money(subtotal + shipping));
}


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

        if (!item) {
            item = normalizeGuestItem({
                ...product,
                product_id: productId,
                quantity: minimum
            });

            cartItems.push(item);
        } else {
            item.quantity = newQuantity;
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

        request(config.addUrl, { product_id: productId })
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

            productCatalog[id] = {
                product_id: id,
                product_name: product.product_name || 'Product',
                listing_price: Number(product.listing_price) || 0,
                shipping_allowance: Number(
                    product.shipping_allowance ??
                    product.product_shipping_allowance ??
                    product.shippingAllowance
                ) || 0,
                mrp: Number(product.mrp) || 0,
                stock_quantity: Number(product.stock_quantity) || 0,
                min_order_quantity: Math.max(
                    1, Number(product.min_order_quantity) || 1
                ),
                image_path: product.image_path || '',
                image_url: product.image_path
                    ? (window.site_url || '') +
                      'shared-images/' +
                      String(product.image_path).replace(/^[\\/]+/, '')
                    : ''
            };
        });

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
            shipping_allowance: Number(item.shipping_allowance) || 0
        };
    }).filter(function (item) {
        return item.product_id > 0 && item.quantity > 0;
    });
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



})(jQuery);
