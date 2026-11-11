<style>

/* ================================
   AJOBMART CART PAGE
================================ */

.aj-cart-page {
    background: #f5f7fb;
    min-height: 75vh;
    color: #252b3b;
}

.aj-cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.aj-cart-eyebrow {
    display: block;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 5px;
}

.aj-cart-header h3 {
    font-size: 25px;
    font-weight: 800;
    color: #172554;
}

.aj-cart-header p {
    font-size: 13px;
    color: #8490a5;
}

.aj-continue-shopping {
    text-decoration: none;
    border: 1px solid #dce3ef;
    background: #fff;
    color: #334155;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    transition: .2s ease;
}

.aj-continue-shopping:hover {
    background: #eef2ff;
    border-color: #c7d2fe;
    color: #4338ca;
}

/* CARDS */

.aj-cart-card {
    background: #fff;
    border: 1px solid #e9edf5;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(31, 45, 80, .045);
}

.aj-card-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 17px;
    margin-bottom: 15px;
    border-bottom: 1px solid #edf0f5;
}

.aj-card-heading h5 {
    font-size: 16px;
    font-weight: 800;
    color: #1e293b;
}

.aj-card-heading small {
    color: #94a3b8;
    font-size: 12px;
}

.aj-heading-icon {
    font-size: 23px;
    color: #6366f1;
}

/* DELIVERY ADDRESS */

.aj-delivery-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px 20px;
    background: linear-gradient(110deg, #ffffff, #f5f7ff);
}

.aj-delivery-icon {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 20px;
}

.aj-section-label {
    display: block;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 4px;
}

#ajCartDeliveryAddress {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    line-height: 1.5;
}

.aj-address-change {
    flex-shrink: 0;
    border: 0;
    background: #eef2ff;
    color: #4f46e5;
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
}

.aj-address-change:hover {
    background: #4f46e5;
    color: #fff;
}

/* PRODUCTS */

.aj-cart-products-wrapper {
    min-height: 130px;
}

/* PRICE SUMMARY */

.aj-summary-card {
    position: sticky;
    top: 20px;
}

.aj-price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 17px;
    font-size: 13px;
    color: #64748b;
}

.aj-price-row strong {
    color: #334155;
    font-size: 13px;
    font-weight: 700;
}

.aj-price-row .bi {
    color: #818cf8;
}

.aj-summary-divider {
    border-top: 1px dashed #dbe2ed;
    margin: 20px 0;
}

.aj-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
}

.aj-total-row > div {
    display: flex;
    flex-direction: column;
}

.aj-total-row > div strong {
    font-size: 15px;
    color: #172554;
}

.aj-total-row small {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 4px;
}

.aj-total-row > strong {
    font-size: 22px;
    font-weight: 800;
    color: #4338ca;
    white-space: nowrap;
}

/* CHECKOUT BUTTON */

.aj-checkout-button {
    width: 100%;
    border: 0;
    border-radius: 11px;
    padding: 14px 16px;
    background: linear-gradient(110deg, #4f46e5, #6366f1);
    color: #fff;
    font-size: 14px;
    font-weight: 800;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(79, 70, 229, .2);
    transition: .2s ease;
}

.aj-checkout-button:hover {
    background: linear-gradient(110deg, #4338ca, #4f46e5);
    transform: translateY(-2px);
    box-shadow: 0 9px 20px rgba(79, 70, 229, .25);
}

.aj-checkout-button:active {
    transform: scale(.98);
}

.aj-payment-note {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 13px;
    color: #94a3b8;
    font-size: 10px;
}

.aj-payment-note i {
    color: #16a34a;
}

/* TRUST INFORMATION */

.aj-cart-trust {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 12px;
    padding: 15px 10px;
    background: #fff;
    border: 1px solid #e9edf5;
    border-radius: 12px;
}

.aj-cart-trust > div {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
}

.aj-cart-trust i {
    color: #16a34a;
    font-size: 16px;
}

/* HELP CARD */

.aj-help-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #eef2ff;
    border: 1px solid #e0e7ff;
    border-radius: 13px;
}

.aj-help-card > i {
    font-size: 23px;
    color: #4f46e5;
}

.aj-help-card strong {
    font-size: 12px;
    color: #312e81;
}

.aj-help-card p {
    font-size: 11px;
    color: #64748b;
    margin-top: 3px;
}

/* MOBILE */

@media (max-width: 767px) {

    .aj-cart-page {
        padding-top: 15px !important;
    }

    .aj-cart-header {
        align-items: flex-start;
    }

    .aj-cart-header h3 {
        font-size: 21px;
    }

    .aj-continue-shopping {
        font-size: 11px;
        padding: 9px 10px;
        white-space: nowrap;
    }

    .aj-cart-card {
        padding: 15px;
        border-radius: 13px;
    }

    .aj-delivery-card {
        gap: 10px;
        padding: 13px;
    }

    .aj-delivery-icon {
        width: 38px;
        height: 38px;
        font-size: 17px;
    }

    .aj-address-change {
        padding: 8px 10px;
        font-size: 11px;
    }

    .aj-cart-trust {
        gap: 10px;
        justify-content: flex-start;
    }

    .aj-cart-trust > div {
        font-size: 10px;
    }

    .aj-summary-card {
        position: static;
    }

    .aj-total-row > strong {
        font-size: 20px;
    }
}

/* EMPTY CART */

.aj-empty-cart {
    background: #fff;
    border: 1px solid #e9edf5;
    border-radius: 16px;
    padding: 55px 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(31, 45, 80, .045);
}

.aj-empty-cart-icon {
    width: 95px;
    height: 95px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.aj-empty-cart-icon i {
    font-size: 43px;
    color: #6366f1;
}

.aj-empty-cart h4 {
    font-size: 21px;
    font-weight: 800;
    color: #172554;
    margin-bottom: 10px;
}

.aj-empty-cart p {
    max-width: 380px;
    margin: 0 auto 22px;
    color: #8490a5;
    font-size: 13px;
    line-height: 1.7;
}

.aj-empty-cart-button {
    display: inline-flex;
    align-items: center;
    padding: 12px 22px;
    border-radius: 10px;
    background: linear-gradient(110deg, #4f46e5, #6366f1);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}

.aj-empty-cart-button:hover {
    color: #fff;
    background: #4338ca;
}



/* NEW AJOBMART PRICE SUMMARY */

.aj-summary-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 17px;
    padding: 18px;
    box-shadow: 0 5px 20px rgba(20, 35, 55, .045);
}

/* Header */

.aj-summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 17px;
    border-bottom: 1px solid #edf0f3;
}

.aj-summary-header h5 {
    font-size: 17px;
    font-weight: 750;
    color: #172033;
    margin: 0 0 4px;
}

.aj-summary-header small {
    font-size: 11px;
    color: #8993a1;
}

.aj-summary-header > i {
    font-size: 23px;
    color: #315eea;
    background: #eff4ff;
    width: 42px;
    height: 42px;
    display: grid;
    place-items: center;
    border-radius: 12px;
}

/* Price boxes */

.aj-summary-price-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 16px 0 12px;
}

.aj-summary-price-box {
    background: #f6f8fb;
    border: 1px solid #edf0f3;
    border-radius: 12px;
    padding: 13px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.aj-summary-price-box span {
    font-size: 11px;
    color: #788394;
}

.aj-summary-price-box strong {
    font-size: 18px;
    font-weight: 750;
    color: #202b3b;
    font-variant-numeric: tabular-nums;
}

/* Savings card */

.aj-summary-savings {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px;
    border-radius: 12px;
    background: #edf9f1;
    margin-bottom: 13px;
}

.aj-savings-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #d6f2df;
    color: #138348;
    display: grid;
    place-items: center;
    font-size: 18px;
}

.aj-summary-savings > div:nth-child(2) {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
}

.aj-summary-savings > div strong {
    font-size: 12px;
    color: #167b43;
}

.aj-summary-savings small {
    font-size: 10px;
    color: #56876a;
}

.aj-summary-savings > strong {
    font-size: 16px;
    color: #138348;
    font-weight: 800;
    white-space: nowrap;
}

/* WOW offer */

.aj-wow-summary-row {
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(120deg, #fff8e9, #fffdf7);
    border: 1px dashed #edc46f;
    margin-bottom: 17px;
}

.aj-wow-summary-top,
.aj-wow-summary-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.aj-wow-summary-top > div {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #94600b;
    font-size: 12px;
}

.aj-wow-summary-top > div i {
    font-size: 17px;
    color: #e6a21b;
}

.aj-wow-summary-top > span {
    background: #f9e6b9;
    color: #95600b;
    font-size: 10px;
    font-weight: 800;
    padding: 4px 8px;
    border-radius: 20px;
}

.aj-wow-summary-bottom {
    margin-top: 11px;
}

.aj-wow-summary-bottom small {
    color: #9b804f;
    font-size: 10px;
}

.aj-wow-summary-bottom strong {
    color: #138348;
    font-size: 17px;
    font-weight: 800;
    white-space: nowrap;
}

/* Charges */

.aj-additional-charges {
    padding: 3px 0 14px;
}

.aj-charges-heading {
    padding: 0 0 9px;
    border-bottom: 1px solid #edf0f3;
    margin-bottom: 4px;
}

.aj-charges-heading strong {
    color: #283548;
    font-size: 12px;
    font-weight: 750;
}

.aj-charge-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
}

.aj-charge-label {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #738092;
    font-size: 12px;
}

.aj-charge-label i {
    font-size: 16px;
    color: #8793a3;
}

.aj-charge-item > strong {
    color: #293548;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

.aj-charge-item > .aj-positive-amount {
    color: #159653;
}

/* Final payment */

.aj-final-payment {
    background: #f5f8fc;
    border: 1px solid #e8edf4;
    border-radius: 13px;
    padding: 15px;
}

.aj-final-payment-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 15px;
}

.aj-final-payment-info > div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.aj-final-payment-info > div strong {
    font-size: 15px;
    font-weight: 800;
    color: #1d2939;
}

.aj-final-payment-info small {
    font-size: 10px;
    color: #8993a1;
}

.aj-final-payment-info > strong {
    font-size: 24px;
    font-weight: 850;
    color: #148348;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

/* Checkout button */

.aj-summary-card .aj-checkout-button {
    width: 100%;
    min-height: 49px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #159653, #087b43);
    color: #fff;
    font-size: 14px;
    font-weight: 750;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: .2s ease;
}

.aj-summary-card .aj-checkout-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(21, 150, 83, .2);
}

.aj-summary-card .aj-checkout-button i {
    font-size: 18px;
}

/* Payment security */

.aj-payment-note {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    color: #8793a1;
    font-size: 10px;
}

.aj-payment-note i {
    color: #159653;
}

/* Mobile */

@media (max-width: 575.98px) {

    .aj-summary-card {
        padding: 14px;
        border-radius: 14px;
    }

    .aj-summary-price-box {
        padding: 11px;
    }

    .aj-summary-price-box strong {
        font-size: 16px;
    }

    .aj-summary-savings > strong {
        font-size: 14px;
    }

    .aj-final-payment-info > strong {
        font-size: 22px;
    }
}

</style>
<div class="aj-cart-page py-4">
    <div class="container">

        <!-- PAGE HEADER -->
        <div class="aj-cart-header mb-4">
            <div>
                <span class="aj-cart-eyebrow">YOUR SHOPPING BAG</span>
                <h3 class="mb-1">
                    <i class="bi bi-cart3"></i> My Cart
                </h3>
                <p class="mb-0">Review your items before checkout.</p>
            </div>

            <a href="<?= base_url('/') ?>" class="aj-continue-shopping">
                <i class="bi bi-arrow-left"></i>
                Continue Shopping
            </a>
        </div>

        <div class="row g-4">

            <!-- CART PRODUCTS -->
            <div class="col-lg-8">

                <!-- DELIVERY ADDRESS -->
                <div class="aj-cart-card aj-delivery-card mb-3">
                    <div class="aj-delivery-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div class="flex-grow-1">
                        <span class="aj-section-label">DELIVER TO</span>
                        <div id="ajCartDeliveryAddress">
                            Select delivery address
                        </div>
                    </div>

                    <button type="button" id="ajCartDeliverySelector"
                            class="aj-address-change">
                        Change <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                
                <!-- EMPTY CART MESSAGE -->
                <div id="ajEmptyCart" class="aj-empty-cart" style="display:none;">
                    <div class="aj-empty-cart-icon">
                        <i class="bi bi-cart-x"></i>
                    </div>

                    <h4>Your Cart is Empty!</h4>

                    <p>
                        Looks like you haven't added anything to your cart yet.
                        Explore our products and find something you love.
                    </p>

                    <a href="<?= base_url('/') ?>" class="aj-empty-cart-button">
                        <i class="bi bi-shop me-2"></i>
                        Continue Shopping
                    </a>
                </div>
                <!-- PRODUCTS -->
                <div class="aj-cart-card" id="ajCartProductsSection">

                

                    <div class="aj-card-heading">
                        <div>
                            <h5 class="mb-1">Shopping Items</h5>
                            <small>Your selected products</small>
                        </div>
                        <i class="bi bi-bag-check aj-heading-icon"></i>
                    </div>

                    <div class="aj-cart-products-wrapper">
                        <div id="ajCartProducts">
                            <div class="text-center py-5">
                                <span class="spinner-border spinner-border-sm"></span>
                                <p class="mt-2 mb-0">Loading your cart...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TRUST INFORMATION -->
                <div class="aj-cart-trust mt-3">
                    <div>
                        <i class="bi bi-shield-check"></i>
                        <span>Secure Shopping</span>
                    </div>
                    <div>
                        <i class="bi bi-truck"></i>
                        <span>Reliable Delivery</span>
                    </div>
                    <div>
                        <i class="bi bi-headset"></i>
                        <span>Customer Support</span>
                    </div>
                </div>

            </div>

            <!-- CART SUMMARY -->
            <div class="col-lg-4">

                
                <div class="aj-cart-card aj-summary-card" id="ajCartSummarySection">

                    <div class="aj-summary-header">
                        <div>
                            <h5>Your Price Summary</h5>
                            <small>Review your savings and payment</small>
                        </div>
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>

                    <!-- PRODUCT PRICE COMPARISON -->
                    <div class="aj-summary-price-grid">

                        <div class="aj-summary-price-box">
                            <span>Total MRP</span>
                            <strong id="ajCartMrp">₹0.00</strong>
                        </div>

                        <div class="aj-summary-price-box">
                            <span>Product Subtotal</span>
                            <strong id="ajCartSubtotal">₹0.00</strong>
                        </div>

                    </div>

                    <!-- TOTAL SAVINGS -->
                    <div class="aj-summary-savings">
                        <div class="aj-savings-icon">
                            <i class="bi bi-piggy-bank-fill"></i>
                        </div>

                        <div>
                            <strong>You are saving</strong>
                            <small>Including all product discounts</small>
                        </div>

                        <strong id="ajCartTotalSavings">₹0.00</strong>
                    </div>

                    <!-- WOW OFFER -->
                    <div class="aj-wow-summary-row"
                        id="ajCartWowDiscountRow"
                        style="display:none;">

                        <div class="aj-wow-summary-top">
                            <div>
                                <i class="bi bi-lightning-charge-fill"></i>
                                <strong>WOW SPECIAL OFFER</strong>
                            </div>
                            <span id="ajCartWowPercent">20% OFF</span>
                        </div>

                        <div class="aj-wow-summary-bottom">
                            <small>Extra discount on eligible products</small>
                            <strong id="ajCartWowDiscount">-₹0.00</strong>
                        </div>
                    </div>

                    <!-- ADDITIONAL CHARGES -->
                    <div class="aj-additional-charges">

                        <div class="aj-charges-heading">
                            <strong>Additional Charges</strong>
                        </div>

                        <div class="aj-charge-item">
                            <div class="aj-charge-label">
                                <i class="bi bi-truck"></i>
                                <span>Delivery Fee</span>
                            </div>
                            <strong id="ajCartDeliveryFee">Not calculated</strong>
                        </div>

                        <div class="aj-charge-item">
                            <div class="aj-charge-label">
                                <i class="bi bi-wallet2"></i>
                                <span>Cashback Used</span>
                            </div>
                            <strong id="ajCartCouponDiscount"
                                    class="aj-positive-amount">-₹0.00</strong>
                        </div>

                        <div class="aj-charge-item">
                            <div class="aj-charge-label">
                                <i class="bi bi-shop"></i>
                                <span>Platform Fee</span>
                            </div>
                            <strong id="ajCartPlatformFee">₹5.00</strong>
                        </div>

                    </div>

                    <!-- FINAL AMOUNT -->
                    <div class="aj-final-payment">

                        <div class="aj-final-payment-info">
                            <div>
                                <strong>You Pay</strong>
                                <small>Inclusive of applicable charges</small>
                            </div>

                            <strong id="ajCartTotal">₹0.00</strong>
                        </div>

                        <button type="button"
                                id="ajCartCheckout"
                                class="aj-checkout-button">

                            Proceed to Checkout
                            <i class="bi bi-arrow-right-circle-fill"></i>

                        </button>

                        <div class="aj-payment-note">
                            <i class="bi bi-shield-lock-fill"></i>
                            Secure and protected payment
                        </div>

                    </div>

                </div>


                <div class="aj-help-card mt-3">
                    <i class="bi bi-question-circle-fill"></i>
                    <div>
                        <strong>Need help?</strong>
                        <p class="mb-0">Our support team is here to assist you.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
