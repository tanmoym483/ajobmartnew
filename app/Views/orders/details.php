
<?php

$productImage = static function ($path) {
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

$status = strtolower((string) ($order['order_status'] ?? 'pending_payment'));

$statusClass = match ($status) {
    'delivered' => 'success',
    'shipped' => 'info',
    'processing' => 'primary',
    'confirmed' => 'success',
    'cancelled' => 'danger',
    'pending_payment' => 'warning',
    default => 'secondary',
};

$requestStatus = $cancellationRequest['request_status'] ?? null;
?>

<div class="container py-4 aj-order-details">

    <a href="<?= site_url('my-orders') ?>"
       class="text-decoration-none text-muted d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Back to My Orders
    </a>

    <?php foreach (['success', 'error', 'info'] as $type): ?>
        <?php if (session()->getFlashdata($type)): ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show">
                <?= esc(session()->getFlashdata($type)) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- ORDER HEADER -->
    <div class="aj-detail-card mb-3">

        <div class="d-flex justify-content-between flex-wrap gap-3">

            <div>
                <h4 class="mb-1">
                    Order #<?= esc($order['order_number']) ?>
                </h4>

                <div class="text-muted small">
                    Placed on
                    <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                </div>
            </div>

            <div>
                <span class="badge text-bg-<?= $statusClass ?> p-2">
                    <?= esc(ucwords(str_replace('_', ' ', $status))) ?>
                </span>
            </div>

        </div>

    </div>

    <!-- ORDERED PRODUCTS -->
    <div class="aj-detail-card mb-3">

        <h5 class="aj-section-title">Ordered Products</h5>

        <?php foreach ($items as $item): ?>

            <div class="aj-detail-product">

                <img
                    src="<?= esc($productImage($item['product_image'] ?? '')) ?>"
                    alt="<?= esc($item['product_name']) ?>"
                >

                <div class="flex-grow-1">
                    <div class="fw-semibold">
                        <?= esc($item['product_name']) ?>
                    </div>

                    <small class="text-muted">
                        Quantity: <?= (int) $item['quantity'] ?>
                    </small>

                    <div class="small text-muted">
                        Unit price:
                        ₹<?= number_format((float) $item['unit_price'], 2) ?>
                    </div>
                </div>

                <div class="fw-bold">
                    ₹<?= number_format((float) $item['line_total'], 2) ?>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <!-- PRICE DETAILS -->
    <div class="aj-detail-card mb-3">

        <h5 class="aj-section-title">Price Details</h5>

        <div class="aj-price-row">
            <span>Subtotal</span>
            <span>₹<?= number_format((float) $order['subtotal'], 2) ?></span>
        </div>

        <div class="aj-price-row">
            <span>Shipping charge</span>
            <span>₹<?= number_format((float) $order['shipping_charge'], 2) ?></span>
        </div>

        <div class="aj-price-row">
            <span>Platform fee</span>
            <span>₹<?= number_format((float) $order['platform_fee'], 2) ?></span>
        </div>

        <div class="aj-price-row text-success">
            <span>Cashback used</span>
            <span>- ₹<?= number_format((float) $order['cashback_deduct'], 2) ?></span>
        </div>

        

        <?php if ((float) ($order['product_wow_discount'] ?? 0) > 0): ?>
            <div class="aj-price-row text-success">
                <span>Product WOW discount</span>
                <span>- ₹<?= number_format((float) $order['product_wow_discount'], 2) ?></span>
            </div>
        <?php endif; ?>

        <hr>

        <div class="aj-price-row fw-bold fs-5">
            <span>Total paid / payable</span>
            <span>₹<?= number_format((float) $order['total_amount'], 2) ?></span>
        </div>

    </div>

    <!-- DELIVERY ADDRESS -->
    <div class="aj-detail-card mb-3">

        <h5 class="aj-section-title">Delivery Address</h5>

        <?php if ($address): ?>

            <div class="fw-semibold">
                <?= esc($address['recipient_name']) ?>
            </div>

            <div><?= esc($address['mobile']) ?></div>

            <div>
                <?= esc($address['address_line1']) ?>
                <?= !empty($address['address_line2']) ? ', ' . esc($address['address_line2']) : '' ?>
            </div>

            <?php if (!empty($address['landmark'])): ?>
                <div>Landmark: <?= esc($address['landmark']) ?></div>
            <?php endif; ?>

            <div>
                <?= esc($address['city']) ?>,
                <?= esc($address['state']) ?> -
                <?= esc($address['pincode']) ?>
            </div>

        <?php else: ?>

            <p class="text-muted mb-0">
                Saved delivery address is no longer available.
            </p>

        <?php endif; ?>

    </div>

    <!-- PAYMENT INFORMATION -->
    <div class="aj-detail-card mb-3">

        <h5 class="aj-section-title">Payment Information</h5>

        <div class="aj-price-row">
            <span>Payment method</span>
            <strong><?= esc(strtoupper($order['payment_method'])) ?></strong>
        </div>

        <div class="aj-price-row">
            <span>Payment status</span>
            <strong><?= esc(ucfirst($order['payment_status'])) ?></strong>
        </div>

        <?php if (!empty($order['payment_gateway'])): ?>
            <div class="aj-price-row">
                <span>Payment gateway</span>
                <span><?= esc($order['payment_gateway']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($order['razorpay_order_id'])): ?>
            <div class="small text-muted mt-2">
                Gateway order ID:
                <?= esc($order['razorpay_order_id']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($order['razorpay_payment_id'])): ?>
            <div class="small text-muted">
                Payment ID:
                <?= esc($order['razorpay_payment_id']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($payments)): ?>

            <hr>
            <h6>Payment Attempts</h6>

            <?php foreach ($payments as $payment): ?>

                <div class="border rounded p-2 mb-2">

                    <div class="aj-price-row">
                        <span><?= esc(strtoupper($payment['payment_gateway'])) ?></span>
                        <strong><?= esc(ucfirst($payment['payment_status'])) ?></strong>
                    </div>

                    <div class="small text-muted">
                        Amount:
                        ₹<?= number_format((float) $payment['amount'], 2) ?>
                    </div>

                    <?php if (!empty($payment['gateway_order_id'])): ?>
                        <div class="small text-muted">
                            Gateway Order:
                            <?= esc($payment['gateway_order_id']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($payment['gateway_payment_id'])): ?>
                        <div class="small text-muted">
                            Gateway Payment:
                            <?= esc($payment['gateway_payment_id']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($payment['failure_reason'])): ?>
                        <div class="small text-danger">
                            <?= esc($payment['failure_reason']) ?>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- SELLER SHIPMENT AND TRACKING -->
    <div class="aj-detail-card mb-3">

        <h5 class="aj-section-title">Shipment & Tracking</h5>

        <?php if (empty($fulfillments) && empty($bookings) && empty($shipments)): ?>

            <p class="text-muted mb-0">
                Shipment information will appear here when available.
            </p>

        <?php else: ?>

            <?php foreach ($shipments as $shipment): ?>

                <div class="border rounded p-3 mb-3">

                    <h6>Seller shipment</h6>

                    <div class="aj-price-row">
                        <span>Courier</span>
                        <span><?= esc($shipment['courier_name'] ?? 'Not assigned') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Origin PIN</span>
                        <span><?= esc($shipment['origin_pincode'] ?? '-') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Destination PIN</span>
                        <span><?= esc($shipment['destination_pincode'] ?? '-') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Shipment quantity</span>
                        <span><?= (int) ($shipment['item_quantity'] ?? 0) ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Customer shipping</span>
                        <span>₹<?= number_format((float) ($shipment['customer_shipping'] ?? 0), 2) ?></span>
                    </div>

                </div>

            <?php endforeach; ?>

            <?php foreach ($fulfillments as $fulfillment): ?>

                <div class="border rounded p-3 mb-3">

                    <h6>Courier tracking</h6>

                    <div class="aj-price-row">
                        <span>Shipment status</span>
                        <strong>
                            <?= esc(ucfirst($fulfillment['seller_status'])) ?>
                        </strong>
                    </div>

                    <div class="aj-price-row">
                        <span>Courier</span>
                        <span><?= esc($fulfillment['courier_name'] ?? '-') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Tracking number</span>
                        <span><?= esc($fulfillment['tracking_number'] ?? 'Not available') ?></span>
                    </div>

                    <?php if (!empty($fulfillment['shipped_at'])): ?>
                        <div class="small text-muted">
                            Shipped:
                            <?= esc($fulfillment['shipped_at']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($fulfillment['delivered_at'])): ?>
                        <div class="small text-muted">
                            Delivered:
                            <?= esc($fulfillment['delivered_at']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($fulfillment['tracking_url'])): ?>
                        <a href="<?= esc($fulfillment['tracking_url']) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-dark btn-sm mt-2">
                            Track Shipment
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($fulfillment['seller_note'])): ?>
                        <div class="small text-muted mt-2">
                            Note: <?= esc($fulfillment['seller_note']) ?>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <?php foreach ($bookings as $booking): ?>

                <div class="border rounded p-3 mb-3">

                    <h6>India Post Tracking</h6>

                    <div class="aj-price-row">
                        <span>Booking status</span>
                        <strong>
                            <?= esc(ucwords(str_replace('_', ' ', $booking['admin_status']))) ?>
                        </strong>
                    </div>

                    <div class="aj-price-row">
                        <span>Service</span>
                        <span><?= esc($booking['booking_service'] ?? '-') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Consignment number</span>
                        <strong><?= esc($booking['consignment_number'] ?? 'Not booked yet') ?></strong>
                    </div>

                    <div class="aj-price-row">
                        <span>Booking date</span>
                        <span><?= esc($booking['booking_date'] ?? '-') ?></span>
                    </div>

                    <div class="aj-price-row">
                        <span>Post office</span>
                        <span><?= esc($booking['booking_post_office'] ?? '-') ?></span>
                    </div>

                    <?php if (!empty($booking['shipped_at'])): ?>
                        <div class="small text-muted">
                            Shipped: <?= esc($booking['shipped_at']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($booking['delivered_at'])): ?>
                        <div class="small text-muted">
                            Delivered: <?= esc($booking['delivered_at']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($booking['admin_note'])): ?>
                        <div class="small text-muted mt-2">
                            Admin note: <?= esc($booking['admin_note']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($booking['rejection_reason'])): ?>
                        <div class="small text-danger mt-2">
                            <?= esc($booking['rejection_reason']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($booking['postal_receipt_path'])): ?>
                        <div class="small mt-2">
                            Postal receipt:
                            <?= esc(basename($booking['postal_receipt_path'])) ?>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <?php if (!empty($labels)): ?>

            <hr>
            <h6>Shipping Labels</h6>

            <?php foreach ($labels as $label): ?>
                <div class="aj-price-row">
                    <span><?= esc($label['label_number']) ?></span>
                    <span><?= esc(ucfirst($label['label_status'])) ?></span>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- CANCELLATION REQUEST -->
    <div class="aj-detail-card mb-4">

        <h5 class="aj-section-title">Cancellation Request</h5>

        <?php if ($cancellationRequest): ?>

            <?php
            $cancelClass = match ($requestStatus) {
                'approved' => 'success',
                'rejected' => 'danger',
                default => 'warning',
            };
            ?>

            <div class="alert alert-<?= $cancelClass ?>">
                <strong>
                    Request <?= esc(ucfirst($requestStatus)) ?>
                </strong>

                <div class="mt-1">
                    Reason: <?= nl2br(esc($cancellationRequest['reason'])) ?>
                </div>

                <?php if (!empty($cancellationRequest['admin_note'])): ?>
                    <div class="mt-2">
                        Admin response:
                        <?= nl2br(esc($cancellationRequest['admin_note'])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cancellationRequest['reviewed_at'])): ?>
                    <small>
                        Reviewed: <?= esc($cancellationRequest['reviewed_at']) ?>
                    </small>
                <?php endif; ?>
            </div>

            <?php if ($requestStatus === 'pending'): ?>
                <p class="text-muted mb-0">
                    Your cancellation request is waiting for admin approval.
                    Your order has not been cancelled yet.
                </p>
            <?php elseif ($requestStatus === 'approved'): ?>
                <p class="text-success mb-0">
                    Your cancellation request has been approved.
                </p>
            <?php else: ?>
                <p class="text-muted mb-0">
                    Your previous request was rejected. You may submit a new request if needed.
                </p>
            <?php endif; ?>

        <?php else: ?>

            <p class="text-muted">
                Need to cancel this order? Submit a request to our team.
                The order will remain active until an admin reviews your request.
            </p>

            <form method="post"
                  action="<?= site_url('my-orders/' . (int) $order['id'] . '/cancel-request') ?>">

                <?= csrf_field() ?>

                <label class="form-label fw-semibold">
                    Cancellation reason
                </label>

                <textarea
                    name="reason"
                    class="form-control mb-3"
                    rows="3"
                    maxlength="2000"
                    required
                    placeholder="Please tell us why you want to cancel this order..."
                ></textarea>

                <button type="submit"
                        class="btn btn-outline-danger"
                        onclick="return confirm('Submit cancellation request for admin approval?')">
                    Request Order Cancellation
                </button>

            </form>

        <?php endif; ?>

    </div>

</div>

<style>
.aj-order-details {
    max-width: 900px;
}

.aj-detail-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.035);
}

.aj-section-title {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 18px;
}

.aj-detail-product {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 13px 0;
    border-bottom: 1px solid #eee;
}

.aj-detail-product:last-child {
    border-bottom: 0;
}

.aj-detail-product img {
    width: 76px;
    height: 76px;
    object-fit: contain;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 4px;
}

.aj-price-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 11px;
}

.aj-price-row > :last-child {
    text-align: right;
}

@media(max-width:576px) {
    .aj-detail-card {
        padding: 15px;
    }

    .aj-detail-product img {
        width: 60px;
        height: 60px;
    }

    .aj-detail-product {
        gap: 9px;
        font-size: 13px;
    }
}
</style>
