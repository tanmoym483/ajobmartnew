<style>
/* Ajobmart product details — modern marketplace styling */
.aj-product-details-page {
    --aj-market-orange: #ff6a00;
    --aj-market-orange-dark: #e85f00;
    --aj-market-ink: #202a35;
    --aj-market-muted: #626d78;
    --aj-market-border: #e3e8ed;
    --aj-market-surface: #ffffff;
    --aj-market-soft: #f6f8fa;
    max-width: 1440px;
    color: var(--aj-market-ink);
    font-size: 14px;
}

.aj-product-details-page a {
    color: #176b9a;
    text-decoration: none;
}

.aj-product-details-page a:hover {
    color: var(--aj-market-orange-dark);
    text-decoration: underline;
}

.aj-product-details-page .breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 2px;
    margin-bottom: 18px !important;
    padding: 10px 0 14px;
    border-bottom: 1px solid var(--aj-market-border);
    background: transparent;
    font-size: 12px;
}

.aj-product-details-page .breadcrumb-item,
.aj-product-details-page .breadcrumb-item.active {
    color: var(--aj-market-muted);
}

.aj-product-details-page .breadcrumb-item + .breadcrumb-item::before {
    color: #a0a9b2;
}

.aj-details-gallery,
.aj-details-heading,
.aj-details-price-block,
.aj-details-shipping,
.aj-details-section {
    min-width: 0;
    background: var(--aj-market-surface);
}

.aj-details-gallery {
    position: sticky;
    top: 18px;
    width: 100%;
}

.aj-details-main-image {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: clamp(340px, 39vw, 500px);
    min-height: 0;
    padding: 22px;
    overflow: hidden;
    box-sizing: border-box;
    background: linear-gradient(180deg, #fff 0%, #fbfcfd 100%);
    border: 1px solid var(--aj-market-border);
    border-radius: 12px;
}

.aj-details-main-image > img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    cursor: zoom-in;
    transition: transform .25s ease;
}

.aj-details-main-image:hover > img {
    transform: scale(1.015);
}

.aj-details-no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-height: 260px;
    color: #8a949e;
    font-size: 13px;
}

.aj-details-no-image i {
    font-size: 48px;
    color: #b7c0c8;
}

.aj-image-zoom-btn {
    position: absolute;
    right: 14px;
    bottom: 14px;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    padding: 0;
    border: 1px solid #d9e0e6;
    border-radius: 50%;
    background: rgba(255,255,255,.96);
    color: #25313c;
    font-size: 19px;
    box-shadow: 0 3px 12px rgba(25, 38, 50, .08);
    cursor: pointer;
    transition: background .2s ease, color .2s ease, transform .2s ease;
}

.aj-image-zoom-btn:hover {
    color: var(--aj-market-orange-dark);
    background: #fff7f1;
    transform: translateY(-1px);
}

.aj-details-thumbnails {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 13px;
}

.aj-details-thumbnail {
    flex: 0 0 68px;
    width: 68px;
    height: 68px;
    padding: 4px;
    overflow: hidden;
    border: 1px solid #d8dfe5;
    border-radius: 9px;
    background: #fff;
    cursor: pointer;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}

.aj-details-thumbnail img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    pointer-events: none;
}

.aj-details-thumbnail.active,
.aj-details-thumbnail:hover {
    border: 2px solid var(--aj-market-orange);
    padding: 3px;
    background: #fffaf6;
    box-shadow: 0 0 0 2px rgba(255, 106, 0, .08);
}

.aj-details-heading {
    padding: 2px 0 16px;
    border-bottom: 1px solid var(--aj-market-border);
}

.aj-details-brand {
    margin-bottom: 8px;
    color: #176b9a;
    font-size: 13px;
    font-weight: 650;
    letter-spacing: .01em;
}

.aj-details-heading h1 {
    margin: 0 0 12px;
    color: #202a35;
    font-size: clamp(22px, 2.1vw, 30px);
    font-weight: 600;
    line-height: 1.32;
    letter-spacing: -.025em;
    overflow-wrap: anywhere;
}

.aj-details-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 7px 10px;
    color: var(--aj-market-muted);
    font-size: 12px;
}

.aj-details-meta span {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border: 1px solid #e6ebef;
    border-radius: 5px;
    background: #f8fafb;
}

.aj-details-price-block {
    margin-top: 0;
    padding: 20px 0 18px;
    border-bottom: 1px solid var(--aj-market-border);
}

.aj-details-price {
    display: inline-block;
    color: #202a35;
    font-size: clamp(28px, 2.7vw, 36px);
    font-weight: 750;
    line-height: 1.2;
    letter-spacing: -.035em;
    font-variant-numeric: tabular-nums;
}

.aj-details-mrp {
    margin-left: 9px;
    color: #78828c;
    font-size: 15px;
}

.aj-details-discount {
    display: inline-block;
    margin-left: 8px;
    padding: 5px 8px;
    border-radius: 5px;
    background: #fff0e6;
    color: #c84f00;
    font-size: 12px;
    font-weight: 750;
    vertical-align: 4px;
}

.aj-details-tax-note {
    margin-top: 7px;
    color: var(--aj-market-muted);
    font-size: 12px;
    line-height: 1.5;
}

.aj-details-availability {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    padding: 17px 0;
    border-bottom: 1px solid var(--aj-market-border);
}

.aj-details-availability > div {
    display: flex;
    flex: 1 1 105px;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
    padding: 11px 12px;
    border: 1px solid #e8edf1;
    border-radius: 8px;
    background: #fafbfc;
}

.aj-details-label {
    color: #697580;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .035em;
    text-transform: uppercase;
}

.aj-details-availability strong,
.aj-details-availability > div > span:not(.aj-details-label) {
    color: #293642;
    font-size: 13px;
    font-weight: 700;
}

.aj-stock-available {
    color: #168447 !important;
}

.aj-stock-unavailable {
    color: #c62828 !important;
}

.aj-product-details-page .aj-product-cart-action {
    margin-top: 18px;
}

.aj-product-details-page .aj-add-cart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    min-height: 49px;
    max-width: 100%;
    padding: 12px 20px;
    border: 1px solid #f0a33a;
    border-radius: 999px;
    background: linear-gradient(180deg, #ffd978 0%, #ffc342 100%);
    color: #202a35;
    font-size: 15px;
    font-weight: 750;
    box-shadow: 0 2px 0 rgba(148, 89, 0, .09);
    transition: filter .2s ease, transform .2s ease, box-shadow .2s ease;
}

.aj-product-details-page .aj-add-cart:hover:not(:disabled) {
    filter: brightness(.98);
    box-shadow: 0 4px 12px rgba(156, 105, 0, .14);
    transform: translateY(-1px);
}

.aj-product-details-page .aj-add-cart:focus-visible,
.aj-product-details-page .aj-details-thumbnail:focus-visible,
.aj-product-details-page .aj-image-zoom-btn:focus-visible {
    outline: 3px solid rgba(255, 106, 0, .28);
    outline-offset: 2px;
}

.aj-product-details-page .aj-add-cart:disabled {
    border-color: #d8dde2;
    background: #e9edf0;
    color: #79838c;
    cursor: not-allowed;
    box-shadow: none;
}

.aj-details-shipping {
    margin-top: 18px;
    padding: 17px 18px;
    border: 1px solid var(--aj-market-border);
    border-radius: 10px;
    background: #fff;
}

.aj-details-shipping h5 {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0 0 12px;
    color: #25313c;
    font-size: 15px;
    font-weight: 750;
}

.aj-details-shipping h5 i {
    color: #176b9a;
    font-size: 18px;
}

.aj-details-shipping > div {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    padding: 10px 0;
    border-top: 1px solid #edf0f2;
    font-size: 12px;
}

.aj-details-shipping > div span {
    color: #68737e;
}

.aj-details-shipping > div strong {
    color: #303c47;
    text-align: right;
    font-weight: 650;
}

.aj-details-section {
    margin-top: 24px;
    padding: 24px;
    border: 1px solid var(--aj-market-border);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(22, 34, 46, .025);
}

.aj-details-section h2 {
    margin: 0 0 18px;
    padding-bottom: 13px;
    border-bottom: 1px solid #e9edf1;
    color: #25313c;
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -.015em;
}

.aj-details-description {
    color: #45515c;
    font-size: 14px;
    line-height: 1.85;
    overflow-wrap: anywhere;
}

.aj-details-features {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px 20px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.aj-details-features li {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    color: #45515c;
    line-height: 1.6;
}

.aj-details-features i {
    flex: 0 0 auto;
    margin-top: 2px;
    color: #168447;
    font-size: 17px;
}

.aj-details-spec-table {
    margin: 0;
    color: #34414d;
    font-size: 13px;
    --bs-table-bg: transparent;
}

.aj-details-spec-table th,
.aj-details-spec-table td {
    width: 50%;
    padding: 12px 14px;
    border-color: #e8edf1;
    vertical-align: middle;
    overflow-wrap: anywhere;
}

.aj-details-spec-table th {
    background: #f7f9fa;
    color: #53606c;
    font-weight: 650;
}

.aj-details-spec-table tr:nth-child(even) td {
    background: #fcfdfd;
}

.aj-product-details-page > .mt-4 .btn {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 650;
}

/* Full-screen image zoom; functionality retained */
.aj-image-zoom-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: rgba(14, 20, 26, .96);
}

.aj-image-zoom-modal.open {
    display: flex;
}

#ajZoomPreviewImage {
    display: block;
    max-width: 85vw;
    max-height: 82vh;
    object-fit: contain;
    transform-origin: center center;
    cursor: grab;
    user-select: none;
    -webkit-user-drag: none;
    touch-action: none;
}

#ajZoomPreviewImage:active {
    cursor: grabbing;
}

.aj-zoom-close,
.aj-zoom-control {
    position: absolute;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    padding: 0;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50%;
    background: #fff;
    color: #202a35;
    font-size: 19px;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,.18);
}

.aj-zoom-close {
    top: 20px;
    right: 20px;
}

.aj-zoom-plus {
    right: 22px;
    bottom: 70px;
}

.aj-zoom-minus {
    right: 22px;
    bottom: 18px;
}

.aj-zoom-hint {
    position: absolute;
    bottom: 20px;
    left: 20px;
    max-width: 65%;
    color: #fff;
    font-size: 12px;
    opacity: .82;
    pointer-events: none;
}

@media (max-width: 991.98px) {
    .aj-details-gallery {
        position: static;
    }

    .aj-details-main-image {
        height: 390px;
    }
}

@media (max-width: 767.98px) {
    .aj-product-details-page {
        padding-right: 14px;
        padding-left: 14px;
    }

    .aj-product-details-page .breadcrumb {
        margin-bottom: 14px !important;
        padding: 6px 0 12px;
    }

    .aj-details-main-image {
        height: min(86vw, 360px);
        padding: 15px;
        border-radius: 10px;
    }

    .aj-details-thumbnails {
        gap: 8px;
        margin-top: 10px;
    }

    .aj-details-thumbnail {
        flex-basis: 60px;
        width: 60px;
        height: 60px;
    }

    .aj-details-heading h1 {
        font-size: 23px;
    }

    .aj-details-price-block {
        padding: 17px 0;
    }

    .aj-details-price {
        font-size: 30px;
    }

    .aj-details-availability {
        gap: 8px;
    }

    .aj-details-availability > div {
        flex-basis: calc(50% - 8px);
        padding: 10px;
    }

    .aj-details-section {
        margin-top: 18px;
        padding: 18px 15px;
        border-radius: 10px;
    }

    .aj-details-section h2 {
        font-size: 18px;
    }

    .aj-details-features {
        grid-template-columns: minmax(0, 1fr);
        gap: 10px;
    }

    .aj-details-spec-table th,
    .aj-details-spec-table td {
        padding: 10px 9px;
        font-size: 12px;
    }

    #ajZoomPreviewImage {
        max-width: 96vw;
        max-height: 78vh;
    }

    .aj-zoom-close {
        top: 12px;
        right: 12px;
    }

    .aj-zoom-plus,
    .aj-zoom-minus {
        right: 12px;
    }

    .aj-zoom-hint {
        bottom: 14px;
        left: 12px;
        max-width: 72%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .aj-product-details-page *,
    .aj-product-details-page *::before,
    .aj-product-details-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>
<?php
$productId = (int) ($product['id'] ?? 0);
$productName = $product['product_name'] ?? 'Product';

$price = (float) ($product['listing_price'] ?? 0);
$mrp = (float) ($product['mrp'] ?? 0);
$sellingPrice = (float) ($product['selling_price'] ?? 0);

$discount = ($mrp > $price && $mrp > 0)
    ? round((($mrp - $price) / $mrp) * 100)
    : 0;

$formatPrice = static function ($value) {
    return number_format((float) $value, 2);
};

$formatDimension = static function ($value) {
    return ($value !== null && $value !== '')
        ? esc((string) $value)
        : 'Not specified';
};

// Use the existing shared-images route.
$imageUrl = static function ($path) {
    $path = str_replace('\\', '/', trim((string) $path));
    $path = ltrim($path, '/');

    if ($path === '') {
        return '';
    }

    return base_url('shared-images/' . $path);
};

$features = $product['key_features'] ?? '';
if (is_string($features)) {
    $decodedFeatures = json_decode($features, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFeatures)) {
        $features = $decodedFeatures;
    } else {
        $features = preg_split('/[\r\n]+/', $features, -1, PREG_SPLIT_NO_EMPTY);
    }
}

if (!is_array($features)) {
    $features = [];
}

// Include product images returned by the image table.
$productImages = [];

foreach ($images ?? [] as $image) {
    // Change image_path here if your image table uses another column name.
    $path = $image['image_path'] ?? '';

    if ($path !== '') {
        $url = $imageUrl($path);

        if ($url !== '') {
            $productImages[] = $url;
        }
    }
}

$stock = (int) ($product['stock_quantity'] ?? 0);
$moq = max(1, (int) ($product['min_order_quantity'] ?? 1));
?>

<div class="container py-4 aj-product-details-page">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= site_url('/') ?>">Home</a>
            </li>
            <li class="breadcrumb-item">
                <?= esc($product['category'] ?? 'Products') ?>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= esc($productName) ?>
            </li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">

        <!-- Product image gallery -->
        <div class="col-lg-5 col-md-6">
            <div class="aj-details-gallery">

                <!-- Main image -->
                <div class="aj-details-main-image" id="ajImageZoomArea">

                    <?php if (!empty($productImages)): ?>
                        <img
                            id="ajDetailsMainImage"
                            src="<?= esc($productImages[0]) ?>"
                            alt="<?= esc($productName) ?>"
                            onerror="this.style.display='none';">

                        <button type="button"
                                class="aj-image-zoom-btn"
                                id="ajOpenImageZoom"
                                aria-label="Zoom product image">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                    <?php else: ?>
                        <div class="aj-details-no-image">
                            <i class="bi bi-image"></i>
                            <span>No image available</span>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Thumbnails -->
                <?php if (count($productImages) > 1): ?>
                    <div class="aj-details-thumbnails">
                        <?php foreach ($productImages as $index => $url): ?>
                            <button
                                type="button"
                                class="aj-details-thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                data-image="<?= esc($url) ?>"
                                aria-label="View product image <?= $index + 1 ?>">

                                <img
                                    src="<?= esc($url) ?>"
                                    alt="Product image <?= $index + 1 ?>"
                                    onerror="this.parentElement.style.display='none';">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Zoom preview modal -->
        <div class="aj-image-zoom-modal" id="ajImageZoomModal">
            <button type="button"
                    class="aj-zoom-close"
                    id="ajCloseImageZoom"
                    aria-label="Close zoom preview">
                <i class="bi bi-x-lg"></i>
            </button>

            <button type="button"
                    class="aj-zoom-control aj-zoom-minus"
                    id="ajZoomOut"
                    aria-label="Zoom out">
                <i class="bi bi-dash-lg"></i>
            </button>

            <button type="button"
                    class="aj-zoom-control aj-zoom-plus"
                    id="ajZoomIn"
                    aria-label="Zoom in">
                <i class="bi bi-plus-lg"></i>
            </button>

            <img id="ajZoomPreviewImage"
                src=""
                alt="Zoomed product image">

            <div class="aj-zoom-hint">
                Use + / − to zoom. Drag the image to explore.
            </div>
        </div>

        <!-- Main product information -->
        <div class="col-lg-7 col-md-6">

            <div class="aj-details-heading">
                <?php if (!empty($product['brand'])): ?>
                    <div class="aj-details-brand">
                        <?= esc($product['brand']) ?>
                        <?php if (!empty($product['sub_brand'])): ?>
                            · <?= esc($product['sub_brand']) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <h1><?= esc($productName) ?></h1>

                <div class="aj-details-meta">
                    <?php if (!empty($product['sku'])): ?>
                        <span>SKU: <?= esc($product['sku']) ?></span>
                    <?php endif; ?>

                    <?php if (!empty($product['product_condition'])): ?>
                        <span>
                            Condition: <?= esc($product['product_condition']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="aj-details-price-block">
                <div class="aj-details-price">
                    ₹<?= $formatPrice($price) ?>
                </div>

                <?php if ($mrp > $price): ?>
                    <del class="aj-details-mrp">
                        ₹<?= $formatPrice($mrp) ?>
                    </del>

                    <span class="aj-details-discount">
                        <?= (int) $discount ?>% OFF
                    </span>
                <?php endif; ?>

                <div class="aj-details-tax-note">
                    Final price and applicable taxes are confirmed at checkout.
                </div>
            </div>

            <div class="aj-details-availability">
                <div>
                    <span class="aj-details-label">Availability</span>
                    <?php if ($stock > 0): ?>
                        <span class="aj-stock-available">
                            <i class="bi bi-check-circle-fill"></i>
                            In stock
                        </span>
                    <?php else: ?>
                        <span class="aj-stock-unavailable">
                            Out of stock
                        </span>
                    <?php endif; ?>
                </div>

                <div>
                    <span class="aj-details-label">Minimum order</span>
                    <strong><?= $moq ?> unit(s)</strong>
                </div>

                <?php if (!empty($product['pack_of'])): ?>
                    <div>
                        <span class="aj-details-label">Pack size</span>
                        <strong><?= esc($product['pack_of']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Keep the existing Ajobmart cart classes. -->
            <div
                class="aj-product-cart-action"
                data-product-id="<?= $productId ?>">

                <button
                    type="button"
                    class="aj-add-cart"
                    data-product-id="<?= $productId ?>"
                    <?= $stock <= 0 ? 'disabled' : '' ?>>

                    <i class="bi bi-cart-plus"></i>
                    <?= $stock > 0 ? 'Add to Cart' : 'Out of Stock' ?>
                </button>

            </div>

            <div class="aj-details-shipping">
                <h5>
                    <i class="bi bi-truck"></i>
                    Delivery information
                </h5>

                <div>
                    <span>Shipping type</span>
                    <strong>
                        <?= esc($product['shipping_type'] ?: 'Standard') ?>
                    </strong>
                </div>

                <div>
                    <span>Dispatch time</span>
                    <strong>
                        <?= !empty($product['shipping_days'])
                            ? esc($product['shipping_days']) . ' day(s)'
                            : 'Not specified' ?>
                    </strong>
                </div>

                <div>
                    <span>Shipping allowance</span>
                    <strong>
                        ₹<?= $formatPrice($product['shipping_allowance'] ?? 0) ?>
                    </strong>
                </div>
            </div>

        </div>
    </div>

    <!-- Product description -->
    <section class="aj-details-section mt-5">
        <h2>Product Description</h2>

        <?php if (!empty($product['description'])): ?>
            <div class="aj-details-description">
                <?= nl2br(esc($product['description'])) ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No description available.</p>
        <?php endif; ?>
    </section>

    <!-- Key features -->
    <?php if (!empty($features)): ?>
        <section class="aj-details-section">
            <h2>Key Features</h2>

            <ul class="aj-details-features">
                <?php foreach ($features as $feature): ?>
                    <?php
                    if (is_array($feature)) {
                        $feature = $feature['value']
                            ?? $feature['label']
                            ?? implode(': ', array_map('strval', $feature));
                    }

                    if (trim((string) $feature) === '') {
                        continue;
                    }
                    ?>

                    <li>
                        <i class="bi bi-check2-circle"></i>
                        <?= esc((string) $feature) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- Technical / product specifications -->
    <section class="aj-details-section">
        <h2>Product Specifications</h2>

        <div class="table-responsive">
            <table class="table aj-details-spec-table">
                <tbody>
                    <tr>
                        <th>Category</th>
                        <td><?= esc($product['category'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Brand</th>
                        <td><?= esc($product['brand'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Sub-brand</th>
                        <td><?= esc($product['sub_brand'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <td><?= esc($product['sku'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Condition</th>
                        <td><?= esc($product['product_condition'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Pack of</th>
                        <td><?= esc($product['pack_of'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Minimum order quantity</th>
                        <td><?= $moq ?></td>
                    </tr>
                    <tr>
                        <th>Manufacturer</th>
                        <td><?= esc($product['manufacturer_name'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Packer</th>
                        <td><?= esc($product['packer_name'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Country of origin</th>
                        <td><?= esc($product['country_of_origin'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>HSN code</th>
                        <td><?= esc($product['hsn_code'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Model number</th>
                        <td><?= esc($product['model_number_general'] ?: 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Package length</th>
                        <td><?= $formatDimension($product['package_length'] ?? null) ?></td>
                    </tr>
                    <tr>
                        <th>Package width</th>
                        <td><?= $formatDimension($product['package_width'] ?? null) ?></td>
                    </tr>
                    <tr>
                        <th>Package height</th>
                        <td><?= $formatDimension($product['package_height'] ?? null) ?></td>
                    </tr>
                    <tr>
                        <th>Shipping weight</th>
                        <td>
                            <?= $formatDimension($product['shipping_weight'] ?? null) ?>
                            <?php if (($product['shipping_weight'] ?? '') !== ''): ?>
                                kg
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Stock</th>
                        <td><?= $stock > 0 ? $stock . ' available' : 'Out of stock' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Dynamic category attributes -->
    <?php if (!empty($attributes)): ?>
        <section class="aj-details-section">
            <h2>Additional Information</h2>

            <div class="table-responsive">
                <table class="table aj-details-spec-table">
                    <tbody>
                        <?php foreach ($attributes as $attribute): ?>
                            <tr>
                                <th>
                                    <?= esc(
                                        $attribute['label']
                                        ?? $attribute['attribute_name']
                                        ?? ('Attribute ' . $attribute['attribute_id'])
                                    ) ?>
                                </th>
                                <td><?= esc($attribute['attribute_value'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?= site_url('/') ?>"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Continue Shopping
        </a>
    </div>

</div>

<script>
(function () {
    'use strict';

    // Ensure gallery handlers are initialized only once.
    if (window.ajProductGalleryInitialized) return;
    window.ajProductGalleryInitialized = true;

    const mainImage = document.getElementById('ajDetailsMainImage');
    const modal = document.getElementById('ajImageZoomModal');
    const preview = document.getElementById('ajZoomPreviewImage');

    if (!mainImage) {
        console.warn('Ajobmart gallery: #ajDetailsMainImage was not found.');
        return;
    }

    let zoom = 1;
    let translateX = 0;
    let translateY = 0;
    let dragging = false;
    let startX = 0;
    let startY = 0;
    let startTranslateX = 0;
    let startTranslateY = 0;

    function updatePreviewTransform() {
        if (!preview) return;
        preview.style.transform = `translate(${translateX}px, ${translateY}px) scale(${zoom})`;
    }

    function resetZoom() {
        zoom = 1;
        translateX = 0;
        translateY = 0;
        updatePreviewTransform();
    }

    function syncZoomPreview() {
        if (modal && preview && modal.classList.contains('open')) {
            preview.src = mainImage.currentSrc || mainImage.src;
            resetZoom();
        }
    }

    function selectThumbnail(button) {
        const imageUrl = button.getAttribute('data-image');
        if (!imageUrl) {
            console.error('Ajobmart gallery: thumbnail is missing data-image.', button);
            return;
        }

        // Preload the image before replacing the currently visible main image.
        const candidate = new Image();

        candidate.onload = function () {
            mainImage.src = candidate.src;
            mainImage.style.display = 'block';

            document.querySelectorAll('.aj-details-thumbnail').forEach(function (item) {
                item.classList.toggle('active', item === button);
            });

            syncZoomPreview();
            console.info('Ajobmart gallery: primary image changed:', candidate.src);
        };

        candidate.onerror = function () {
            console.error('Ajobmart gallery: could not load thumbnail image:', imageUrl);
        };

        candidate.src = imageUrl;
    }

    // Use a native capture-phase handler so other delegated jQuery handlers
    // do not prevent the gallery thumbnail click from being handled.
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.aj-details-thumbnail');
        if (!button) return;

        event.preventDefault();
        selectThumbnail(button);
    }, true);

    function openZoom() {
        if (!modal || !preview || !mainImage.src || mainImage.style.display === 'none') return;

        preview.src = mainImage.currentSrc || mainImage.src;
        resetZoom();
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeZoom() {
        if (!modal) return;
        modal.classList.remove('open');
        document.body.style.overflow = '';
        dragging = false;
    }

    function changeZoom(amount) {
        zoom = Math.max(1, Math.min(5, zoom + amount));
        if (zoom === 1) {
            translateX = 0;
            translateY = 0;
        }
        updatePreviewTransform();
    }

    mainImage.addEventListener('click', openZoom);

    const openButton = document.getElementById('ajOpenImageZoom');
    const closeButton = document.getElementById('ajCloseImageZoom');
    const zoomInButton = document.getElementById('ajZoomIn');
    const zoomOutButton = document.getElementById('ajZoomOut');

    if (openButton) openButton.addEventListener('click', openZoom);
    if (closeButton) closeButton.addEventListener('click', closeZoom);
    if (zoomInButton) zoomInButton.addEventListener('click', function () { changeZoom(0.5); });
    if (zoomOutButton) zoomOutButton.addEventListener('click', function () { changeZoom(-0.5); });

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeZoom();
        });

        modal.addEventListener('wheel', function (event) {
            if (!modal.classList.contains('open')) return;
            event.preventDefault();
            changeZoom(event.deltaY < 0 ? 0.25 : -0.25);
        }, { passive: false });
    }

    if (preview) {
        preview.addEventListener('pointerdown', function (event) {
            if (zoom <= 1) return;
            dragging = true;
            startX = event.clientX;
            startY = event.clientY;
            startTranslateX = translateX;
            startTranslateY = translateY;
            if (preview.setPointerCapture) preview.setPointerCapture(event.pointerId);
            event.preventDefault();
        });

        preview.addEventListener('pointermove', function (event) {
            if (!dragging) return;
            translateX = startTranslateX + event.clientX - startX;
            translateY = startTranslateY + event.clientY - startY;
            updatePreviewTransform();
        });

        ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(function (type) {
            preview.addEventListener(type, function () { dragging = false; });
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeZoom();
    });
})();
</script>
