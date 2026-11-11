<style>

/* CHECKOUT PRICE DETAILS */

.aj-checkout-price-details {
    background: #fff;
    padding: 5px 0;
}

.aj-checkout-price-details h6 {
    color: #202b3b;
    font-size: 15px;
}

.aj-checkout-price-details .d-flex {
    font-size: 13px;
}

/* Savings */

.aj-checkout-savings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px;
    border-radius: 10px;
    background: #edf9f1;
    color: #148348;
    font-size: 13px;
}

/* Product WOW */

.aj-checkout-product-wow {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #fff8e9;
    border: 1px dashed #edc46f;
    border-radius: 10px;
    font-size: 12px;
}

.aj-checkout-product-wow > div {
    color: #95600b;
}

/* Final total */

.aj-checkout-final-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f1f8f3;
    border-radius: 12px;
}

.aj-checkout-final-total > div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.aj-checkout-final-total > div strong {
    font-size: 15px;
    color: #172033;
}

.aj-checkout-final-total small {
    font-size: 10px;
    color: #87909e;
}

.aj-checkout-final-total > strong {
    font-size: 23px;
    font-weight: 800;
    color: #148348;
    white-space: nowrap;
}

</style>
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a
            href="<?= esc(site_url('/')) ?>"
            class="btn btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
            style="width: 42px; height: 42px;"
            aria-label="Go back"
            onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }"
        >
            <i class="bi bi-arrow-left"></i>
        </a>

        <h3 class="mb-0">Checkout</h3>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header fw-semibold">
                    <i class="bi bi-geo-alt"></i> Delivery address
                </div>
                <div class="card-body">
                    <div class="fw-semibold">
                        <?= esc($address['recipient_name']) ?>
                    </div>
                    <div><?= esc($address['mobile']) ?></div>
                    <div>
                        <?= esc($address['address_line1']) ?>
                        <?= esc($address['address_line2'] ?? '') ?>
                    </div>
                    <div>
                        <?= esc($address['city']) ?>,
                        <?= esc($address['state']) ?> -
                        <?= esc($address['pincode']) ?>
                    </div>

                    <a
                        class="btn btn-outline-primary btn-sm mt-3"
                        href="<?= esc(
                            site_url('checkout') .
                            '?address_id=' .
                            (int) $address['id']
                        ) ?>"
                    >
                        Address confirmed
                    </a>
                </div>
            </div>

<div class="card mb-3" id="ajPaymentMethodCard">
    <div class="card-header fw-semibold">
        <i class="bi bi-credit-card me-2"></i>Payment method
    </div>

    <div class="card-body">

        <!-- Online payment toggle -->
        <div class="border rounded p-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <input
                    type="radio"
                    name="payment_method"
                    id="ajPaymentOnline"
                    value="online"
                    form="ajCheckoutForm"
                    checked
                >

                <label
                    for="ajPaymentOnline"
                    class="d-flex align-items-center justify-content-between flex-grow-1 mb-0"
                    style="cursor:pointer;"
                >
                    <span>
                        <strong>Online Payment</strong>
                        <br>
                        <small class="text-muted">
                            UPI, cards, net banking and more
                        </small>
                    </span>

                    <i
                        class="bi bi-chevron-down"
                        id="ajOnlinePaymentChevron"
                    ></i>
                </label>
            </div>

            <!-- Gateway choices -->
<div
    id="ajOnlinePaymentOptions"
    class="mt-3 pt-3 border-top"
    style="display:none;"
>
    <div class="small text-muted mb-3">
        Choose a payment gateway
    </div>

    <!-- Razorpay -->
    <label class="d-flex align-items-center gap-3 border rounded p-3 mb-2"
           for="ajGatewayRazorpay"
           style="cursor:pointer;">
        <input
            type="radio"
            id="ajGatewayRazorpay"
            name="payment_gateway"
            value="razorpay"
            form="ajCheckoutForm"
        >

        <img
            src="https://razorpay.com/favicon.png"
            alt="Razorpay"
            width="40"
            height="40"
            style="object-fit:contain;"
        >

        <span class="flex-grow-1">
            <strong>Razorpay</strong>
            <br>
            <small class="text-muted">UPI, cards and net banking</small>
        </span>

        <i class="bi bi-chevron-right text-muted"></i>
    </label>

    <!-- Cashfree -->
    <!-- <label class="d-flex align-items-center gap-3 border rounded p-3 mb-2"
           for="ajGatewayCashfree"
           style="cursor:pointer;">
        <input
            type="radio"
            id="ajGatewayCashfree"
            name="payment_gateway"
            value="cashfree"
            form="ajCheckoutForm"
        >

        <img
            src="https://www.cashfree.com/favicon.ico"
            alt="Cashfree Payments"
            width="40"
            height="40"
            style="object-fit:contain;"
        >

        <span class="flex-grow-1">
            <strong>Cashfree Payments</strong>
            <br>
            <small class="text-muted">UPI, cards and more</small>
        </span>

        <i class="bi bi-chevron-right text-muted"></i>
    </label> -->

    <!-- PayU -->
    <!-- <label class="d-flex align-items-center gap-3 border rounded p-3 mb-2"
           for="ajGatewayPayU"
           style="cursor:pointer;">
        <input
            type="radio"
            id="ajGatewayPayU"
            name="payment_gateway"
            value="payu"
            form="ajCheckoutForm"
        >

        <img
            src="https://www.payu.in/favicon.ico"
            alt="PayU"
            width="40"
            height="40"
            style="object-fit:contain;"
        >

        <span class="flex-grow-1">
            <strong>PayU</strong>
            <br>
            <small class="text-muted">Multiple online payment methods</small>
        </span>

        <i class="bi bi-chevron-right text-muted"></i>
    </label> -->
</div>
        </div>

        <!-- Cash on Delivery -->
        <label
            class="d-flex align-items-center gap-2 border rounded p-3 mb-0"
            for="ajPaymentCod"
            style="cursor:pointer;"
        >
            <input
                type="radio"
                name="payment_method"
                id="ajPaymentCod"
                value="cod"
                form="ajCheckoutForm"
            >
            <span>
                <strong>Cash on Delivery</strong>
                <br>
                <small class="text-muted">
                    Pay when your order is delivered.
                </small>
            </span>
        </label>

    </div>
</div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header fw-semibold">
                    Order summary
                </div>

                <div class="card-body">
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                            $quantity = (int) $item['quantity'];
                            $price = (float) $item['listing_price'];

                            // Use the product image URL/path supplied by your cart data.
                            $imagePath = trim((string) ($item['image_path'] ?? ''));

                            if ($imagePath !== '' && preg_match('~^https?://~i', $imagePath)) {
                                // Already a complete URL.
                                $imageUrl = $imagePath;
                            } elseif ($imagePath !== '') {
                                // Normalize stored paths.
                                $imagePath = str_replace('\\', '/', $imagePath);
                                $imagePath = ltrim($imagePath, '/');

                                // Remove the shared folder prefix if already present.
                                $imagePath = preg_replace(
                                    '~^(?:shared-images/)?(?:uploads/products/)?~',
                                    '',
                                    $imagePath
                                );

                                // Serve through the SharedImages controller.
                                $imageUrl = base_url('shared-images/uploads/products/' . $imagePath);
                            } else {
                                $imageUrl = base_url('assets/images/no-image.png');
                            }

                            $productName = $item['product_name']
                                ?? $item['name']
                                ?? ('Product #' . $item['product_id']);
                        ?>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <!-- Product image -->
                            <img
                                src="<?= esc($imageUrl) ?>"
                                alt="<?= esc($item['product_name'] ?? 'Product') ?>"
                                width="76"
                                height="76"
                                class="rounded border flex-shrink-0"
                                style="object-fit: contain; background: #fff; padding: 4px;"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='<?= esc(base_url('assets/images/no-image.png')) ?>';"
                            >

                            <!-- Product details -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold">
                                    <?= esc($productName) ?>
                                </div>

                                <small class="text-muted">
                                    Qty: <?= $quantity ?>
                                </small>

                                <div class="text-muted small">
                                    ₹<?= number_format($price, 2) ?> each
                                </div>
                            </div>

                            <!-- Line total -->
                            <div class="fw-semibold text-nowrap">
                                ₹<?= number_format($price * $quantity, 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    
                    
                    <!-- CHECKOUT PRICE DETAILS -->

                    <div class="aj-checkout-price-details">

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-receipt me-2"></i>
                            Price Details
                        </h6>

                        <!-- MRP -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total MRP</span>
                            <strong>
                                ₹<?= number_format($totalMrp, 2) ?>
                            </strong>
                        </div>

                        <!-- PRODUCT SUBTOTAL -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Product Subtotal</span>
                            <strong>
                                ₹<?= number_format($subtotal, 2) ?>
                            </strong>
                        </div>

                        <!-- TOTAL SAVINGS -->
                        <div class="aj-checkout-savings mb-3">
                            <div>
                                <i class="bi bi-piggy-bank-fill me-2"></i>
                                <strong>You are saving</strong>
                            </div>

                            <strong id="ajCheckoutTotalSavings">
                                ₹<?= number_format($totalSavings, 2) ?>
                            </strong>
                        </div>

                        <!-- PRODUCT WOW DISCOUNT -->
                        <?php if ($wowProductDiscount > 0): ?>

                            <!-- PRODUCT WOW DISCOUNT -->
                            <div
                                class="aj-checkout-product-wow mb-3"
                                id="ajCheckoutProductWow"
                                style="<?= $wowProductDiscount > 0 ? '' : 'display:none;' ?>"
                            >
                                <div>
                                    <i class="bi bi-lightning-charge-fill me-2"></i>
                                    <strong id="ajCheckoutProductWowLabel">
                                        Product WOW Discount
                                    </strong>
                                </div>

                                <strong
                                    class="text-success"
                                    id="ajCheckoutProductWowAmount"
                                >
                                    -₹<?= number_format($wowProductDiscount, 2) ?>
                                </strong>
                            </div>

                        <?php endif; ?>

                        <hr>

                        <!-- ADDITIONAL CHARGES -->
                        <div class="fw-bold small mb-3">
                            Additional Charges
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                <i class="bi bi-truck me-2"></i>
                                Delivery Fee
                            </span>

                            <strong>
                                <?= $deliveryFee > 0
                                    ? '₹' . number_format($deliveryFee, 2)
                                    : 'FREE' ?>
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                <i class="bi bi-wallet2 me-2"></i>
                                Cashback Used
                            </span>

                            <strong class="text-success">
                                -₹<?= number_format($couponDiscount, 2) ?>
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                Platform Fee
                            </span>

                            <strong>
                                ₹<?= number_format($platformFee, 2) ?>
                            </strong>
                        </div>

                    </div>

                    <hr>

                    



                    <!-- FINAL PAYABLE -->
                    <div class="aj-checkout-final-total mb-3">

                        <div>
                            <strong>Total Payable</strong>
                            <small>After applicable discounts and charges</small>
                        </div>

                        <strong
                            id="ajCheckoutTotal"
                            data-original-total="<?= esc(number_format((float) $finalTotal, 2, '.', '')) ?>"
                            data-subtotal="<?= esc(number_format((float) $subtotal, 2, '.', '')) ?>"
                            data-original-savings="<?= esc(number_format((float) $totalSavings, 2, '.', '')) ?>"
                        >
                            ₹<?= number_format($finalTotal, 2) ?>
                        </strong>

                    </div>



                    <div class="alert alert-info small mt-3 mb-3">
                        The final delivery charge must be validated using
                        the shipping calculation for your selected PIN code.
                    </div>

                    <form
                        id="ajCheckoutForm"
                        method="post"
                        action="<?= esc(site_url('checkout/place-order')) ?>"
                    >
                        <?= csrf_field() ?>

                        <input
                            type="hidden"
                            name="address_id"
                            value="<?= (int) $address['id'] ?>"
                        >

                        <button type="submit" class="btn btn-primary w-100">
                            Continue to payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
document.addEventListener('DOMContentLoaded', function () {
    const onlineRadio = document.getElementById('ajPaymentOnline');
    const codRadio = document.getElementById('ajPaymentCod');
    const options = document.getElementById('ajOnlinePaymentOptions');
    const chevron = document.getElementById('ajOnlinePaymentChevron');
    const gatewayRadios = document.querySelectorAll(
        'input[name="payment_gateway"]'
    );

    if (!onlineRadio || !codRadio || !options || !chevron) {
        return;
    }

    function updatePaymentOptions() {
        const isOnline = onlineRadio.checked;

        options.style.display = isOnline ? 'block' : 'none';

        chevron.classList.toggle('bi-chevron-down', !isOnline);
        chevron.classList.toggle('bi-chevron-up', isOnline);

        gatewayRadios.forEach(function (radio) {
            radio.required = isOnline;
        });

        if (!isOnline) {
            gatewayRadios.forEach(function (radio) {
                radio.checked = false;
            });
        }
    }

    onlineRadio.addEventListener('change', updatePaymentOptions);
    codRadio.addEventListener('change', updatePaymentOptions);

    updatePaymentOptions();

    

// ========================================
// WOW DISCOUNT CALCULATION
// ========================================








const checkoutTotal = document.getElementById('ajCheckoutTotal');

const wowEligibleSubtotal = <?= json_encode((float) $wowEligibleSubtotal) ?>;

if (checkoutTotal) {

    const originalTotal = Number(checkoutTotal.dataset.originalTotal) || 0;

    function formatWowCurrency(amount) {
        return '₹' + Number(amount).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    
    function calculateWowDiscount() {

        const isOnline = onlineRadio.checked;

        // ========================================
        // PAYMENT METHOD DISCOUNT PERCENTAGE
        // ========================================

        const discountPercentage = isOnline ? 20 : 10;

        // ========================================
        // PRODUCT WOW DISCOUNT
        // ========================================

        const productWowOnline = <?= json_encode((float) $wowProductDiscount) ?>;
        const productWowCod = <?= json_encode((float) $wowProductDiscountCod) ?>;

        const selectedProductWow = isOnline
            ? productWowOnline
            : productWowCod;

        const productWowSection = document.getElementById('ajCheckoutProductWow');
        const productWowAmount = document.getElementById('ajCheckoutProductWowAmount');

        if (productWowSection && productWowAmount) {

            productWowSection.style.display =
                selectedProductWow > 0 ? 'flex' : 'none';

            productWowAmount.textContent =
                '-' + formatWowCurrency(selectedProductWow);
        }

        // ========================================
        // PAYMENT METHOD WOW DISCOUNT
        // ========================================

        // Only eligible cart products
        const paymentDiscountAmount = Math.floor(
            wowEligibleSubtotal * discountPercentage / 100
        );

        

        

        

        


        // ========================================
        // FINAL PAYABLE CALCULATION
        // ========================================

        // Original total already includes the online (20%) product WOW discount.
        // Adjust it when COD is selected.
        const productDiscountDifference =
            productWowOnline - selectedProductWow;

        const payableAmount = Math.max(
            originalTotal +
            productDiscountDifference -
            paymentDiscountAmount,
            0
        );

        // Update final payable amount
        checkoutTotal.textContent =
            formatWowCurrency(Math.round(payableAmount));

        // ========================================
        // UPDATE TOTAL SAVINGS
        // ========================================

        const totalSavingsElement =
            document.getElementById('ajCheckoutTotalSavings');

        if (totalSavingsElement) {

            const originalSavings =
                Number(checkoutTotal.dataset.originalSavings) || 0;

            const updatedSavings =
                originalSavings -
                productWowOnline +
                selectedProductWow +
                paymentDiscountAmount;

            totalSavingsElement.textContent =
                formatWowCurrency(Math.max(0, updatedSavings));
        }
    }


    onlineRadio.addEventListener('change', calculateWowDiscount);
    codRadio.addEventListener('change', calculateWowDiscount);

    // Initial calculation
    calculateWowDiscount();
}


});

</script>