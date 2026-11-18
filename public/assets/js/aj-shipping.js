(function () {
    'use strict';

    const config = window.AJ_CART_CONFIG || {};

    const endpoints = {
        customerAddress: config.shippingCustomerAddressUrl,
        customerAddresses: config.shippingCustomerAddressesUrl,
        estimate: config.shippingEstimateUrl,
        saveAddress: config.shippingSaveAddressUrl,
        saveDetectedAddress: config.shippingSaveDetectedAddressUrl
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
        !addressModalElement ||
        !form ||
        !pinInput ||
        !addressFields ||
        !submitButton ||
        !messageBox ||
        !window.bootstrap
    ) {
        console.error('Ajobmart shipping elements or Bootstrap were not found.');
        return;
    }

    const shippingModal = bootstrap.Modal.getOrCreateInstance(shippingElement);

    let loggedIn = Boolean(config.loggedIn);
    let savedAddress = null;
    let savedAddresses = [];
    let selectedAddress = null;
    const GUEST_PIN_KEY = 'ajobmart_guest_shipping_pin';

    let guestShippingPin =
        localStorage.getItem(GUEST_PIN_KEY) || '';
    
    const GUEST_LOCATION_KEY = 'ajobmart_guest_shipping_location';

    let guestShippingLocation =
        localStorage.getItem(GUEST_LOCATION_KEY) || '';

    const GUEST_ADDRESS_KEY = 'ajobmart_guest_shipping_address';

    let guestShippingAddress = {};

        try {
            guestShippingAddress = JSON.parse(
                localStorage.getItem(GUEST_ADDRESS_KEY) || '{}'
            );
        } catch (error) {
            guestShippingAddress = {};
        }

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


    /**
     * Get customer's current GPS location.
     */
    function getGuestCoordinates() {

        return new Promise(function (resolve, reject) {

            if (!navigator.geolocation) {
                reject(new Error('Location is not supported by this browser.'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    resolve({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    });
                },
                function (error) {
                    reject(error);
                },
                {
                    enableHighAccuracy: false,
                    timeout: 12000,
                    maximumAge: 600000
                }
            );
        });
    }


    /**
     * Convert GPS coordinates into an address using OpenStreetMap.
     */
    

async function getGuestAddressFromCoordinates(latitude, longitude) {

    const url = new URL(
        'https://nominatim.openstreetmap.org/reverse'
    );

    url.searchParams.set('format', 'jsonv2');
    url.searchParams.set('lat', latitude);
    url.searchParams.set('lon', longitude);
    url.searchParams.set('addressdetails', '1');
    url.searchParams.set('zoom', '18');
    url.searchParams.set('accept-language', 'en');

    const response = await fetch(url.toString(), {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'omit'
    });

    if (!response.ok) {
        throw new Error('Unable to find your delivery location.');
    }

    const data = await response.json();

    if (!data || !data.address) {
        throw new Error('No address found for your location.');
    }

    const address = data.address;

    // Only accept Indian addresses.
    const countryCode = String(
        address.country_code || ''
    ).toLowerCase();

    if (countryCode !== 'in') {
        throw new Error(
            'Automatic PIN detection is currently available for India only.'
        );
    }

    // Validate Indian PIN.
    const pin = String(
        address.postcode || ''
    ).replace(/\D/g, '');

    if (!/^\d{6}$/.test(pin)) {
        throw new Error(
            'Could not detect a valid six-digit PIN code.'
        );
    }

    // Detect city / town / village.
    const city =
        address.city ||
        address.town ||
        address.village ||
        address.municipality ||
        address.city_district ||
        address.county ||
        address.state_district ||
        '';

    const state = address.state || '';

    // Detect street / locality.
    const line1 =
        address.road ||
        address.residential ||
        address.neighbourhood ||
        address.suburb ||
        address.hamlet ||
        address.village ||
        address.town ||
        address.city_district ||
        address.county ||
        city;

    // Additional locality information.
    const line2 = [
        address.suburb,
        address.neighbourhood,
        address.city_district,
        address.state_district
    ]
        .filter(Boolean)
        .filter((value, index, array) =>
            array.indexOf(value) === index
        )
        .filter(value =>
            value !== line1 && value !== city
        )
        .join(', ');

    const location = [
        line1,
        city !== line1 ? city : '',
        state
    ]
        .filter(Boolean)
        .filter((value, index, array) =>
            array.indexOf(value) === index
        )
        .join(', ');

    if (!city || !state || !line1) {
        console.warn('Incomplete OSM address:', address);

        throw new Error(
            'The detected address is incomplete. Please enter it manually.'
        );
    }

    return {
        pincode: pin,
        city: city,
        state: state,
        address_line1: line1,
        address_line2: line2,
        landmark: '',
        location: location
    };
}




    /**
     * Automatically detect guest delivery PIN.
     */
    async function detectGuestDeliveryLocation() {

        // Never overwrite an existing manually selected PIN.
        guestShippingPin =
            localStorage.getItem(GUEST_PIN_KEY) || '';

        if (/^\d{6}$/.test(guestShippingPin)) {
            renderCartDeliveryAddress(null);
            return true;
        }

        try {

            const coordinates = await getGuestCoordinates();

            const result = await getGuestAddressFromCoordinates(
                coordinates.latitude,
                coordinates.longitude
            );

            guestShippingPin = result.pincode;
            guestShippingLocation = result.location;

            localStorage.setItem(
                GUEST_PIN_KEY,
                guestShippingPin
            );

            localStorage.setItem(
                GUEST_LOCATION_KEY,
                guestShippingLocation
            );

            // Update delivery bar.
            renderCartDeliveryAddress(null);

            // Calculate shipping only if cart has products.
            if (getCartItems().length > 0) {
                await estimateShipping(guestShippingPin);
            }

            return true;

        } catch (error) {

            console.warn(
                'Automatic delivery location detection failed:',
                error.message
            );

            // Fall back to your existing PIN-entry modal.
            renderCartDeliveryAddress(null);

            await openShippingGate(false);

            return false;
        }
    }


async function detectLoggedInDeliveryAddress() {

    try {

        // Get customer's GPS coordinates.
        const coordinates = await getGuestCoordinates();

        // Reverse geocode coordinates.
        const detected = await getGuestAddressFromCoordinates(
            coordinates.latitude,
            coordinates.longitude
        );

        // Save address through CI4.
        const response = await requestJson(
            endpoints.saveDetectedAddress,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pincode: detected.pincode,
                    city: detected.city,
                    state: detected.state,
                    address_line1: detected.address_line1,
                    address_line2: '',
                    landmark: '',
                    address_type: 'home'
                })
            }
        );

        if (!response || !response.success || !response.address) {
            throw new Error(
                response?.message || 'Unable to save detected address.'
            );
        }

        // Use the saved database address.
        selectedAddress = response.address;
        savedAddress = response.address;

        sessionStorage.setItem(
            'ajSelectedDeliveryAddressId',
            String(response.address.id)
        );

        // Display the address in the global delivery bar.
        renderCartDeliveryAddress(response.address);

        // Calculate shipping only if products exist.
        if (getCartItems().length > 0) {
            await estimateShipping(response.address.pincode);
        }

        console.log(
            'Customer delivery address:',
            response.message
        );

        return true;

    } catch (error) {

        console.error(
            'Automatic customer address detection failed:',
            error
        );

        // Fall back to manual address entry.
        await openShippingGate(true);

        return false;
    }
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
            listing_price: Number(
                item.listing_price ??
                item.product_listing_price ??
                item.price ??
                0
            ),

            selling_price: Number(
                item.selling_price ??
                item.product_selling_price ??
                0
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
    // const deliveryElement = document.getElementById('ajCartDelivery');
    const deliveryFeeElement = document.getElementById('ajCartDeliveryFee');
    const couponElement = document.getElementById('ajCartCouponDiscount');
    const platformElement = document.getElementById('ajCartPlatformFee');
    const totalElement = document.getElementById('ajCartTotal');

    if (
        !subtotalElement ||
        !deliveryFeeElement ||
        !couponElement ||
        !platformElement ||
        !totalElement
    ) {
        console.error('Cart charge breakdown elements were not found.');
        return;
    }

    const items = getCartItems();

    if (!items.length) {
        console.error('No cart items available for total calculation.');
        return;
    }

    // 1. PRODUCT SUBTOTAL
    // listing_price is the final product listing price.

    const subtotal = items.reduce(function (sum, item) {

        return sum +
            (Number(item.listing_price) || 0) *
            (Number(item.quantity) || 0);

    }, 0);

    const wowDiscount = Number(window.AJ_WOW_DISCOUNT) || 0;

    // 2. INCLUDED SHIPPING ALLOWANCE

    // 1. Calculate total included shipping allowance.
    const includedAllowance = items.reduce(function (sum, item) {

        const allowance = Math.max(
            0,
            Number(item.shipping_allowance) || 0
        );

        const quantity = Math.max(
            0,
            Number(item.quantity) || 0
        );

        return sum + (allowance * quantity);

    }, 0);

    console.log('========== DELIVERY FEE DEBUG ==========');

    console.table(items.map(function (item) {
        return {
            ProductID: item.product_id,
            Quantity: item.quantity,
            AllowancePerUnit: item.shipping_allowance,
            TotalAllowance:
                item.shipping_allowance * item.quantity
        };
    }));

    console.log({
        actualShipping: actualShippingAmount,
        includedAllowance: includedAllowance,
        calculatedDifference:
            Number(actualShippingAmount) - includedAllowance
    });

    console.log('========================================');


    // 2. Actual courier shipping.
    const actualShipping = Math.max(
        0,
        Number(actualShippingAmount) || 0
    );

    // SELLER SELLING PRICE TOTAL
    const sellingTotal = items.reduce(function (sum, item) {

        return sum +
            (Number(item.selling_price) || 0) *
            (Number(item.quantity) || 0);

    }, 0);

    // 3. Customer delivery fee.
    let deliveryFee = 0;

    if (actualShipping > includedAllowance) {
        deliveryFee = actualShipping - includedAllowance;
    }

    deliveryFee = Math.round(deliveryFee * 100) / 100;


    const breakdown = window.AJ_CART_BREAKDOWN || {};

    // 5. COUPON DISCOUNT

    // 5. CASHBACK DISCOUNT

    const availableCashback = Math.max(
        0,
        Number(window.AJ_CASHBACK_BALANCE) || 0
    );

    const couponDiscount = sellingTotal >= 100
        ? Math.min(availableCashback, sellingTotal / 6)
        : 0;

    // Remaining cashback balance after cart deduction.
    const remainingCashback = Math.max(
        0,
        availableCashback - couponDiscount
    );

    if (subtotal <= 0) {
        window.ajUpdateHeaderCashback(availableCashback);
    } else {
        window.ajUpdateHeaderCashback(remainingCashback);
    }
    // 6. PLATFORM FEE

    const configuredPlatformFee = Number(
        breakdown.platformFee
    );

    const platformFee = Math.max(
        0,
        Number.isFinite(configuredPlatformFee)
            ? configuredPlatformFee
            : 5
    );


    // 7. FINAL TOTAL

    const finalTotal = Math.max(
        0,
        subtotal -
        wowDiscount +
        deliveryFee +
        platformFee -
        couponDiscount
    );


    // 8. UPDATE CART DISPLAY

    subtotalElement.textContent = money(subtotal);

    // deliveryElement.textContent = deliveryFee > 0
    //     ? money(deliveryFee)
    //     : 'FREE';

    deliveryFeeElement.textContent = deliveryFee > 0
    ? money(deliveryFee)
    : 'FREE';

    couponElement.textContent = '−' + money(couponDiscount);

    platformElement.textContent = money(platformFee);

    totalElement.textContent = money(finalTotal);


    // 9. STORE CALCULATED VALUES

    subtotalElement.dataset.baseSubtotal = String(subtotal);

    totalElement.dataset.subtotal = String(subtotal);
    totalElement.dataset.shippingAmount = String(deliveryFee);
    totalElement.dataset.actualShipping = String(actualShipping);
    totalElement.dataset.includedAllowance = String(includedAllowance);
    totalElement.dataset.couponDiscount = String(couponDiscount);
    totalElement.dataset.platformFee = String(platformFee);
    totalElement.dataset.finalTotal = String(finalTotal);
    totalElement.dataset.shippingPincode = pinInput.value.trim();


    // 10. GLOBAL SHIPPING CHARGE

    window.AJ_SHIPPING_CHARGE = deliveryFee;

    // 11. DEBUGGING

    console.table({
        productSubtotal: subtotal,
        actualCourierShipping: actualShipping,
        includedShippingAllowance: includedAllowance,
        customerDeliveryFee: deliveryFee,
        couponDiscount: couponDiscount,
        platformFee: platformFee,
        finalPayable: finalTotal
    });

    return finalTotal;
}


// Make the total calculation accessible to cart JS.
window.ajUpdateCartTotals = updateCartTotals;

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

    const containers = document.querySelectorAll(
        '#ajCartDeliveryAddress, #ajCartPageDeliveryAddress'
    );

    containers.forEach(function (container) {

        container.replaceChildren();

        if (loggedIn && address) {

            const recipient = document.createElement('div');
            recipient.className = 'aj-delivery-recipient';

            recipient.textContent = [
                address.recipient_name,
                address.pincode
            ].filter(Boolean).join(' · ');

            const details = document.createElement('div');
            details.className = 'aj-delivery-address-text';

            details.textContent = [
                address.address_line1,
                address.city,
                address.state
            ].filter(Boolean).join(', ');

            container.append(recipient, details);

            return;
        }

        if (!loggedIn && guestShippingPin) {

            const pin = document.createElement('div');
            pin.className = 'aj-delivery-recipient';

            pin.textContent = 'PIN: ' + guestShippingPin;

            const details = document.createElement('div');
            details.className = 'aj-delivery-address-text';

            details.textContent =
                guestShippingLocation ||
                'Guest delivery location';

            container.append(pin, details);

            return;
        }

        const message = document.createElement('div');
        message.className = 'small text-muted';
        message.textContent = 'Select delivery address';

        container.appendChild(message);
    });
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

        // Persist the actual selected address
        if (selectedAddress) {
            sessionStorage.setItem(
                'ajSelectedDeliveryAddressId',
                String(selectedAddress.id)
            );
        }

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

        renderCartDeliveryAddress();
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
        console.log(
            'Actual destination shipping charge:',
            result.total_shipping
        );


        (result.seller_shipments || []).forEach((seller, index) => {

            console.log(`Seller ${index + 1}:`, seller.seller_id);

            console.table(
                (seller.available_couriers || []).map(courier => ({
                    Courier: courier.courier_name,
                    'Actual Shipping': courier.shipping,
                    'Customer Payable': courier.customer_cost,
                    Selected: courier.is_selected ? 'YES' : 'NO'
                }))
            );

            console.log('Automatically Selected:', seller.courier_name);
        });


        console.log('===========================================');

        updateCartTotals(result.total_shipping);
        return result;
    }

    async function refreshShippingAfterCartChange() {

    if (shippingRequestInProgress) return;

    const pin = loggedIn
        ? String(selectedAddress?.pincode || savedAddress?.pincode || '').trim()
        : String(guestShippingPin || '').trim();

    if (!/^\d{6}$/.test(pin)) {
        resetShippingOnCartChange();
        return;
    }

    const items = getCartItems();

    if (!items.length) {
        resetShippingOnCartChange();
        return;
    }

    shippingRequestInProgress = true;

    try {

        const result = await estimateShipping(pin);

        console.log(
            'Shipping recalculated after cart change:',
            result.total_shipping
        );

    } catch (error) {

        console.error(
            'Unable to refresh shipping after cart change:',
            error
        );

    } finally {

        shippingRequestInProgress = false;

    }
}

window.ajRefreshShippingAfterCartChange =
    refreshShippingAfterCartChange;

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

    // Save selected address
    selectedAddress = address;
    savedAddress = address;

    sessionStorage.setItem(
        'ajSelectedDeliveryAddressId',
        String(address.id)
    );

    pinInput.value = pin;

    renderSavedAddressList();
    renderCartDeliveryAddress(address);

    // Check cart products
    const cartItems = getCartItems();

    if (cartItems.length > 0) {

        // Calculate shipping only when products exist
        await estimateShipping(pin);

    } else {

        // No products: reset shipping calculation
        resetShippingOnCartChange();

    }

    // Refresh cart contents, including empty-cart state
    if (typeof window.ajCartRefresh === 'function') {
        await window.ajCartRefresh();
    }

    // Close address selection modal first
    const addressModal = bootstrap.Modal.getOrCreateInstance(
        addressModalElement
    );

    addressModalElement.addEventListener(
        'hidden.bs.modal',
        function openCartAfterAddressSelection() {

            // Open cart even when it has no products
            if (cartElement) {
                bootstrap.Modal
                    .getOrCreateInstance(cartElement)
                    .show();
            }

        },
        { once: true }
    );

    addressModal.hide();
}


    async function openCartFlow() {

    // =========================================
    // GUEST CUSTOMER
    // =========================================
    if (!config.loggedIn) {
        openShippingGate(false);
        return;
    }

    // =========================================
    // LOGGED-IN CUSTOMER
    // =========================================
    try {

        // First check the customer's saved address.
        await loadCustomerAddress();

        // If the default address was not returned,
        // check the complete saved-address list.
        if (!savedAddress) {
            await loadSavedAddresses();
        }

        const address = selectedAddress || savedAddress;

        // =====================================
        // NO SAVED ADDRESS
        // OPEN ADD NEW ADDRESS FORM FIRST
        // =====================================
        if (!address || !address.id) {

            selectedAddress = null;
            savedAddress = null;

            // Do NOT open the cart modal.
            // Open the new address form directly.
            await openShippingGate(true);

            return;
        }

        // =====================================
        // ADDRESS EXISTS
        // OPEN CART MODAL
        // =====================================
        selectedAddress = address;
        savedAddress = address;

        const pin = String(address.pincode || '').trim();

        if (pinInput && /^\d{6}$/.test(pin)) {
            pinInput.value = pin;
        }

        renderCartDeliveryAddress(address);

        // Open cart only after confirming an address exists.
        await showExistingCart();

        // Calculate shipping in the background.
        if (/^\d{6}$/.test(pin)) {

            estimateShipping(pin).catch(function (error) {
                console.error('Shipping estimate error:', error);
            });

        }

    } catch (error) {

        console.error('Cart/address loading error:', error);

        showMessage(
            error.message || 'Unable to load your delivery address.',
            'danger'
        );

    }
}


async function initializeGlobalDeliveryAddress() {

    // =====================================
    // GUEST CUSTOMER
    // =====================================

    if (!loggedIn) {

        guestShippingPin =
            localStorage.getItem(GUEST_PIN_KEY) || '';

        guestShippingLocation =
            localStorage.getItem(GUEST_LOCATION_KEY) || '';

        // Returning guest: display previously detected PIN.
        if (/^\d{6}$/.test(guestShippingPin)) {

            renderCartDeliveryAddress(null);

            if (getCartItems().length > 0) {
                await estimateShipping(guestShippingPin);
            }

            return;
        }

        // First-time guest: automatically detect on homepage.
        const isHomePage = !!document.querySelector('.aj-home-v3');

        if (isHomePage) {

            await detectGuestDeliveryLocation();

        } else {

            renderCartDeliveryAddress(null);

        }

        return;
    }

    // =====================================
    // LOGGED-IN CUSTOMER
    // =====================================

    try {

        await loadSavedAddresses();

        const address = selectedAddress || savedAddress;

        // First-time logged-in customer without an address.
        if (!address || !address.id) {

            selectedAddress = null;
            savedAddress = null;

            renderCartDeliveryAddress(null);

            await detectLoggedInDeliveryAddress();

            return;
        }

        selectedAddress = address;
        savedAddress = address;

        renderCartDeliveryAddress(address);

        // Calculate shipping only if cart has products.
        if (getCartItems().length > 0) {

            const pin = String(address.pincode || '').trim();

            if (/^\d{6}$/.test(pin)) {
                await estimateShipping(pin);
            }
        }

    } catch (error) {

        console.error(
            'Global delivery address initialization failed:',
            error
        );

    }
}


window.ajInitializeDeliveryAddress =
    initializeGlobalDeliveryAddress;

    /*
     * Cart header address selector:
     * - Logged-in customer: close cart and open the saved-address list.
     * - Guest: close cart and open the existing PIN modal.
     */
    const addressSelector = document.getElementById(
            'ajCartDeliverySelector'
        );

        if (addressSelector) {

            addressSelector.addEventListener('click', async function (event) {

                event.preventDefault();

                if (shippingRequestInProgress || addressSelectionInProgress) {
                    return;
                }

                try {

                    if (!loggedIn) {

                        await openShippingGate(false);

                        return;
                    }

                    await loadSavedAddresses();

                    if (!savedAddresses.length) {

                        await openShippingGate(true);

                        return;
                    }

                    openAddressModal();

                } catch (error) {

                    console.error('Unable to open delivery addresses:', error);

                    alert(
                        error.message || 'Unable to load delivery addresses.'
                    );
                }

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

            const selectedAddressElement = document.getElementById(
                'ajSelectedMapAddress'
            );

            guestShippingLocation =
                selectedAddressElement?.textContent.trim() || '';

            // Save guest PIN
            localStorage.setItem(
                GUEST_PIN_KEY,
                guestShippingPin
            );

            // Save detected Google Maps address
            localStorage.setItem(
                GUEST_LOCATION_KEY,
                guestShippingLocation
            );

            // Save complete guest address
            localStorage.setItem(
                GUEST_ADDRESS_KEY,
                JSON.stringify({
                    pincode: pin,
                    city: document.getElementById('ajShippingCity')?.value.trim() || '',
                    state: document.getElementById('ajShippingState')?.value.trim() || '',
                    address_line1: document.getElementById('ajShippingAddress1')?.value.trim() || '',
                    address_line2: document.getElementById('ajShippingAddress2')?.value.trim() || '',
                    landmark: document.getElementById('ajShippingLandmark')?.value.trim() || ''
                })
            );

            renderCartDeliveryAddress(null);
        }

            // Check whether cart contains products
            const cartItems = getCartItems();

            if (cartItems.length > 0) {

                // Calculate shipping only when products exist
                await estimateShipping(pin);

            } else {

                console.log(
                    'Delivery location saved. Shipping will be calculated when products are added.'
                );

                resetShippingOnCartChange();
            }

            if (loggedIn && selectedAddress) {

                renderCartDeliveryAddress(selectedAddress);

            } else {

                renderCartDeliveryAddress(null);

            }

            shippingModal.hide();

            // Refresh existing cart only if there are products
            if (cartItems.length > 0) {
                await showExistingCart();
            }

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



/* ======================================
   CART CHARGES DROPDOWN TOGGLER
====================================== */

const chargeToggle = document.getElementById('ajCartChargeToggle');
const chargeDetails = document.getElementById('ajCartChargeDetails');

if (chargeToggle && chargeDetails) {

    chargeToggle.addEventListener('click', function () {

        const isExpanded =
            chargeToggle.getAttribute('aria-expanded') === 'true';

        chargeToggle.setAttribute(
            'aria-expanded',
            String(!isExpanded)
        );

        chargeDetails.hidden = isExpanded;

    });

}


async function initializeCartPage() {

    const isCartPage = !!document.querySelector('.aj-cart-page');

    // CART PAGE
    if (isCartPage) {

        // Load saved address/PIN and display it.
        // Do not automatically open the shipping modal.
        try {
            if (loggedIn) {
                await loadSavedAddresses();

                const address = selectedAddress || savedAddress;

                if (address) {
                    selectedAddress = address;
                    savedAddress = address;
                    renderCartDeliveryAddress(address);

                    if (getCartItems().length > 0) {
                        await estimateShipping(address.pincode);
                    }
                } else {
                    renderCartDeliveryAddress(null);
                }

            } else {
                guestShippingPin =
                    localStorage.getItem(GUEST_PIN_KEY) || '';

                renderCartDeliveryAddress(null);

                if (
                    guestShippingPin &&
                    getCartItems().length > 0
                ) {
                    await estimateShipping(guestShippingPin);
                }
            }

        } catch (error) {
            console.error('Cart delivery initialization error:', error);
        }

        return;
    }

    // OTHER PAGES / HOMEPAGE
    await initializeGlobalDeliveryAddress();
}

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeCartPage,
        { once: true }
    );
} else {
    initializeCartPage();
}
})();