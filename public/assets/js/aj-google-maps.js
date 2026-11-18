
(function () {
    'use strict';

    const config = window.AJ_CART_CONFIG || {};

    const modal = document.getElementById('ajShippingModal');
    const searchInput = document.getElementById('ajGoogleMapSearch');
    const suggestionsBox = document.getElementById('ajGoogleMapSuggestions');
    const mapElement = document.getElementById('ajGoogleMap');
    const detectButton = document.getElementById('ajDetectCurrentLocation');
    const selectedAddressElement = document.getElementById('ajSelectedMapAddress');

    if (!modal || !searchInput || !mapElement) {
        return;
    }

    let map = null;
    let marker = null;
    let geocoder = null;
    let autocompleteToken = null;
    let autocompleteLibrary = null;
    let mapReady = false;
    let mapsLoading = null;
    let searchTimer = null;
    let searchRequestId = 0;
    let selectedLocation = null;

    const defaultCenter = {
        lat: 22.5726,
        lng: 88.3639
    };

    function getElement(id) {
        return document.getElementById(id);
    }

    function showMessage(message, type = 'info') {
        const box = getElement('ajShippingMessage');

        if (!box) return;

        box.className = 'alert alert-' + type;
        box.textContent = message;
        box.classList.remove('d-none');
    }

    function hideMessage() {
        const box = getElement('ajShippingMessage');

        if (!box) return;

        box.classList.add('d-none');
        box.textContent = '';
    }

    function setField(id, value) {
        const element = getElement(id);

        if (element) {
            element.value = value || '';
        }
    }

    function getComponent(components, type) {
        const component = components.find(item =>
            Array.isArray(item.types) && item.types.includes(type)
        );

        return component
            ? (component.longText || component.long_name || '')
            : '';
    }

    function parseGoogleAddress(components) {

        const country = getComponent(components, 'country');

        if (country.toLowerCase() !== 'india') {
            throw new Error('Please select an address in India.');
        }

        const pincode = getComponent(components, 'postal_code')
            .replace(/\D/g, '');

        const state = getComponent(
            components,
            'administrative_area_level_1'
        );

        const city =
            getComponent(components, 'locality') ||
            getComponent(components, 'postal_town') ||
            getComponent(components, 'administrative_area_level_3') ||
            getComponent(components, 'sublocality_level_1') ||
            getComponent(components, 'administrative_area_level_2');

        const route = getComponent(components, 'route');
        const streetNumber = getComponent(components, 'street_number');
        const premise = getComponent(components, 'premise');

        const locality =
            getComponent(components, 'sublocality_level_1') ||
            getComponent(components, 'sublocality') ||
            getComponent(components, 'neighborhood');

        const addressLine1 = [
            streetNumber,
            route
        ].filter(Boolean).join(' ').trim() ||
            premise ||
            locality ||
            city;

        const addressLine2 = [
            locality,
            getComponent(components, 'sublocality_level_2')
        ]
            .filter(Boolean)
            .filter((value, index, array) =>
                value !== addressLine1 &&
                array.indexOf(value) === index
            )
            .join(', ');

        if (!/^\d{6}$/.test(pincode)) {
            throw new Error(
                'Google Maps could not identify a six-digit PIN code. Please enter it manually.'
            );
        }

        if (!city || !state || !addressLine1) {
            throw new Error(
                'The address details are incomplete. Please adjust the map location or enter the address manually.'
            );
        }

        return {
            pincode,
            city,
            state,
            address_line1: addressLine1,
            address_line2: addressLine2
        };
    }

    function updateMarker(position, draggable = true) {

        if (!marker) {

            marker = new google.maps.Marker({
                map: map,
                position: position,
                draggable: draggable,
                title: 'Delivery location'
            });

            marker.addListener('dragend', function (event) {
                if (event.latLng) {
                    reverseGeocode(event.latLng);
                }
            });

        } else {

            marker.setPosition(position);
            marker.setDraggable(draggable);
        }

        map.setCenter(position);
    }

    function fillAddressFields(result, formattedAddress, position) {

        selectedLocation = {
            latitude: position.lat(),
            longitude: position.lng(),
            formattedAddress: formattedAddress
        };

        setField('ajShippingPin', result.pincode);
        setField('ajShippingCity', result.city);
        setField('ajShippingState', result.state);
        setField('ajShippingAddress1', result.address_line1);
        setField('ajShippingAddress2', result.address_line2);

        setField(
            'ajShippingLatitude',
            position.lat()
        );

        setField(
            'ajShippingLongitude',
            position.lng()
        );

        if (selectedAddressElement) {
            selectedAddressElement.textContent = formattedAddress;
        }

        hideMessage();

        // Do not submit the form automatically.
        // Customer must confirm the selected address.
    }

    function processGeocodeResult(result, position) {

        if (!result || !result.address_components) {
            throw new Error('No address details found for this location.');
        }

        const parsed = parseGoogleAddress(result.address_components);

        fillAddressFields(
            parsed,
            result.formatted_address || '',
            position
        );

        updateMarker(position);

        return parsed;
    }

    async function reverseGeocode(position) {

        if (!geocoder) return;

        hideMessage();

        try {

            const response = await geocoder.geocode({
                location: {
                    lat: position.lat(),
                    lng: position.lng()
                }
            });

            if (!response.results || !response.results.length) {
                throw new Error('No address found at this location.');
            }

            // Prefer a postal address containing a PIN code.
            const result = response.results.find(item =>
                item.address_components &&
                item.address_components.some(component =>
                    component.types.includes('postal_code')
                )
            ) || response.results[0];

            processGeocodeResult(result, position);

        } catch (error) {

            console.error('Google reverse geocoding failed:', error);

            showMessage(
                error.message || 'Unable to identify this location.',
                'warning'
            );
        }
    }

    async function loadGoogleMaps() {

        if (mapReady) return;

        if (mapsLoading) {
            return mapsLoading;
        }

        const apiKey = String(config.googleMapsApiKey || '').trim();

        if (!apiKey) {
            throw new Error('Google Maps API key is missing from AJ_CART_CONFIG.');
        }

        mapsLoading = new Promise((resolve, reject) => {

            if (window.google && window.google.maps) {
                resolve();
                return;
            }

            const callbackName = 'ajGoogleMapsApiLoaded';

            window[callbackName] = function () {
                resolve();
                delete window[callbackName];
            };

            const script = document.createElement('script');

            script.src =
                'https://maps.googleapis.com/maps/api/js' +
                '?key=' + encodeURIComponent(apiKey) +
                '&v=weekly' +
                '&loading=async' +
                '&callback=' + callbackName;

            script.async = true;
            script.defer = true;

            script.onerror = function () {
                delete window[callbackName];
                reject(new Error('Google Maps could not be loaded.'));
            };

            document.head.appendChild(script);
        });

        try {
            await mapsLoading;
        } catch (error) {
            mapsLoading = null;
            throw error;
        }
    }

    async function initializeMap() {

        if (mapReady) return;

        await loadGoogleMaps();

        const libraries = await Promise.all([
            google.maps.importLibrary('maps'),
            google.maps.importLibrary('places')
        ]);

        const mapsLibrary = libraries[0];

        autocompleteLibrary = libraries[1];

        map = new mapsLibrary.Map(mapElement, {
            center: defaultCenter,
            zoom: 5,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            clickableIcons: false
        });

        geocoder = new google.maps.Geocoder();

        map.addListener('click', function (event) {

            if (!event.latLng) return;

            updateMarker(event.latLng);

            reverseGeocode(event.latLng);
        });

        mapReady = true;

        setupSearch();
    }

    function setupSearch() {

        if (searchInput.dataset.ajGoogleBound === '1') {
            return;
        }

        searchInput.dataset.ajGoogleBound = '1';

        searchInput.addEventListener('input', function () {

            clearTimeout(searchTimer);

            const query = searchInput.value.trim();

            if (query.length < 3) {
                suggestionsBox.classList.add('d-none');
                suggestionsBox.replaceChildren();
                return;
            }

            searchTimer = setTimeout(function () {
                fetchSuggestions(query);
            }, 350);
        });

        document.addEventListener('click', function (event) {

            if (
                !searchInput.contains(event.target) &&
                !suggestionsBox.contains(event.target)
            ) {
                suggestionsBox.classList.add('d-none');
            }
        });
    }

    async function fetchSuggestions(query) {

        if (!autocompleteLibrary) return;

        const requestId = ++searchRequestId;

        try {

            if (!autocompleteToken) {
                autocompleteToken =
                    new autocompleteLibrary.AutocompleteSessionToken();
            }

            const response =
                await autocompleteLibrary.AutocompleteSuggestion
                    .fetchAutocompleteSuggestions({
                        input: query,
                        includedRegionCodes: ['in'],
                        language: 'en',
                        region: 'in',
                        sessionToken: autocompleteToken
                    });

            if (requestId !== searchRequestId) return;

            suggestionsBox.replaceChildren();

            const predictions = (response.suggestions || [])
                .map(item => item.placePrediction)
                .filter(Boolean);

            if (!predictions.length) {
                suggestionsBox.classList.add('d-none');
                return;
            }

            predictions.forEach(prediction => {

                const button = document.createElement('button');

                button.type = 'button';
                button.textContent = prediction.text.toString();

                button.addEventListener('click', async function () {

                    suggestionsBox.classList.add('d-none');

                    try {

                        const place = prediction.toPlace();

                        await place.fetchFields({
                            fields: [
                                'location',
                                'formattedAddress',
                                'addressComponents',
                                'displayName'
                            ]
                        });

                        autocompleteToken = null;

                        if (!place.location) {
                            throw new Error('Location coordinates were not found.');
                        }

                        const components = place.addressComponents || [];

                        const parsed = parseGoogleAddress(components);

                        map.setZoom(16);

                        updateMarker(place.location);

                        fillAddressFields(
                            parsed,
                            place.formattedAddress || '',
                            place.location
                        );

                        searchInput.value =
                            place.formattedAddress || prediction.text.toString();

                    } catch (error) {

                        console.error('Place selection failed:', error);

                        showMessage(
                            error.message || 'Unable to select this address.',
                            'warning'
                        );
                    }
                });

                suggestionsBox.appendChild(button);
            });

            suggestionsBox.classList.remove('d-none');

        } catch (error) {

            console.error('Google Places search failed:', error);

            showMessage(
                'Unable to search locations. Please try again.',
                'warning'
            );
        }
    }

    async function detectCurrentLocation() {

        hideMessage();

        if (!navigator.geolocation) {
            showMessage(
                'Your browser does not support location detection.',
                'warning'
            );
            return;
        }

        detectButton.disabled = true;

        const originalText = detectButton.innerHTML;

        detectButton.textContent = 'Detecting your location...';

        try {

            const position = await new Promise((resolve, reject) => {

                navigator.geolocation.getCurrentPosition(
                    resolve,
                    reject,
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 300000
                    }
                );
            });

            const coordinates = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setZoom(17);

            const mapPosition = new google.maps.LatLng(
                coordinates.lat,
                coordinates.lng
            );

            updateMarker(mapPosition);

            await reverseGeocode(mapPosition);

        } catch (error) {

            console.error('Current location detection failed:', error);

            let message = 'Unable to detect your current location.';

            if (error.code === 1) {
                message = 'Please allow location permission in your browser.';
            } else if (error.code === 2) {
                message = 'Your current location is unavailable.';
            } else if (error.code === 3) {
                message = 'Location request timed out. Please try again.';
            }

            showMessage(message, 'warning');

        } finally {

            detectButton.disabled = false;
            detectButton.innerHTML = originalText;
        }
    }

    if (detectButton) {
        detectButton.addEventListener('click', async function () {

            try {
                await initializeMap();
                await detectCurrentLocation();
            } catch (error) {
                console.error(error);
                showMessage(error.message, 'danger');
            }
        });
    }

    modal.addEventListener('shown.bs.modal', async function () {

        try {

            await initializeMap();

            google.maps.event.trigger(map, 'resize');

            if (selectedLocation) {
                map.setCenter({
                    lat: selectedLocation.latitude,
                    lng: selectedLocation.longitude
                });
            }

        } catch (error) {

            console.error('Google Maps initialization failed:', error);

            showMessage(error.message, 'danger');
        }
    });

    // Expose map controls for other Ajobmart scripts if needed.
    window.ajGoogleMaps = {
        initialize: initializeMap,
        detectCurrentLocation: detectCurrentLocation,
        getSelectedLocation: function () {
            return selectedLocation;
        }
    };

})();
