
<style>

/* =========================================
   AJOBMART LIVE SEARCH MODAL
========================================= */

.aj-search-wrapper {
    position: relative;
    z-index: 10000;
    /* overflow: visible; */
}

.aj-search-container {
    position: relative;
}

.aj-search-box {
    position: relative;
}

/* Search clear button */

.aj-search-clear {
    border: none;
    background: transparent;
    color: #777;
    font-size: 17px;
    cursor: pointer;
    padding: 5px 10px;
}

/* Search modal */

.aj-search-results-modal {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.14);
    z-index: 99999;
    overflow: visible;
}

/* Modal header */

.aj-search-results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    border-bottom: 1px solid #eee;
}

/* Results list */

.aj-search-results-list {
    max-height: 380px;
    overflow-y: auto;
}

/* Individual product */

.aj-search-product {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    text-decoration: none;
    color: #222;
    border-bottom: 1px solid #f1f1f1;
    cursor: pointer;
    transition: background 0.2s;
}

.aj-search-product:hover {
    background: #f8f8f8;
}

/* Product image */

.aj-search-product-image {
    width: 55px;
    height: 55px;
    min-width: 55px;
    object-fit: contain;
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fff;
    padding: 3px;
}

/* Product details */

.aj-search-product-info {
    flex: 1;
    min-width: 0;
}

.aj-search-product-name {
    font-size: 13px;
    font-weight: 600;
    color: #222;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aj-search-product-brand {
    font-size: 11px;
    color: #888;
    margin-top: 3px;
}

.aj-search-product-price {
    font-size: 13px;
    font-weight: 700;
    color: #222;
    margin-top: 4px;
}

/* Loading / empty */

.aj-search-loading,
.aj-search-no-results {
    padding: 20px;
    text-align: center;
    font-size: 13px;
    color: #777;
}

/* View all */

.aj-search-view-all {
    width: 100%;
    padding: 13px;
    border: none;
    border-top: 1px solid #eee;
    background: #fafafa;
    color: #333;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.aj-search-view-all:hover {
    background: #f1f1f1;
}

/* Mobile */

@media (max-width: 767px) {

    .aj-search-results-modal {
        max-height: 70vh;
    }

    .aj-search-results-list {
        max-height: 55vh;
    }

    .aj-search-product {
        padding: 9px 11px;
    }

    .aj-search-product-image {
        width: 48px;
        height: 48px;
        min-width: 48px;
    }

}

</style>
<!-- ==============================
     SEARCH
================================ -->

<div
    class="aj-search-wrapper"
    id="ajSearchWrapper"
    data-suggestions-url="<?= site_url('products/search-suggestions') ?>"
    data-search-url="<?= site_url('products/search') ?>"
>

    <div class="aj-search-container">

        <form
            action="<?= site_url('products/search') ?>"
            method="get"
            id="ajSearchForm"
            class="aj-search-box"
        >

            <i class="bi bi-search aj-search-icon"></i>

            <input
                type="text"
                name="search"
                id="ajSearchInput"
                placeholder="Search products, brands and categories..."
                autocomplete="off"
            >

            <button
                type="button"
                id="ajSearchClear"
                class="aj-search-clear"
                style="display:none;"
                aria-label="Clear search"
            >
                <i class="bi bi-x-circle"></i>
            </button>

        </form>

        <!-- LIVE SEARCH MODAL -->

        <div
            class="aj-search-results-modal"
            id="ajSearchResultsModal"
            style="display:none;"
        >

            <div class="aj-search-results-header">
                <span>Search Suggestions</span>
                <span id="ajSearchResultCount"></span>
            </div>

            <div
                class="aj-search-results-list"
                id="ajSearchResultsList"
            ></div>

            <div
                class="aj-search-no-results"
                id="ajSearchNoResults"
                style="display:none;"
            >
                No products found.
            </div>

            <div
                class="aj-search-loading"
                id="ajSearchLoading"
                style="display:none;"
            >
                Searching products...
            </div>

            <button
                type="button"
                class="aj-search-view-all"
                id="ajSearchViewAll"
            >
                View all search results
                <i class="bi bi-arrow-right"></i>
            </button>

        </div>

    </div>

</div>

<div
    id="ajSearchPlaceholder"
    class="aj-search-placeholder"
></div>



<script>

/* =========================================
   AJOBMART LIVE PRODUCT SEARCH
========================================= */

document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.getElementById('ajSearchWrapper');
    const form = document.getElementById('ajSearchForm');
    const input = document.getElementById('ajSearchInput');

    const modal = document.getElementById('ajSearchResultsModal');
    const resultsList = document.getElementById('ajSearchResultsList');

    const loading = document.getElementById('ajSearchLoading');
    const noResults = document.getElementById('ajSearchNoResults');

    const resultCount = document.getElementById('ajSearchResultCount');
    const viewAll = document.getElementById('ajSearchViewAll');
    const clearButton = document.getElementById('ajSearchClear');

    if (!wrapper || !form || !input || !modal) {
        return;
    }

    const suggestionsUrl = wrapper.dataset.suggestionsUrl;
    let searchTimer = null;
    let controller = null;

    const defaultImage = '<?= base_url('assets/images/default-user.png') ?>';

    function openModal() {
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    function resetModal() {
        resultsList.innerHTML = '';
        resultCount.textContent = '';
        noResults.style.display = 'none';
        loading.style.display = 'none';
    }

    function getImageUrl(path) {

        if (!path) {
            return defaultImage;
        }

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return '<?= rtrim(base_url(), '/') ?>/'
            + path.replace(/^\/+/, '');
    }

    function createProductElement(product) {

        const item = document.createElement('a');

        item.href = '#';
        item.className = 'aj-search-product';

        const image = document.createElement('img');

        image.className = 'aj-search-product-image';
        image.src = getImageUrl(product.image_path);
        image.alt = product.product_name || 'Product';
        image.onerror = function () {
            this.onerror = null;
            this.src = defaultImage;
        };

        const info = document.createElement('div');
        info.className = 'aj-search-product-info';

        const name = document.createElement('div');
        name.className = 'aj-search-product-name';
        name.textContent = product.product_name || '';

        const brand = document.createElement('div');
        brand.className = 'aj-search-product-brand';
        brand.textContent = product.brand || '';

        const price = document.createElement('div');
        price.className = 'aj-search-product-price';

        const amount = Number(product.listing_price || 0);

        price.textContent = '₹' + amount.toLocaleString('en-IN');

        info.appendChild(name);

        if (product.brand) {
            info.appendChild(brand);
        }

        info.appendChild(price);

        item.appendChild(image);
        item.appendChild(info);

        // Search for the selected product.
        item.addEventListener('click', function (e) {

            e.preventDefault();

            input.value = product.product_name || '';

            closeModal();

            form.submit();
        });

        return item;
    }

    async function fetchSuggestions(query) {

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        resetModal();
        openModal();

        loading.style.display = 'block';

        try {

            const response = await fetch(
                suggestionsUrl + '?q=' + encodeURIComponent(query),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    signal: controller.signal
                }
            );

            if (!response.ok) {
                throw new Error('Search request failed');
            }

            const data = await response.json();

            loading.style.display = 'none';

            const products = Array.isArray(data.products)
                ? data.products
                : [];

            if (products.length === 0) {

                noResults.style.display = 'block';
                resultCount.textContent = '0 results';

                return;
            }

            resultCount.textContent = products.length + ' results';

            const fragment = document.createDocumentFragment();

            products.forEach(function (product) {

                fragment.appendChild(
                    createProductElement(product)
                );

            });

            resultsList.appendChild(fragment);

        } catch (error) {

            if (error.name === 'AbortError') {
                return;
            }

            loading.style.display = 'none';

            noResults.textContent = 'Unable to load products. Please try again.';
            noResults.style.display = 'block';

            console.error('Ajobmart search error:', error);

        }
    }

    // Search while typing.

    input.addEventListener('input', function () {

        const query = this.value.trim();

        clearButton.style.display = query ? 'block' : 'none';

        clearTimeout(searchTimer);

        if (query.length < 2) {

            if (controller) {
                controller.abort();
            }

            closeModal();
            resetModal();

            return;
        }

        searchTimer = setTimeout(function () {

            fetchSuggestions(query);

        }, 300);

    });

    // View all search results.

    viewAll.addEventListener('click', function () {

        if (input.value.trim().length >= 2) {
            closeModal();
            form.submit();
        }

    });

    // Clear search.

    clearButton.addEventListener('click', function () {

        input.value = '';

        closeButtonReset();

        input.focus();
    });

    function closeButtonReset() {

        clearTimeout(searchTimer);

        if (controller) {
            controller.abort();
        }

        clearButton.style.display = 'none';

        resetModal();
        closeModal();

    }

    // Close when clicking outside.

    document.addEventListener('click', function (e) {

        if (!wrapper.contains(e.target)) {
            closeModal();
        }

    });

    // Close with Escape.

    input.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {
            closeModal();
        }

    });

});

</script>