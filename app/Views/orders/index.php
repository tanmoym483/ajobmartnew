
<?php
$sharedImageUrl = rtrim(
    (string) env('AJOBMART_SHARED_UPLOAD_URL'),
    '/'
);


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

$statusClass = static function ($status) {
    return match (strtolower((string) $status)) {
        'delivered' => 'success',
        'shipped' => 'info',
        'processing' => 'primary',
        'confirmed' => 'success',
        'cancelled' => 'danger',
        'pending_payment' => 'warning',
        default => 'secondary',
    };
};
?>

<div class="container py-4 aj-orders-page">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">My Orders</h3>
            <p class="text-muted mb-0">
                View and track your Ajobmart orders
            </p>
        </div>
    </div>

    <?php foreach (['success', 'error', 'info'] as $type): ?>
        <?php if (session()->getFlashdata($type)): ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show">
                <?= esc(session()->getFlashdata($type)) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty($orders)): ?>

        <div class="text-center bg-white border rounded-3 p-5">
            <div class="mb-3">
                <i class="bi bi-bag-x" style="font-size:48px;color:#999"></i>
            </div>
            <h5>No orders yet</h5>
            <p class="text-muted">Your orders will appear here after checkout.</p>
            <a href="<?= site_url('/') ?>" class="btn btn-dark">
                Continue Shopping
            </a>
        </div>

    <?php else: ?>

        <div class="d-flex flex-column gap-3">

            <?php foreach ($orders as $order): ?>

                <?php
                $items = $order['items'] ?? [];
                $visibleItems = array_slice($items, 0, 3);
                $remainingItems = max(0, count($items) - 3);
                $request = $order['cancellation_request'] ?? null;
                ?>

                <a href="<?= site_url('my-orders/' . (int) $order['id']) ?>"
                   class="aj-order-card text-decoration-none">

                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                        <div>
                            <div class="fw-bold text-dark">
                                Order #<?= esc($order['order_number']) ?>
                            </div>

                            <small class="text-muted">
                                <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                            </small>
                        </div>

                        <div class="text-end">
                            <span class="badge text-bg-<?= $statusClass($order['order_status']) ?>">
                                <?= esc(ucwords(str_replace('_', ' ', $order['order_status']))) ?>
                            </span>

                            <?php if ($request): ?>
                                <div class="mt-1">
                                    <small class="text-warning">
                                        Cancellation:
                                        <?= esc(ucwords($request['request_status'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-3 mt-3">

                        <div class="d-flex align-items-center gap-2">

                            <?php foreach ($visibleItems as $item): ?>
                                <img
                                    src="<?= esc($productImage($item['product_image'] ?? '')) ?>"
                                    alt="<?= esc($item['product_name'] ?? 'Product') ?>"
                                    class="aj-order-thumb"
                                    loading="lazy"
                                >
                            <?php endforeach; ?>

                            <?php if ($remainingItems > 0): ?>
                                <div class="aj-order-more">
                                    +<?= $remainingItems ?>
                                </div>
                            <?php endif; ?>

                            <?php if (empty($items)): ?>
                                <span class="text-muted small">Product details unavailable</span>
                            <?php endif; ?>

                        </div>

                        <div class="text-end ms-auto">
                            <div class="fw-bold text-dark">
                                ₹<?= number_format((float) $order['total_amount'], 2) ?>
                            </div>
                            <small class="text-muted">
                                <?= (int) ($order['item_count'] ?? count($items)) ?> products
                                · <?= (int) ($order['total_quantity'] ?? 0) ?> items
                            </small>
                        </div>

                        <div class="aj-order-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<style>
.aj-orders-page {
    max-width: 950px;
}

.aj-order-card {
    display: block;
    padding: 18px;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,.035);
    transition: box-shadow .2s ease, transform .2s ease;
}

.aj-order-card:hover {
    color: inherit;
    box-shadow: 0 5px 16px rgba(0,0,0,.08);
    transform: translateY(-1px);
}

.aj-order-thumb {
    width: 64px;
    height: 64px;
    object-fit: contain;
    border: 1px solid #eee;
    border-radius: 8px;
    background: #fff;
    padding: 4px;
}

.aj-order-more {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    background: #f4f4f4;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.aj-order-arrow {
    color: #777;
    font-size: 20px;
    padding-left: 4px;
}

@media(max-width:576px) {
    .aj-order-card {
        padding: 14px;
    }

    .aj-order-thumb,
    .aj-order-more {
        width: 48px;
        height: 48px;
    }

    .aj-order-card .badge {
        font-size: 10px;
    }
}
</style>
