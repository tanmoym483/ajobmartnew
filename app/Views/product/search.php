
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="aj-container aj-search-results-page">

    <div class="aj-search-results-heading">
        <div>
            <span class="aj-v3-label">AJOBMART SEARCH</span>
            <h2>Search Products</h2>
            <p id="ajSearchResultCount">
                Searching for "<?= esc($searchQuery) ?>"
            </p>
        </div>
    </div>

    <div id="ajSearchResultsGrid"
         class="aj-v3-product-grid">

        <div class="text-center w-100 py-4">
            <span class="spinner-border spinner-border-sm"></span>
            Searching products...
        </div>

    </div>

    <div id="ajSearchLoadMore"
         class="text-center py-4"
         style="display:none;">

        <button type="button"
                class="btn btn-outline-secondary btn-sm">
            Load More Products
        </button>

    </div>

    <div id="ajSearchNoMore"
         class="text-center text-muted py-4"
         style="display:none;">
        No more products available.
    </div>

</div>

<script>
window.ajCustomerSearchQuery = <?= json_encode(
    $searchQuery,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
) ?>;
</script>

<?= $this->endSection() ?>
