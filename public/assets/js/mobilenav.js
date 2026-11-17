
// $(function () {

//     const searchWrapper = $('#ajSearchWrapper');
//     const searchInput = $('#ajSearchInput');
//     const searchBack = $('#ajSearchBack');

//     // Detect homepage
//     const isHome =
//         window.location.pathname === '/' ||
//         window.location.pathname === '/ajobmartnew/' ||
//         window.location.pathname.endsWith('/index.php');

//     if (isHome) {
//         searchWrapper.addClass('aj-search-home');
//     }

//     // Back button
//     searchBack.on('click', function () {

//         searchWrapper.removeClass('aj-search-active');

//         searchInput.val('');

//         // Hide search suggestions if available
//         $('#ajSearchSuggestions').hide();

//         searchInput.blur();

//         // Go back only when not on homepage
//         if (!searchWrapper.hasClass('aj-search-home')) {
//             window.history.back();
//         }

//     });

//     // Activate back button on non-home pages
//     if (!isHome) {
//         searchWrapper.addClass('aj-search-active');
//     }

// });




// /* =========================================
//    AJOBMART CUSTOMER LIVE SEARCH
// ========================================= */


// /* =========================================
//    AJOBMART CUSTOMER LIVE SEARCH
//    SINGLE INITIALIZATION
// ========================================= */

// (function () {
//     'use strict';

//     const input = document.getElementById('ajSearchInput');
//     const form = document.getElementById('ajSearchForm');
//     const suggestions = document.getElementById('ajSearchSuggestions');
//     const wrapper = document.getElementById('ajSearchWrapper');

//     if (!input || !form || !suggestions || !wrapper) return;

//     let searchTimer = null;
//     let requestNumber = 0;

//     const searchUrl = site_url + 'products/search';
//     const suggestionUrl = site_url + 'products/search-suggestions';


// // Move suggestions outside header and page containers
// if (suggestions.parentElement !== document.body) {
//     document.body.appendChild(suggestions);
// }

// // Position modal directly below search field
// function positionSuggestions() {

//     const rect = input.getBoundingClientRect();

//     const width = Math.min(
//         rect.width,
//         document.documentElement.clientWidth - 16
//     );

//     const left = rect.left + (rect.width - width) / 2;

//     const viewportHeight = document.documentElement.clientHeight;

//     const below = viewportHeight - rect.bottom - 10;
//     const above = rect.top - 10;

//     const showAbove = below < 180 && above > below;

//     const available = showAbove ? above : below;
//     const height = Math.max(80, Math.min(360, available));

//     suggestions.style.position = 'fixed';
//     suggestions.style.width = width + 'px';
//     suggestions.style.left = Math.max(8, left) + 'px';

//     suggestions.style.top = showAbove
//         ? Math.max(8, rect.top - height - 6) + 'px'
//         : (rect.bottom + 6) + 'px';

//     suggestions.style.maxHeight = height + 'px';
// }

// window.addEventListener('scroll', positionSuggestions, true);
// window.addEventListener('resize', positionSuggestions);


//     function hideSuggestions() {
//         suggestions.style.display = 'none';
//         suggestions.innerHTML = '';
//         wrapper.classList.remove('aj-search-suggestions-open');
//     }

//     function showSuggestions() {

//     suggestions.style.display = 'block';

//     wrapper.classList.add('aj-search-suggestions-open');

//     requestAnimationFrame(function () {
//         positionSuggestions();
//     });
// }

//     function makeImageUrl(path) {
//         if (!path) return '';

//         return site_url + 'shared-images/' +
//             String(path).replace(/^[/\\]+/, '');
//     }

//     function renderSuggestions(products) {

//         if (!products.length) {
//             suggestions.innerHTML = `
//                 <div class="aj-search-no-suggestion">
//                     No matching products found.
//                 </div>
//             `;

//             showSuggestions();
//             return;
//         }

//         let html = '';

//         products.forEach(function (product) {

//             const name = escapeHomeHtml(
//                 product.product_name || 'Product'
//             );

//             const brand = escapeHomeHtml(
//                 product.brand || product.category || ''
//             );

//             const price = Number(product.listing_price || 0);

//             const image = makeImageUrl(product.image_path);

//             const productUrl = site_url +
//                 'product-details/' + Number(product.id);

//             html += `
//                 <a href="${productUrl}"
//                    class="aj-search-suggestion">

//                     ${
//                         image
//                         ? `
//                             <img
//                                 src="${escapeHomeHtml(image)}"
//                                 alt="${name}"
//                                 onerror="this.style.display='none'"
//                             >
//                         `
//                         : `
//                             <div class="aj-product-image-placeholder">
//                                 <i class="bi bi-box-seam"></i>
//                             </div>
//                         `
//                     }

//                     <div class="aj-search-suggestion-info">
//                         <strong>${name}</strong>
//                         <small>${brand}</small>
//                     </div>

//                     <span class="aj-search-suggestion-price">
//                         ₹${price.toLocaleString('en-IN')}
//                     </span>

//                 </a>
//             `;
//         });

//         suggestions.innerHTML = html;
//         showSuggestions();
//     }

//     input.addEventListener('input', function () {

//         const query = input.value.trim();

//         clearTimeout(searchTimer);

//         // Invalidate previous pending responses
//         requestNumber++;

//         if (query.length < 2) {
//             hideSuggestions();
//             return;
//         }

//         searchTimer = setTimeout(function () {

//             const currentRequest = requestNumber;

//             $.ajax({
//                 url: suggestionUrl,
//                 method: 'GET',
//                 data: { q: query },
//                 dataType: 'json',
//                 cache: false,

//                 success: function (response) {

//                     if (currentRequest !== requestNumber) return;

//                     if (response.status) {
//                         renderSuggestions(response.products || []);
//                     } else {
//                         hideSuggestions();
//                     }
//                 },

//                 error: function (xhr) {

//                     if (currentRequest !== requestNumber) return;

//                     console.error(
//                         'Product suggestions error:',
//                         xhr.responseText
//                     );

//                     hideSuggestions();
//                 }
//             });

//         }, 300);
//     });

//     form.addEventListener('submit', function (event) {

//         event.preventDefault();

//         const query = input.value.trim();

//         if (query.length < 2) {
//             input.focus();
//             return;
//         }

//         window.location.href =
//             searchUrl + '?search=' + encodeURIComponent(query);
//     });

//     document.addEventListener('click', function (event) {

//         if (
//             !wrapper.contains(event.target) &&
//             !suggestions.contains(event.target)
//         ) {
//             hideSuggestions();
//         }
//     });

//     input.addEventListener('keydown', function (event) {

//         if (event.key === 'Escape') {
//             hideSuggestions();
//         }
//     });

// })();



// /* =========================================
//    AJOBMART SEARCH RESULTS
// ========================================= */

// (function () {
//     'use strict';

//     const container = document.getElementById('ajSearchResultsGrid');

//     if (!container) return;

//     const query = window.ajCustomerSearchQuery || '';
//     const countElement = document.getElementById('ajSearchResultCount');
//     const loadMore = document.getElementById('ajSearchLoadMore');
//     const noMore = document.getElementById('ajSearchNoMore');

//     let currentPage = 1;
//     let isLoading = false;
//     let hasMore = true;

//     function loadSearchResults(append = false) {
//         if (isLoading || (!hasMore && append)) return;

//         isLoading = true;

//         if (!append) {
//             container.innerHTML = `
//                 <div class="text-center w-100 py-4">
//                     <span class="spinner-border spinner-border-sm"></span>
//                     Searching products...
//                 </div>
//             `;
//         }

//         $.ajax({
//             url: site_url + 'products/search-data',
//             method: 'GET',
//             data: {
//                 search: query,
//                 page: currentPage
//             },
//             dataType: 'json',
//             cache: false,

//             success: function (response) {
//                 if (!response.status) {
//                     container.innerHTML = `
//                         <div class="text-center w-100 py-4">
//                             Unable to load products.
//                         </div>
//                     `;
//                     return;
//                 }

//                 const products = response.products || [];

//                 if (countElement) {
//                     countElement.textContent =
//                         response.total + ' products found for "' + query + '"';
//                 }

//                 if (!append) {
//                     container.innerHTML = '';
//                 }

//                 if (!products.length && !append) {
//                     container.innerHTML = `
//                         <div class="text-center w-100 py-5">
//                             <i class="bi bi-search fs-2 text-muted"></i>
//                             <h5 class="mt-3">No products found</h5>
//                             <p class="text-muted">
//                                 Try another product name, brand or keyword.
//                             </p>
//                         </div>
//                     `;
//                     loadMore.style.display = 'none';
//                     return;
//                 }

//                 if (typeof window.ajCartRegisterProducts === 'function') {
//                     window.ajCartRegisterProducts(products);
//                 }

//                 let html = '';

//                 products.forEach(function (product) {
//                     html += renderHomeProductCard(product, 'search');
//                 });

//                 container.insertAdjacentHTML('beforeend', html);

//                 if (typeof window.ajCartRefresh === 'function') {
//                     window.ajCartRefresh();
//                 }

//                 hasMore = response.has_more === true;

//                 if (hasMore) {
//                     loadMore.style.display = 'block';
//                     noMore.style.display = 'none';
//                 } else {
//                     loadMore.style.display = 'none';
//                     noMore.style.display = 'block';
//                 }

//                 currentPage++;
//             },

//             error: function (xhr) {
//                 console.error('Search results error:', xhr.responseText);

//                 if (!append) {
//                     container.innerHTML = `
//                         <div class="text-center w-100 py-4">
//                             Could not load products. Please try again.
//                         </div>
//                     `;
//                 }
//             },

//             complete: function () {
//                 isLoading = false;
//             }
//         });
//     }

//     loadMore?.querySelector('button')?.addEventListener('click', function () {
//         loadSearchResults(true);
//     });

//     loadSearchResults();

// })();

