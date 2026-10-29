(function () {
    'use strict';

    const config = window.AJ_CART_CONFIG || {};

    const endpoints = {
        customerAddress: config.shippingCustomerAddressUrl,
        customerAddresses: config.shippingCustomerAddressesUrl,
        estimate: config.shippingEstimateUrl,
        saveAddress: config.shippingSaveAddressUrl
    };

    const shippingElement = document.getElementById('ajShippingModal');
    const cartElement = document.getElementById('cartModal');
    const addressModalElement = document.getElementById('ajAddressSelectModal');
    const form = document.getElementById('ajShippingForm');
    const pinInput = document.getElementById('ajShippingPin');
    const addressFields = document.getElementById('ajShippingAddressFields');
    const submitButton = document.getElementById('ajShippingSubmit');
    const messageBox = document.getElementById('ajShippingMessage');

    if (
        !shippingElement ||
        !cartElement ||
        !addressModalElement ||
        !form ||
        !pinInput ||
        !addressFields ||
        !submitButton ||
        !messageBox ||
        !window.bootstrap
    ) {
        console.error('Ajobmart shipping/cart modal elements or Bootstrap were not found.');
        return;
    }

    const shippingModal = bootstrap.Modal.getOrCreateInstance(shippingElement);

    let loggedIn = Boolean(config.loggedIn);
    let savedAddress = null;
    let savedAddresses = [];
    let selectedAddress = null;
    let guestShippingPin = '';
    let addingNewAddress = false;
    let shippingRequestInProgress = false;
    let addressSelectionInProgress = false;

    function showMessage(message, type = 'info') {
        messageBox.textContent = message;
        messageBox.className = 'alert alert-' + type;
    }

    function hideMessage() {
        messageBox.textContent = '';
        messageBox.className = 'alert alert-info d-none';
    }

    function csrfHeaders() {
        const headers = {
            Accept: 'application/json'
        };

        const tokenInput = document.querySelector(
            'input[name="' + (config.csrfName || 'csrf_test_name') + '"]'
        );

        const token = tokenInput?.value || config.csrfHash;

        if (token && config.csrfHeader) {
            headers[config.csrfHeader] = token;
        }

        return headers;
    }

    async function requestJson(url, options = {}) {
        if (!url) {
            throw new Error('A required shipping endpoint URL is missing.');
        }

        const headers = {
            ...csrfHeaders(),
            ...(options.headers || {})
        };

        if (options.body && !headers['Content-Type']) {
            headers['Content-Type'] = 'application/json';
        }

        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers
        });

        const result = await response.json().catch(() => ({}));

        if (!response.ok || result.success === false) {
            throw new Error(
                result.message || 'Unable to process your request.'
            );
        }

        return result;
    }

    function getCartItems() {
        if (typeof window.ajGetShippingCartItems === 'function') {
            return normalizeItems(window.ajGetShippingCartItems());
        }

        console.error(
            'window.ajGetShippingCartItems() is not available. ' +
            'Check that ajobmart-cart.js loaded successfully.'
        );

        return [];
    }

    function normalizeItems(items) {
        if (!Array.isArray(items)) {
            return [];
        }

        return items.map(function (item) {
            return {
                product_id: Number(
                    item.product_id ?? item.productId ?? item.id
                ),
                quantity: Number(
                    item.quantity ?? item.qty ?? item.cart_quantity ?? 1
                ),
                shipping_allowance: Number(
                    item.shipping_allowance ??
                    item.product_shipping_allowance ??
                    item.shippingAllowance
                ) || 0
            };
        }).filter(function (item) {
            return Number.isInteger(item.product_id) &&
                item.product_id > 0 &&
                Number.isInteger(item.quantity) &&
                item.quantity > 0;
        });
    }

    function money(value) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            maximumFractionDigits: 2
        }).format(Number(value) || 0);
    }

    function readAmount(element) {
        if (!element) return 0;

        return Number(
            String(element.textContent || '')
                .replace(/,/g, '')
                .replace(/[^\d.-]/g, '')
        ) || 0;
    }

    /*
     * Preserve the existing shipping allowance calculation:
     * extra delivery = max(actual destination shipping - included allowance, 0)
     */
    function updateCartTotals(actualShippingAmount) {
        const subtotalElement = document.getElementById('ajCartSubtotal');
        const deliveryElement = document.getElementById('ajCartDelivery');
        const totalElement = document.getElementById('ajCartTotal');

        if (!subtotalElement || !deliveryElement || !totalElement) {
            console.error('Cart total elements were not found.');
            return;
        }

        const items = getCartItems();

        const includedAllowance = items.reduce(function (sum, item) {
            return sum +
                (Number(item.shipping_allowance) || 0) *
                (Number(item.quantity) || 0);
        }, 0);

        const actualShipping = Math.max(
            0,
            Number(actualShippingAmount) || 0
        );

        const extraDeliveryFees = Math.max(
            0,
            actualShipping - includedAllowance
        );

        let subtotal = Number(subtotalElement.dataset.baseSubtotal);

        if (!Number.isFinite(subtotal)) {
            subtotal = readAmount(subtotalElement);
            subtotalElement.dataset.baseSubtotal = String(subtotal);
        }

        deliveryElement.textContent = extraDeliveryFees > 0
            ? money(extraDeliveryFees)
            : 'FREE';

        totalElement.textContent = money(subtotal + extraDeliveryFees);

        totalElement.dataset.shippingAmount = String(extraDeliveryFees);
        totalElement.dataset.actualShipping = String(actualShipping);
        totalElement.dataset.includedAllowance = String(includedAllowance);
        totalElement.dataset.shippingPincode = pinInput.value.trim();

        window.AJ_SHIPPING_CHARGE = extraDeliveryFees;
    }

    function resetShippingOnCartChange() {
        const totalElement = document.getElementById('ajCartTotal');

        if (totalElement) {
            delete totalElement.dataset.shippingAmount;
            delete totalElement.dataset.shippingPincode;
            delete totalElement.dataset.actualShipping;
            delete totalElement.dataset.includedAllowance;
        }

        const subtotalElement = document.getElementById('ajCartSubtotal');

        if (subtotalElement) {
            delete subtotalElement.dataset.baseSubtotal;
        }

        window.AJ_SHIPPING_CHARGE = 0;
    }

    function renderCartDeliveryAddress(address = selectedAddress) {
        const container = document.getElementById('ajCartDeliveryAddress');

        if (!container) return;

        container.replaceChildren();

        if (loggedIn && address) {
            const recipient = document.createElement('div');
            recipient.className = 'aj-delivery-recipient';
            recipient.textContent = [
                address.recipient_name,
                address.mobile
            ].filter(Boolean).join(' · ') || 'Selected recipient';

            const details = document.createElement('div');
            details.className = 'aj-delivery-address-text';
            details.textContent = [
                address.address_line1,
                address.address_line2,
                address.landmark,
                address.city,
                address.state,
                address.pincode
            ].filter(value => String(value || '').trim()).join(', ');

            const type = document.createElement('span');
            type.className = 'badge bg-light text-dark border mt-1';
            type.textContent = address.address_type || 'home';

            container.append(recipient, details, type);
            return;
        }

        if (!loggedIn && guestShippingPin) {
            const pin = document.createElement('div');
            pin.className = 'aj-delivery-recipient';
            pin.textContent = 'PIN: ' + guestShippingPin;

            const note = document.createElement('div');
            note.className = 'aj-delivery-address-text';
            note.textContent = 'Guest delivery estimate';

            container.append(pin, note);
            return;
        }

        const message = document.createElement('div');
        message.className = 'small text-muted';
        message.textContent = 'Delivery address not selected';
        container.appendChild(message);
    }

    function fillAddressForm(address) {
        const values = {
            recipient_name: address?.recipient_name || '',
            mobile: address?.mobile || '',
            pincode: address?.pincode || '',
            address_line1: address?.address_line1 || '',
            address_line2: address?.address_line2 || '',
            landmark: address?.landmark || '',
            city: address?.city || '',
            state: address?.state || '',
            address_type: address?.address_type || 'home'
        };

        Object.entries(values).forEach(function ([name, value]) {
            const field = form.elements.namedItem(name);

            if (field) {
                field.value = value;
            }
        });
    }

    async function loadCustomerAddress() {
        const result = await requestJson(endpoints.customerAddress, {
            method: 'GET'
        });

        loggedIn = result.logged_in === true;

        if (!loggedIn) {
            savedAddress = null;
            savedAddresses = [];
            selectedAddress = null;
            renderCartDeliveryAddress(null);
            return null;
        }

        if (result.address) {
            savedAddress = result.address;

            if (!selectedAddress) {
                selectedAddress = result.address;
            }
        } else {
            savedAddress = null;
        }

        return savedAddress;
    }

    async function loadSavedAddresses() {
        const result = await requestJson(endpoints.customerAddresses, {
            method: 'GET'
        });

        loggedIn = result.logged_in === true;

        savedAddresses = Array.isArray(result.addresses)
            ? result.addresses
            : [];

        if (!loggedIn) {
            selectedAddress = null;
            savedAddress = null;
            renderSavedAddressList();
            renderCartDeliveryAddress(null);
            return [];
        }

        const existingSelection = selectedAddress && savedAddresses.find(
            address =>
                Number(address.id) === Number(selectedAddress.id)
        );

        const storedAddressId = sessionStorage.getItem(
            'ajSelectedDeliveryAddressId'
        );

        selectedAddress =
            savedAddresses.find(
                address => Number(address.id) === Number(storedAddressId)
            ) ||
            savedAddresses.find(
                address => Number(address.id) === Number(selectedAddress?.id)
            ) ||
            savedAddresses.find(
                address => Number(address.is_default) === 1
            ) ||
            savedAddresses[0] ||
            null;

        savedAddress = selectedAddress;

        renderSavedAddressList();
        renderCartDeliveryAddress();

        return savedAddresses;
    }

    
function renderSavedAddressList() {
    const list = document.getElementById('ajSavedAddressList');

    if (!list) return;

    list.replaceChildren();

    if (!savedAddresses.length) {
        const empty = document.createElement('p');
        empty.className = 'text-muted small mb-0';
        empty.textContent =
            'No saved addresses yet. Add a new address to continue.';

        list.appendChild(empty);
        return;
    }

    savedAddresses.forEach(function (address) {
        const isSelected = selectedAddress &&
            Number(address.id) === Number(selectedAddress.id);

        const button = document.createElement('button');
        button.type = 'button';

        button.className =
            'btn w-100 text-start mb-2 p-3 rounded-3 aj-saved-address' +
            (isSelected ? ' aj-address-selected' : '');

        button.dataset.addressId = String(address.id);
        button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');

        const heading = document.createElement('div');
        heading.className = 'fw-semibold';
        heading.textContent = [
            address.recipient_name || 'Recipient',
            address.mobile
        ].filter(Boolean).join(' · ');

        const type = document.createElement('span');
        type.className = 'badge mt-1';
        type.textContent = address.address_type || 'Home';

        const details = document.createElement('div');
        details.className = 'small mt-2';
        details.textContent = [
            address.address_line1,
            address.address_line2,
            address.landmark,
            address.city,
            address.state,
            address.pincode
        ].filter(value => String(value || '').trim()).join(', ');

        const status = document.createElement('div');
        status.className = 'small mt-2 fw-semibold';
        status.textContent = isSelected
            ? '✓ Currently selected'
            : (
                Number(address.is_default) === 1
                    ? 'Default address'
                    : 'Deliver here'
            );

        button.append(heading, type, details, status);
        list.appendChild(button);
    });
}


    function openAddressModal() {
        bootstrap.Modal
            .getOrCreateInstance(addressModalElement)
            .show();
    }

    async function showExistingCart() {
        if (typeof window.ajCartRefresh === 'function') {
            await window.ajCartRefresh();
        }

        bootstrap.Modal
            .getOrCreateInstance(cartElement)
            .show();
    }

    async function estimateShipping(pin) {
        const items = getCartItems();

        if (!items.length) {
            throw new Error(
                'Your cart is empty or its items could not be read. ' +
                'Please refresh the cart and try again.'
            );
        }

        const result = await requestJson(endpoints.estimate, {
            method: 'POST',
            body: JSON.stringify({
                pincode: pin,
                items: items
            })
        });

        if (result.total_shipping === undefined ||
            !Number.isFinite(Number(result.total_shipping))) {
            throw new Error('The shipping service did not return a valid shipping amount.');
        }
        console.log('========== SHIPPING CALCULATION ==========');
console.log('Destination PIN:', pin);
console.log('Selected address:', selectedAddress);
console.log('Shipping API response:', result);
console.log('Actual destination shipping charge:', result.shipping_amount);
console.log('===========================================');

        updateCartTotals(result.total_shipping);
        return result;
    }

    async function openShippingGate(forceNewAddress = false) {
        hideMessage();
        addingNewAddress = false;

        try {
            await loadCustomerAddress();

            if (loggedIn && forceNewAddress) {
                addingNewAddress = true;

                form.reset();
                pinInput.value = '';
                addressFields.classList.remove('d-none');

                const typeField = form.elements.namedItem('address_type');

                if (typeField) {
                    typeField.value = 'home';
                }

                submitButton.textContent = 'Save address & check shipping';
                showMessage('Enter the new delivery address.', 'info');

            } else if (loggedIn && savedAddress) {
                selectedAddress = selectedAddress || savedAddress;
                pinInput.value = selectedAddress.pincode || savedAddress.pincode || '';
                fillAddressForm(selectedAddress);
                addressFields.classList.add('d-none');
                submitButton.textContent = 'Check shipping';

                showMessage('Shipping will be calculated for your selected address.', 'info');

            } else if (loggedIn) {
                addingNewAddress = true;
                form.reset();
                pinInput.value = '';
                addressFields.classList.remove('d-none');
                submitButton.textContent = 'Save address & check shipping';

                showMessage('Add a delivery address to continue.', 'info');

            } else {
                addressFields.classList.add('d-none');
                pinInput.value = guestShippingPin;
                submitButton.textContent = 'Check shipping';

                showMessage('Enter your 6-digit PIN code to estimate shipping.', 'info');
            }

            renderCartDeliveryAddress();

        } catch (error) {
            showMessage(error.message || 'Unable to load your saved address.', 'danger');
        }

        shippingModal.show();
    }

    async function selectDeliveryAddress(address) {
    if (!address || !address.id) {
        throw new Error('Please select a valid delivery address.');
    }

    const pin = String(address.pincode || '').trim();

    if (!/^\d{6}$/.test(pin)) {
        throw new Error('The selected address has an invalid PIN code.');
    }

    // Update BOTH selected-address variables.
    selectedAddress = address;
    savedAddress = address;

    // Preserve the selected address after a page refresh.
    sessionStorage.setItem(
        'ajSelectedDeliveryAddressId',
        String(address.id)
    );

    pinInput.value = pin;

    renderSavedAddressList();
    renderCartDeliveryAddress(address);

    // Calculate shipping for the newly selected address.
    await estimateShipping(pin);

    if (typeof window.ajCartRefresh === 'function') {
        await window.ajCartRefresh();
    }

    // Close the address modal, then reopen the cart.
    addressModalElement.addEventListener('hidden.bs.modal', function openCart() {
        bootstrap.Modal.getOrCreateInstance(cartElement).show();
    }, { once: true });

    bootstrap.Modal.getOrCreateInstance(addressModalElement).hide();
}

    async function openCartFlow() {
    try {
        if (config.loggedIn) {
            // Load all saved addresses and select the default one.
            await loadCustomerAddress();

            if (savedAddress) {
                // Set the saved address PIN.
                const pin = String(savedAddress.pincode || '').trim();

                if (pinInput && /^\d{6}$/.test(pin)) {
                    pinInput.value = pin;
                }

                // Render the address in the cart modal header.
                renderCartDeliveryAddress();

                // Calculate shipping before opening the cart.
                if (/^\d{6}$/.test(pin)) {
                    await estimateShipping(pin);
                }

                // Refresh cart totals and open the cart.
                await showExistingCart();
                return;
            }

            // No saved address: ask the customer to add one.
            openShippingGate(true);
            return;
        }

        // Guest customer.
        openShippingGate(false);

    } catch (error) {
        console.error('Cart opening / delivery address error:', error);

        // Keep the cart accessible if shipping calculation fails.
        renderCartDeliveryAddress();
        await showExistingCart();
    }
}

    /*
     * Cart header address selector:
     * - Logged-in customer: close cart and open the saved-address list.
     * - Guest: close cart and open the existing PIN modal.
     */
    const addressSelector = document.getElementById('ajCartDeliverySelector');

    if (addressSelector) {
        addressSelector.addEventListener('click', async function (event) {
            event.preventDefault();

            if (shippingRequestInProgress || addressSelectionInProgress) {
                return;
            }

            if (!loggedIn) {
                const cartModal = bootstrap.Modal.getOrCreateInstance(cartElement);

                cartElement.addEventListener('hidden.bs.modal', function onHidden() {
                    cartElement.removeEventListener('hidden.bs.modal', onHidden);
                    openShippingGate(false);
                }, { once: true });

                cartModal.hide();
                return;
            }

            const cartModal = bootstrap.Modal.getOrCreateInstance(cartElement);

            cartElement.addEventListener('hidden.bs.modal', async function onHidden() {
                cartElement.removeEventListener('hidden.bs.modal', onHidden);

                try {
                    await loadSavedAddresses();
                    openAddressModal();
                } catch (error) {
                    console.error('Unable to load saved addresses:', error);
                    await openShippingGate(false);
                    showMessage(error.message || 'Unable to load saved addresses.', 'danger');
                }
            }, { once: true });

            cartModal.hide();
        });
    }

    /*
     * Select a saved address from the address list.
     */
    const addressList = document.getElementById('ajSavedAddressList');

    if (addressList) {
        addressList.addEventListener('click', async function (event) {
            const button = event.target.closest('[data-address-id]');

            if (!button) return;

            const address = savedAddresses.find(
                item => Number(item.id) === Number(button.dataset.addressId)
            );

            if (!address) return;

            try {
                await selectDeliveryAddress(address);
            } catch (error) {
                console.error('Address selection failed:', error);
                alert(error.message || 'Unable to calculate shipping for this address.');
            }
        });
    }

    /*
     * Add a new address:
     * close address list first, then open a blank address form.
     */
    const addAddressButton = document.getElementById('ajAddNewAddress');

    if (addAddressButton) {
        addAddressButton.addEventListener('click', function () {
            const addressModal = bootstrap.Modal.getOrCreateInstance(
                addressModalElement
            );

            addressModalElement.addEventListener('hidden.bs.modal', function onHidden() {
                addressModalElement.removeEventListener('hidden.bs.modal', onHidden);
                openShippingGate(true);
            }, { once: true });

            addressModal.hide();
        });
    }

    /*
     * Cart-button entry points. Replace the old inline handler with this.
     */
    document.addEventListener('click', function (event) {
        const trigger = event.target.closest(
            '#ajMobileCartButton, #ajCartButton, [data-aj-shipping-cart-trigger]'
        );

        if (!trigger) return;

        event.preventDefault();
        event.stopPropagation();

        openCartFlow();
    }, true);

    /*
     * Submit shipping form:
     * - Guest: estimate using PIN.
     * - Logged-in with saved address: estimate using that address.
     * - Logged-in adding address: save it first, then estimate.
     */
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (shippingRequestInProgress) return;

        const pin = pinInput.value.trim();

        if (!/^\d{6}$/.test(pin)) {
            showMessage('Enter a valid 6-digit PIN code.', 'danger');
            pinInput.focus();
            return;
        }

        shippingRequestInProgress = true;
        submitButton.disabled = true;

        try {
            hideMessage();

            if (loggedIn && addingNewAddress) {
                const addressPayload = {
                    recipient_name: form.elements.recipient_name.value.trim(),
                    mobile: form.elements.mobile.value.trim(),
                    pincode: pin,
                    address_line1: form.elements.address_line1.value.trim(),
                    address_line2: form.elements.address_line2.value.trim(),
                    landmark: form.elements.landmark.value.trim(),
                    city: form.elements.city.value.trim(),
                    state: form.elements.state.value.trim(),
                    address_type: form.elements.address_type.value || 'home'
                };

                if (
                    !addressPayload.recipient_name ||
                    !/^\d{10}$/.test(addressPayload.mobile) ||
                    !addressPayload.address_line1 ||
                    !addressPayload.city ||
                    !addressPayload.state
                ) {
                    throw new Error(
                        'Please enter recipient name, valid 10-digit mobile, ' +
                        'address line 1, city and state.'
                    );
                }

                const saveResult = await requestJson(endpoints.saveAddress, {
                    method: 'POST',
                    body: JSON.stringify(addressPayload)
                });

                /*
                 * Reload the list so the selected address contains its database ID.
                 * If the save endpoint returns the address, prefer that record.
                 */
                await loadSavedAddresses();

                const savedFromResponse = saveResult.address || null;

                const savedFromList = savedAddresses.find(function (item) {
                    return String(item.pincode) === addressPayload.pincode &&
                        String(item.address_line1 || '').trim() === addressPayload.address_line1;
                });

                selectedAddress = savedFromResponse ||
                    savedFromList ||
                    savedAddresses[0] ||
                    addressPayload;

                savedAddress = selectedAddress;
                addingNewAddress = false;

                renderSavedAddressList();
                renderCartDeliveryAddress(selectedAddress);

            } else if (!loggedIn) {
                guestShippingPin = pin;
            }

            // Use the existing shipping estimate endpoint.
            await estimateShipping(pin);

            if (loggedIn && selectedAddress) {
                renderCartDeliveryAddress(selectedAddress);
            } else {
                renderCartDeliveryAddress(null);
            }

            shippingModal.hide();

            shippingElement.addEventListener('hidden.bs.modal', async function onHidden() {
                shippingElement.removeEventListener('hidden.bs.modal', onHidden);

                try {
                    await showExistingCart();
                } catch (error) {
                    console.error('Unable to open cart after shipping estimate:', error);
                }
            }, { once: true });

        } catch (error) {
            showMessage(error.message || 'Unable to calculate shipping.', 'danger');
        } finally {
            shippingRequestInProgress = false;
            submitButton.disabled = false;
        }
    });

    /*
     * Other cart code can call this after cart quantity changes or item removal.
     */
    window.ajResetShippingEstimate = resetShippingOnCartChange;

    /*
     * Optional public function if another part of your site needs to open the cart.
     */
    window.ajOpenCartWithShipping = openCartFlow;

    // Initial address label: it will update when the customer opens the cart.
    renderCartDeliveryAddress();
    

document.getElementById('ajSavedAddressList')?.addEventListener('click', async function (event) {
    const button = event.target.closest('[data-address-id]');

    if (!button) return;

    const addressId = Number(button.dataset.addressId);

    const address = savedAddresses.find(
        item => Number(item.id) === addressId
    );

    if (!address) {
        console.error('Selected address not found:', addressId);
        return;
    }

    console.log('Address clicked:', address);

    selectedAddress = address;
    savedAddress = address;

    renderSavedAddressList();

    console.log('Selected address updated:', selectedAddress);
    console.log('Destination PIN:', address.pincode);

    try {
        await estimateShipping(address.pincode);
        await window.ajCartRefresh();

        console.log('Shipping recalculated for:', address.pincode);
    } catch (error) {
        console.error('Address/shipping update failed:', error);
    }
});




$(document).on('click', '#ajCartCheckout', function (e) {
    e.preventDefault();

    if (!window.AJ_CART_CONFIG?.loggedIn) {
        window.ajLoginReturnToCart = true;

        const cartModalElement = document.getElementById('cartModal');

        if (cartModalElement) {
            const modal =
                bootstrap.Modal.getInstance(cartModalElement);

            if (modal) {
                modal.hide();
            }
        }

        setTimeout(function () {
            window.showLoginPopup();
        }, 400);

        return;
    }

    const address = selectedAddress || savedAddress;

    if (!address || !address.id) {
        alert('Please select a delivery address first.');
        return;
    }

    const url = new URL(
        window.AJ_CART_CONFIG.checkoutUrl,
        window.location.origin
    );

    url.searchParams.set('address_id', address.id);

    window.location.href = url.toString();
});




})();