
<?php
$money = static function ($amount) {
    return '₹' . number_format((float) $amount, 2);
};

$statusInfo = static function ($status) {
    $status = strtolower(trim((string) $status));

    $map = [
        'confirmed'       => ['Confirmed', 'success'],
        'pending'         => ['Pending', 'warning'],
        'pending_payment' => ['Payment Pending', 'warning'],
        'processing'      => ['Processing', 'primary'],
        'packed'          => ['Packed', 'info'],
        'shipped'         => ['Shipped', 'primary'],
        'out_for_delivery'=> ['Out for Delivery', 'info'],
        'delivered'       => ['Delivered', 'success'],
        'cancelled'       => ['Cancelled', 'danger'],
        'failed'          => ['Failed', 'danger'],
        'returned'        => ['Returned', 'secondary'],
    ];

    return $map[$status] ?? [
        ucwords(str_replace('_', ' ', $status ?: 'Pending')),
        'secondary'
    ];
};

$paymentInfo = static function ($status) {
    $status = strtolower(trim((string) $status));

    $map = [
        'paid'    => ['Paid', 'success'],
        'pending' => ['Pending', 'warning'],
        'failed'  => ['Failed', 'danger'],
        'refunded'=> ['Refunded', 'info'],
    ];

    return $map[$status] ?? [
        ucfirst($status ?: 'Pending'),
        'secondary'
    ];
};

$imageUrl = static function ($path) {
    $path = trim((string) $path);

    if ($path === '') {
        return base_url('assets/images/no-image.png');
    }

    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    $path = str_replace('\\', '/', $path);
    $path = ltrim($path, '/');

    $path = preg_replace(
        '~^(?:shared-images/)?(?:uploads/products/)?~',
        '',
        $path
    );

    return base_url('shared-images/uploads/products/' . $path);
};
?>

<style>
.aj-orders-page {
    padding: 30px 0 60px;
    color: #172033;
}

.aj-orders-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.aj-orders-heading h2 {
    font-size: 27px;
    font-weight: 800;
    margin: 0;
}

.aj-orders-heading p {
    color: #64748b;
    font-size: 14px;
    margin: 6px 0 0;
}

.aj-order-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 16px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .035);
}

.aj-order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 18px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e8edf3;
}

.aj-order-number {
    font-size: 15px;
    font-weight: 800;
    color: #172033;
}

.aj-order-date {
    font-size: 12px;
    color: #64748b;
    margin-top: 5px;
}

.aj-status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 11px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.aj-status-success {
    background: #dcfce7;
    color: #166534;
}

.aj-status-warning {
    background: #fef3c7;
    color: #92400e;
}

.aj-status-primary {
    background: #dbeafe;
    color: #1d4ed8;
}

.aj-status-info {
    background: #cffafe;
    color: #155e75;
}

.aj-status-danger {
    background: #fee2e2;
    color: #991b1b;
}

.aj-status-secondary {
    background: #e2e8f0;
    color: #475569;
}

.aj-order-body {
    padding: 20px;
}

.aj-order-product {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.aj-order-product:last-child {
    border-bottom: 0;
}

.aj-order-product img {
    width: 68px;
    height: 68px;
    flex-shrink: 0;
    object-fit: contain;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 5px;
}

.aj-product-name {
    font-size: 14px;
    font-weight: 700;
    color: #172033;
}

.aj-product-meta {
    color: #64748b;
    font-size: 12px;
    margin-top: 5px;
}

.aj-product-total {
    margin-left: auto;
    font-size: 14px;
    font-weight: 800;
    white-space: nowrap;
}

.aj-order-summary {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 15px;
    border-top: 1px solid #e8edf3;
    margin-top: 15px;
    padding-top: 18px;
}

.aj-summary-item small {
    display: block;
    color: #64748b;
    font-size: 11px;
    margin-bottom: 5px;
}

.aj-summary-item strong {
    font-size: 15px;
}

.aj-order-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 18px;
}

.aj-details-btn {
    border: 1px solid #dbe3ed;
    background: #fff;
    color: #334155;
    border-radius: 8px;
    padding: 9px 15px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.aj-details-btn:hover {
    background: #f1f5f9;
}

.aj-order-details {
    display: none;
    padding: 18px 20px;
    background: #fbfdff;
    border-top: 1px solid #e8edf3;
}

.aj-order-details.aj-open {
    display: block;
}

.aj-details-title {
    font-size: 14px;
    font-weight: 800;
    margin: 0 0 13px;
}

.aj-shipment-box {
    border: 1px solid #e5eaf0;
    border-radius: 10px;
    padding: 13px;
    margin-top: 10px;
    background: #fff;
    font-size: 12px;
}

.aj-shipment-box strong {
    color: #172033;
}

.aj-empty-orders {
    text-align: center;
    background: #fff;
    padding: 65px 20px;
    border: 1px solid #e5eaf0;
    border-radius: 16px;
}

.aj-empty-orders i {
    font-size: 65px;
    color: #cbd5e1;
}

.aj-empty-orders h3 {
    font-size: 21px;
    font-weight: 800;
    margin: 15px 0 8px;
}

.aj-empty-orders p {
    color: #64748b;
    font-size: 14px;
}

.aj-shop-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 12px 25px;
    border-radius: 30px;
    background: #16a34a;
    color: #fff !important;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

@media(max-width: 576px) {
    .aj-orders-page {
        padding-top: 20px;
    }

    .aj-orders-heading h2 {
        font-size: 22px;
    }

    .aj-order-header {
        align-items: flex-start;
        padding: 14px;
    }

    .aj-order-body {
        padding: 14px;
    }

    .aj-order-product img {
        width: 58px;
        height: 58px;
    }

    .aj-product-name {
        font-size: 12px;
    }

    .aj-product-total {
        font-size: 12px;
    }

    .aj-order-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .aj-order-details {
        padding: 15px;
    }
}
</style>

<div class="container aj-orders-page">

    <div class="aj-orders-heading">
        <div>
            <h2>My Orders</h2>
            <p>Track and manage your Ajobmart purchases.</p>
        </div>

        <a href="<?= site_url('/') ?>"
           class="btn btn-outline-primary btn-sm">
            <i class="bi bi-shop me-1"></i>
            Continue Shopping
        </a>
    </div>

    <?php if (empty($orders)): ?>

        <div class="aj-empty-orders">
            <i class="bi bi-bag"></i>

            <h3>No orders yet</h3>

            <p>
                You haven't placed any orders with Ajobmart.
                Start shopping and your orders will appear here.
            </p>

            <a href="<?= site_url('/') ?>" class="aj-shop-btn">
                <i class="bi bi-cart3 me-1"></i>
                Start Shopping
            </a>
        </div>

    <?php else: ?>

        <?php foreach ($orders as $order): ?>

            <?php
                [$orderStatusLabel, $orderStatusColor] =
                    $statusInfo($order['order_status'] ?? '');

                [$paymentStatusLabel, $paymentStatusColor] =
                    $paymentInfo($order['payment_status'] ?? '');

                $orderId = (int) $order['id'];
                $detailsId = 'ajOrderDetails' . $orderId;

                $itemCount = count($order['items'] ?? []);
            ?>

            <div class="aj-order-card">

                <!-- Order Header -->
                <div class="aj-order-header">

                    <div>
                        <div class="aj-order-number">
                            Order #<?= esc($order['order_number']) ?>
                        </div>

                        <div class="aj-order-date">
                            <i class="bi bi-calendar3 me-1"></i>

                            <?= !empty($order['created_at'])
                                ? date('d M Y, h:i A', strtotime($order['created_at']))
                                : 'Date unavailable' ?>

                            <span class="mx-1">·</span>

                            <?= $itemCount ?>
                            <?= $itemCount === 1 ? 'product' : 'products' ?>
                        </div>
                    </div>

                    <span class="aj-status-badge aj-status-<?= esc($orderStatusColor) ?>">
                        <?= esc($orderStatusLabel) ?>
                    </span>

                </div>

                <!-- Order Products -->
                <div class="aj-order-body">

                    <?php foreach (($order['items'] ?? []) as $item): ?>

                        <?php
                            $quantity = (int) ($item['quantity'] ?? 0);
                            $price = (float) ($item['unit_price'] ?? 0);
                            $productImage = $imageUrl($item['product_image'] ?? '');
                        ?>

                        <div class="aj-order-product">

                            <img
                                src="<?= esc($productImage) ?>"
                                alt="<?= esc($item['product_name'] ?? 'Product') ?>"
                                onerror="this.onerror=null;this.src='<?= esc(base_url('assets/images/no-image.png')) ?>';"
                            >

                            <div class="flex-grow-1 min-w-0">

                                <div class="aj-product-name">
                                    <?= esc($item['product_name'] ?? 'Product') ?>
                                </div>

                                <div class="aj-product-meta">
                                    Qty: <?= $quantity ?>
                                    &nbsp;·&nbsp;
                                    <?= $money($price) ?> each
                                </div>

                            </div>

                            <div class="aj-product-total">
                                <?= $money($price * $quantity) ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                    <!-- Order Summary -->
                    <div class="aj-order-summary">

                        <div class="aj-summary-item">
                            <small>Order total</small>
                            <strong><?= $money($order['total_amount'] ?? 0) ?></strong>
                        </div>

                        <div class="aj-summary-item">
                            <small>Payment method</small>
                            <strong>
                                <?= strtoupper(esc($order['payment_method'] ?? '')) ?>
                            </strong>
                        </div>

                        <div class="aj-summary-item">
                            <small>Payment status</small>
                            <span class="aj-status-badge aj-status-<?= esc($paymentStatusColor) ?>">
                                <?= esc($paymentStatusLabel) ?>
                            </span>
                        </div>

                        <div class="aj-summary-item">
                            <small>Shipping charge</small>
                            <strong><?= $money($order['shipping_charge'] ?? 0) ?></strong>
                        </div>

                    </div>

                    <!-- Details Toggle -->
                    <div class="aj-order-actions">

                        <button
                            type="button"
                            class="aj-details-btn"
                            data-details="<?= esc($detailsId) ?>"
                        >
                            View Order Details
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>

                    </div>

                </div>

                <!-- Expandable Order Details -->
                <div class="aj-order-details" id="<?= esc($detailsId) ?>">

                    <h5 class="aj-details-title">
                        <i class="bi bi-receipt me-1"></i>
                        Order Information
                    </h5>

                    <div class="row g-3 small">

                        <div class="col-md-6">
                            <span class="text-muted">Order number:</span>
                            <strong><?= esc($order['order_number']) ?></strong>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted">Subtotal:</span>
                            <strong><?= $money($order['subtotal'] ?? 0) ?></strong>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted">Shipping:</span>
                            <strong><?= $money($order['shipping_charge'] ?? 0) ?></strong>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted">Included shipping:</span>
                            <strong><?= $money($order['included_shipping'] ?? 0) ?></strong>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted">Gateway:</span>
                            <strong><?= esc($order['payment_gateway'] ?: 'Not applicable') ?></strong>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted">Payment ID:</span>
                            <strong><?= esc($order['razorpay_payment_id'] ?: 'Not available') ?></strong>
                        </div>

                    </div>

                    <?php if (!empty($order['shipments'])): ?>

                        <hr>

                        <h5 class="aj-details-title">
                            <i class="bi bi-truck me-1"></i>
                            Seller-wise Shipping
                        </h5>

                        <?php foreach ($order['shipments'] as $shipment): ?>

                            <div class="aj-shipment-box">

                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                    <strong>
                                        <?= esc($shipment['courier_name'] ?? 'Courier') ?>
                                    </strong>

                                    <span>
                                        <?= esc($shipment['courier_code'] ?? '') ?>
                                    </span>
                                </div>

                                <div class="mt-2">
                                    Seller ID:
                                    <?= (int) ($shipment['seller_id'] ?? 0) ?>
                                </div>

                                <div>
                                    Pickup PIN:
                                    <?= esc($shipment['origin_pincode'] ?? '') ?>
                                </div>

                                <div>
                                    Delivery PIN:
                                    <?= esc($shipment['destination_pincode'] ?? '') ?>
                                </div>

                                <div>
                                    Weight:
                                    <?= (int) ($shipment['weight_grams'] ?? 0) ?> grams
                                </div>

                                <div>
                                    Actual courier charge:
                                    <strong>
                                        <?= $money($shipment['actual_shipping'] ?? 0) ?>
                                    </strong>
                                </div>

                                <div>
                                    Customer shipping:
                                    <strong>
                                        <?= $money($shipment['customer_shipping'] ?? 0) ?>
                                    </strong>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.aj-details-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            const detailsId = this.dataset.details;
            const details = document.getElementById(detailsId);

            if (!details) {
                return;
            }

            const isOpen = details.classList.toggle('aj-open');

            this.innerHTML = isOpen
                ? 'Hide Order Details <i class="bi bi-chevron-up ms-1"></i>'
                : 'View Order Details <i class="bi bi-chevron-down ms-1"></i>';

        });

    });

});
</script>
