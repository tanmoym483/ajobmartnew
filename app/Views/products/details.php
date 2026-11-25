
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

<?php
// Get the first available image path for cart registration.
$cartImagePath = '';

foreach ($images ?? [] as $cartImage) {
    $candidatePath = trim((string) ($cartImage['image_path'] ?? ''));

    if ($candidatePath !== '') {
        $cartImagePath = str_replace('\\', '/', $candidatePath);
        break;
    }
}
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
                    <!-- <?php if (!empty($product['sku'])): ?>
                        <span>SKU: <?= esc($product['sku']) ?></span>
                    <?php endif; ?> -->

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

            <!-- WOW Online Payment Offer -->
            <?php
            $onlineDiscountPercent = 20;
            $onlineDiscountAmount = floor($price * $onlineDiscountPercent / 100);
            $onlineOfferPrice = max(0, floor($price - $onlineDiscountAmount));

            $wowOfferApplied = (bool) ($wowOfferApplied ?? false);

            $formatPrice = static function ($value) {
                return number_format((float) $value, 0);
            };
            ?>

            <div class="aj-wow-offer"
                id="ajWowOffer"
                data-product-id="<?= (int) $productId ?>"
                data-original-price="<?= esc($price) ?>"
                data-offer-price="<?= esc($onlineOfferPrice) ?>"
                data-applied="<?= $wowOfferApplied ? '1' : '0' ?>">

                <div class="aj-wow-offer-header"
                    id="ajWowOfferToggle"
                    role="button"
                    tabindex="0"
                    aria-expanded="false"
                    aria-controls="ajWowOfferContent">

                    <div class="aj-wow-offer-heading">
                        <span class="aj-wow-icon">
                            <i class="bi bi-stars"></i>
                        </span>

                        <div>
                            <strong>WOW OFFER!</strong>
                            <small>20% OFF on online payment</small>
                        </div>
                    </div>

                    <div class="aj-wow-header-right">
                        <div class="aj-wow-header-price">
                            <strong id="ajWowOfferPrice">
                                ₹<?= $formatPrice($onlineOfferPrice) ?>
                            </strong>
                            <del>₹<?= $formatPrice($price) ?></del>
                        </div>

                        <i class="bi bi-chevron-down aj-wow-arrow"></i>
                    </div>
                </div>

                <div class="aj-wow-offer-content"
                    id="ajWowOfferContent"
                    hidden>

                    <div class="aj-wow-offer-details">
                        <div class="aj-wow-discount">
                            <strong>20% OFF</strong>
                            <span>Online Payment</span>
                        </div>

                        <p>
                            Get an additional 20% discount when you pay online
                            using an eligible payment method.
                        </p>
                    </div>

                    <button type="button"
                            class="aj-wow-apply-btn"
                            id="ajWowApplyBtn"
                            <?= $wowOfferApplied ? 'hidden' : '' ?>>
                        Apply Offer
                    </button>

                    <button type="button"
                            class="aj-wow-apply-btn"
                            id="ajWowRemoveBtn"
                            <?= $wowOfferApplied ? '' : 'hidden' ?>>
                        Remove Offer
                    </button>

                    <div class="aj-wow-applied-message"
                        id="ajWowAppliedMessage"
                        <?= $wowOfferApplied ? '' : 'hidden' ?>>
                        <i class="bi bi-check-circle-fill"></i>
                        Offer selected! Choose online payment at checkout.
                    </div>
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
            <div class="aj-product-cart-action"
                id="ajProductCartAction"
                data-product-id="<?= $productId ?>">

                <div class="aj-product-action-buttons mb-3">

                    <!-- Add to Cart -->
                    <button type="button"
                            class="aj-add-cart"
                            id="ajProductAddCart"
                            data-product-id="<?= $productId ?>"
                            <?= $stock <= 0 ? 'disabled' : '' ?>>

                        <i class="bi bi-cart-plus"></i>
                        <span>Add to Cart</span>

                        <strong id="ajAddCartAmount">
                            ₹<?= $formatPrice($price) ?>
                        </strong>
                    </button>

                    <!-- Go to Cart: hidden initially -->
                    <a href="<?= site_url('cart') ?>"
                    class="aj-go-to-cart"
                    id="ajProductGoToCart"
                    hidden>

                        <i class="bi bi-cart-check"></i>
                        Go to Cart
                    </a>

                    <!-- Buy Now -->
                    <button type="button"
                            class="aj-buy-now"
                            id="ajProductBuyNow"
                            <?= $stock <= 0 ? 'disabled' : '' ?>>

                        <i class="bi bi-lightning-fill"></i>
                        <span id = "ajBuyAmount">Buy At ₹<?= $formatPrice($price) ?></span>
                    </button>

                </div>
            </div>

            
<!-- Product details accordion -->
<div class="aj-details-accordion" id="ajProductDetailsAccordion">

    <!-- 1. Delivery Information -->
    <div class="aj-details-accordion-item">
        <button
            type="button"
            class="aj-details-accordion-header"
            id="ajDeliveryToggle"
            aria-expanded="true"
            aria-controls="ajDeliveryPanel">

            <span class="aj-details-accordion-title">
                <i class="bi bi-truck"></i>
                Delivery Information
            </span>

            <i class="bi bi-chevron-down aj-details-accordion-arrow"></i>
        </button>

        <div
            class="aj-details-accordion-panel"
            id="ajDeliveryPanel"
            role="region"
            aria-labelledby="ajDeliveryToggle">

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

    <!-- 2. Product Description -->
    <div class="aj-details-accordion-item">
        <button
            type="button"
            class="aj-details-accordion-header"
            id="ajDescriptionToggle"
            aria-expanded="false"
            aria-controls="ajDescriptionPanel">

            <span class="aj-details-accordion-title">
                <i class="bi bi-card-text"></i>
                Product Description
            </span>

            <i class="bi bi-chevron-down aj-details-accordion-arrow"></i>
        </button>

        <div
            class="aj-details-accordion-panel"
            id="ajDescriptionPanel"
            role="region"
            aria-labelledby="ajDescriptionToggle"
            hidden>

            <div class="aj-details-section">
                <?php if (!empty($product['description'])): ?>
                    <div class="aj-details-description">
                        <?= nl2br(esc($product['description'])) ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        No description available.
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($features)): ?>
                <div class="aj-details-section">
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
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3. Product Specifications -->
    <div class="aj-details-accordion-item">
        <button
            type="button"
            class="aj-details-accordion-header"
            id="ajSpecificationsToggle"
            aria-expanded="false"
            aria-controls="ajSpecificationsPanel">

            <span class="aj-details-accordion-title">
                <i class="bi bi-list-check"></i>
                Product Specifications
            </span>

            <i class="bi bi-chevron-down aj-details-accordion-arrow"></i>
        </button>

        <div
            class="aj-details-accordion-panel"
            id="ajSpecificationsPanel"
            role="region"
            aria-labelledby="ajSpecificationsToggle"
            hidden>

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
                            <td>
                                <?= $stock > 0
                                    ? $stock . ' available'
                                    : 'Out of stock' ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
<!-- 4. Customer Reviews -->
<div class="aj-details-accordion-item">

    <button
        type="button"
        class="aj-details-accordion-header"
        id="ajReviewsToggle"
        aria-expanded="false"
        aria-controls="ajReviewsPanel">

        <span class="aj-details-accordion-title">
            <i class="bi bi-star-half"></i>
            Customer Reviews & Ratings
        </span>

        <i class="bi bi-chevron-down aj-details-accordion-arrow"></i>
    </button>

    <div
        class="aj-details-accordion-panel"
        id="ajReviewsPanel"
        role="region"
        aria-labelledby="ajReviewsToggle"
        hidden>

        <div class="aj-product-reviews">

            <div class="aj-review-demo-notice">
                <i class="bi bi-info-circle"></i>
                Sample reviews for layout demonstration only. These are not verified customer reviews.
            </div>

            <!-- Rating Summary -->
            <div class="aj-review-summary">

                <div class="aj-review-average">
                    <strong>4.5</strong>

                    <div class="aj-review-stars">
                        ★★★★★
                    </div>

                    <small>Sample rating</small>
                </div>

                <div class="aj-review-breakdown">

                    <div class="aj-review-rating-row">
                        <span>5 ★</span>
                        <div class="aj-review-progress">
                            <span style="width:75%"></span>
                        </div>
                        <span>75%</span>
                    </div>

                    <div class="aj-review-rating-row">
                        <span>4 ★</span>
                        <div class="aj-review-progress">
                            <span style="width:15%"></span>
                        </div>
                        <span>15%</span>
                    </div>

                    <div class="aj-review-rating-row">
                        <span>3 ★</span>
                        <div class="aj-review-progress">
                            <span style="width:7%"></span>
                        </div>
                        <span>7%</span>
                    </div>

                    <div class="aj-review-rating-row">
                        <span>2 ★</span>
                        <div class="aj-review-progress">
                            <span style="width:2%"></span>
                        </div>
                        <span>2%</span>
                    </div>

                    <div class="aj-review-rating-row">
                        <span>1 ★</span>
                        <div class="aj-review-progress">
                            <span style="width:1%"></span>
                        </div>
                        <span>1%</span>
                    </div>

                </div>
            </div>

            <!-- Sample Reviews -->
            <div class="mt-3">

                <div class="aj-review-card">

                    <div class="aj-review-user">
                        <div class="aj-review-avatar">R</div>

                        <div class="aj-review-user-info">
                            <strong>Rahul S.</strong>
                            <small>Sample reviewer · Demo content</small>
                        </div>
                    </div>

                    <div class="aj-review-stars">★★★★★</div>

                    <h6>Good product quality</h6>

                    <p>
                        The product looks good and the overall quality
                        seems impressive. Nice packaging and good value
                        for money.
                    </p>

                </div>

                <div class="aj-review-card">

                    <div class="aj-review-user">
                        <div class="aj-review-avatar">A</div>

                        <div class="aj-review-user-info">
                            <strong>Arpita M.</strong>
                            <small>Sample reviewer · Demo content</small>
                        </div>
                    </div>

                    <div class="aj-review-stars">★★★★☆</div>

                    <h6>Worth considering</h6>

                    <p>
                        The product has a nice appearance and seems
                        suitable for everyday use. Overall a good
                        sample review.
                    </p>

                </div>

                <div class="aj-review-card">

                    <div class="aj-review-user">
                        <div class="aj-review-avatar">S</div>

                        <div class="aj-review-user-info">
                            <strong>Soumik D.</strong>
                            <small>Sample reviewer · Demo content</small>
                        </div>
                    </div>

                    <div class="aj-review-stars">★★★★★</div>

                    <h6>Nice experience</h6>

                    <p>
                        Good-looking product with an attractive price.
                        Would be worth considering for a future purchase.
                    </p>

                </div>

            </div>

        </div>
    </div>
</div>


    <!-- 4. Additional Information -->
    <?php if (!empty($attributes)): ?>
        <div class="aj-details-accordion-item">
            <button
                type="button"
                class="aj-details-accordion-header"
                id="ajAdditionalToggle"
                aria-expanded="false"
                aria-controls="ajAdditionalPanel">

                <span class="aj-details-accordion-title">
                    <i class="bi bi-info-circle"></i>
                    Additional Information
                </span>

                <i class="bi bi-chevron-down aj-details-accordion-arrow"></i>
            </button>

            <div
                class="aj-details-accordion-panel"
                id="ajAdditionalPanel"
                role="region"
                aria-labelledby="ajAdditionalToggle"
                hidden>

                <div class="table-responsive">
                    <table class="table aj-details-spec-table">
                        <tbody>
                            <?php foreach ($attributes as $attribute): ?>
                                <tr>
                                    <th>
                                        <?= esc(
                                            $attribute['label']
                                            ?? $attribute['attribute_name']
                                            ?? ('Attribute ' . ($attribute['attribute_id'] ?? ''))
                                        ) ?>
                                    </th>
                                    <td>
                                        <?= esc($attribute['attribute_value'] ?? '') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>


        </div>
    </div>

    

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

        // Disable floating Add to Cart while image zoom is open.
        document.body.classList.add('aj-image-zoom-active');

        document.dispatchEvent(new CustomEvent('aj:image-zoom-state', {
            detail: { open: true }
        }));
    }

    function closeZoom() {
        if (!modal) return;

        modal.classList.remove('open');
        document.body.style.overflow = '';
        dragging = false;

        // Restore normal floating-cart behaviour.
        document.body.classList.remove('aj-image-zoom-active');

        document.dispatchEvent(new CustomEvent('aj:image-zoom-state', {
            detail: { open: false }
        }));
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



(function () {
    'use strict';

    const accordion = document.getElementById('ajProductDetailsAccordion');

    if (!accordion || accordion.dataset.initialized === 'true') {
        return;
    }

    accordion.dataset.initialized = 'true';

    accordion.addEventListener('click', function (event) {
        const button = event.target.closest('.aj-details-accordion-header');

        if (!button || !accordion.contains(button)) {
            return;
        }

        const panelId = button.getAttribute('aria-controls');
        const panel = document.getElementById(panelId);

        if (!panel) {
            return;
        }

        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        // Single-open accordion: close all other panels.
        accordion.querySelectorAll('.aj-details-accordion-header').forEach(function (item) {
            item.setAttribute('aria-expanded', 'false');

            const controlledPanel = document.getElementById(
                item.getAttribute('aria-controls')
            );

            if (controlledPanel) {
                controlledPanel.hidden = true;
            }
        });

        // Toggle the selected panel.
        if (!isExpanded) {
            button.setAttribute('aria-expanded', 'true');
            panel.hidden = false;
        }
    });
})();

(function () {
    'use strict';

    const offer = document.getElementById('ajWowOffer');
    const toggle = document.getElementById('ajWowOfferToggle');
    const content = document.getElementById('ajWowOfferContent');

    const applyButton = document.getElementById('ajWowApplyBtn');
    const removeButton = document.getElementById('ajWowRemoveBtn');
    const appliedMessage = document.getElementById('ajWowAppliedMessage');

    if (!offer || !toggle || !content || !applyButton || !removeButton) {
        return;
    }

    const productId = Number(offer.dataset.productId);
    const originalPrice = Number(offer.dataset.originalPrice);
    const offerPrice = Number(offer.dataset.offerPrice);
    const wowsave = originalPrice - offerPrice;

    function updateAddCartAmount(amount) {
        if (typeof window.ajUpdateProductAddCartAmount === 'function') {
            window.ajUpdateProductAddCartAmount(amount);
        }
    }

    function setOfferApplied(applied) {
        offer.dataset.applied = applied ? '1' : '0';
        offer.dataset.offerApplied = applied ? 'true' : 'false';

        applyButton.hidden = applied;
        removeButton.hidden = !applied;
        appliedMessage.hidden = !applied;

        updateAddCartAmount(
            applied ? offerPrice : originalPrice
        );

        // Notify the cart integration that this product's offer changed.
        document.dispatchEvent(new CustomEvent('aj:wow-offer-change', {
            detail: {
                product_id: productId,
                applied: applied,
                original_price: originalPrice,
                offer_price: offerPrice
            }
        }));
    }

    function toggleOffer() {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';

        toggle.setAttribute('aria-expanded', String(!isOpen));
        content.hidden = isOpen;
        offer.classList.toggle('is-open', !isOpen);
    }

    toggle.addEventListener('click', toggleOffer);

    toggle.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleOffer();
        }
    });

    applyButton.addEventListener('click', function () {

        if (!Number.isFinite(offerPrice) || offerPrice < 0) {
            console.error('Invalid Wow Offer price');
            return;
        }

        // Apply WOW offer
        setOfferApplied(true);

        // Calculate discount amount
        const discountAmount = Number(wowsave) || 0;

        // Show WOW celebration
        window.showWowCelebration(discountAmount);

    });

    removeButton.addEventListener('click', function () {
        setOfferApplied(false);
    });

    // Restore the state supplied by the server when the product page loads.
    setOfferApplied(offer.dataset.applied === '1');

})();





(function () {
    'use strict';

    function initFloatingCart() {

        const cartAction = document.getElementById('ajProductCartAction');

        if (!cartAction) {
            console.error('Ajobmart: #ajProductCartAction not found');
            return;
        }

        const mobileQuery = window.matchMedia('(max-width: 767.98px)');

        // Create a placeholder to preserve the original layout.
        const placeholder = document.createElement('div');

        placeholder.style.width = '100%';
        placeholder.style.height = cartAction.offsetHeight + 'px';
        placeholder.style.visibility = 'hidden';
        placeholder.style.pointerEvents = 'none';

        cartAction.parentNode.insertBefore(placeholder, cartAction);

        let observer;

        let cartPlaceholderVisible = true;

        function updateFloatingState(isVisible) {

            cartPlaceholderVisible = isVisible;

            const zoomOpen = document.body.classList.contains(
                'aj-image-zoom-active'
            );

            const shouldFloat =
                mobileQuery.matches &&
                !isVisible &&
                !zoomOpen;

            cartAction.classList.toggle(
                'aj-mobile-floating',
                shouldFloat
            );
        }

        // Listen for product image zoom open/close.
        document.addEventListener('aj:image-zoom-state', function () {

            updateFloatingState(cartPlaceholderVisible);

        });

        function startObserver() {

            if (observer) {
                observer.disconnect();
            }

            if (!mobileQuery.matches) {
                updateFloatingState(true);
                return;
            }

            placeholder.style.height =
                cartAction.offsetHeight + 'px';

            observer = new IntersectionObserver(function (entries) {
                updateFloatingState(entries[0].isIntersecting);
            }, {
                threshold: 0
            });

            observer.observe(placeholder);
        }

        // Update placeholder when button size changes.
        if (window.ResizeObserver) {
            const resizeObserver = new ResizeObserver(function () {
                placeholder.style.height =
                    cartAction.offsetHeight + 'px';
            });

            resizeObserver.observe(cartAction);
        }

        window.addEventListener('resize', startObserver);

        if (mobileQuery.addEventListener) {
            mobileQuery.addEventListener('change', startObserver);
        } else {
            mobileQuery.addListener(startObserver);
        }

        startObserver();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFloatingCart);
    } else {
        initFloatingCart();
    }

})();




</script>
<script>
(function () {
    'use strict';

    function registerCurrentProduct() {

        if (typeof window.ajCartRegisterProducts !== 'function') {
            console.error(
                'Ajobmart Cart: ajCartRegisterProducts() is not available. Check whether ajobmart-cart.js is loaded.'
            );
            return;
        }

        window.ajCartRegisterProducts([
            {
                id: <?= json_encode($productId) ?>,
                product_id: <?= json_encode($productId) ?>,
                product_name: <?= json_encode($productName) ?>,

                listing_price: <?= json_encode($price) ?>,
                selling_price: <?= json_encode($sellingPrice) ?>,
                mrp: <?= json_encode($mrp) ?>,

                stock_quantity: <?= json_encode($stock) ?>,
                min_order_quantity: <?= json_encode($moq) ?>,

                shipping_allowance: <?= json_encode(
                    (float) ($product['shipping_allowance'] ?? 0)
                ) ?>,

                image_path: <?= json_encode($cartImagePath) ?>
            }
        ]);

        console.log(
            'Ajobmart: Product registered for cart:',
            <?= json_encode($productId) ?>
        );
    }

    // Wait until normal footer scripts, including ajobmart-cart.js,
    // have had an opportunity to load.
    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            registerCurrentProduct,
            { once: true }
        );
    } else {
        registerCurrentProduct();
    }

    window.AJ_CURRENT_PRODUCT = <?= json_encode([
        'id'            => $productId,
        'product_name'  => $productName,
        'listing_price' => $price,
        'mrp'           => $mrp,
        'image_path'    => $cartImagePath
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

})(); 
</script>

