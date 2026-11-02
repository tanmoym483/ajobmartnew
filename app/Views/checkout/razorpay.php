
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Payment | Ajobmart</title>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
            background:
                radial-gradient(circle at 10% 10%, #dbeafe 0, transparent 35%),
                radial-gradient(circle at 90% 90%, #e0e7ff 0, transparent 35%),
                #f4f7fc;
            font-family: 'Inter', Arial, sans-serif;
            color: #172033;
        }

        .payment-wrapper {
            width: 100%;
            max-width: 465px;
        }

        .brand {
            text-align: center;
            margin-bottom: 22px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
            color: #1769e0;
        }

        .brand-icon {
            width: 39px;
            height: 39px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1769e0, #6448e8);
            color: white;
            border-radius: 12px;
            font-size: 22px;
        }

        .brand-tagline {
            margin-top: 7px;
            font-size: 12px;
            color: #7b8799;
            letter-spacing: .5px;
        }

        .payment-card {
            overflow: hidden;
            background: #fff;
            border-radius: 23px;
            box-shadow: 0 18px 55px rgba(31, 54, 95, .12);
            border: 1px solid rgba(226, 232, 240, .8);
        }

        .card-header {
            position: relative;
            overflow: hidden;
            padding: 29px 25px 27px;
            text-align: center;
            background: linear-gradient(135deg, #1769e0, #5746d9);
            color: white;
        }

        .card-header::before,
        .card-header::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
        }

        .card-header::before {
            width: 180px;
            height: 180px;
            top: -105px;
            right: -45px;
        }

        .card-header::after {
            width: 130px;
            height: 130px;
            bottom: -95px;
            left: -35px;
        }

        .secure-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.3);
            font-size: 32px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .card-header h2 {
            position: relative;
            margin: 0;
            font-size: 23px;
            font-weight: 800;
        }

        .card-header p {
            position: relative;
            margin: 9px 0 0;
            color: rgba(255,255,255,.82);
            font-size: 13px;
        }

        .card-body {
            padding: 27px;
        }

        .order-label {
            color: #8a96a8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .order-number {
            margin-top: 7px;
            font-size: 15px;
            font-weight: 700;
            color: #26354d;
        }

        .amount-box {
            margin: 24px 0;
            padding: 22px 15px;
            background: linear-gradient(135deg, #f0f6ff, #f6f4ff);
            border: 1px dashed #c7d7f5;
            border-radius: 16px;
            text-align: center;
        }

        .amount-label {
            font-size: 12px;
            font-weight: 600;
            color: #748198;
        }

        .amount {
            margin-top: 8px;
            font-size: 37px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: #1769e0;
        }

        .amount-note {
            margin-top: 7px;
            font-size: 11px;
            color: #8994a6;
        }

        .payment-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 13px 0;
            border-bottom: 1px solid #edf0f5;
            font-size: 12px;
        }

        .payment-info:last-of-type {
            border-bottom: 0;
        }

        .info-label {
            color: #8792a5;
        }

        .info-value {
            color: #34435b;
            font-weight: 700;
        }

        .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #16834c;
        }

        .pay-button {
            width: 100%;
            margin-top: 23px;
            padding: 16px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #1769e0, #5746d9);
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(23, 105, 224, .25);
            transition: .25s ease;
        }

        .pay-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(23, 105, 224, .32);
        }

        .pay-button:disabled {
            opacity: .65;
            cursor: wait;
            transform: none;
        }

        .button-icon {
            margin-right: 8px;
        }

        .security-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 18px;
            font-size: 11px;
            color: #8994a6;
            text-align: center;
        }

        .security-note span {
            color: #15945b;
            font-size: 15px;
        }

        .payment-message {
            display: none;
            margin-top: 15px;
            padding: 12px;
            border-radius: 9px;
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
        }

        .payment-message.show {
            display: block;
        }

        .payment-message.info {
            color: #2457a7;
            background: #edf5ff;
        }

        .payment-message.error {
            color: #b42318;
            background: #fff0ef;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #98a2b3;
            font-size: 11px;
            line-height: 1.8;
        }

        .footer strong {
            color: #64748b;
        }

        @media(max-width: 480px) {
            .payment-wrapper {
                max-width: 100%;
            }

            .card-body {
                padding: 22px 19px;
            }

            .card-header {
                padding: 25px 18px;
            }

            .amount {
                font-size: 33px;
            }
        }
    </style>
</head>

<body>

<div class="payment-wrapper">

    <div class="brand">
        <div class="brand-logo">
            <span class="brand-icon">A</span>
            <span>Ajobmart</span>
        </div>
        <div class="brand-tagline">
            SHOP SMART. LIVE BETTER.
        </div>
    </div>

    <div class="payment-card">

        <div class="card-header">
            <div class="secure-icon">🔐</div>
            <h2>Secure Checkout</h2>
            <p>You're just one step away from completing your order.</p>
        </div>

        <div class="card-body">

            <div class="order-label">Order Reference</div>

            <div class="order-number">
                #<?= esc($internalOrderId) ?>
            </div>

            <div class="amount-box">
                <div class="amount-label">Total Amount Payable</div>

                <div class="amount">
                    ₹<?= number_format($amount / 100, 2) ?>
                </div>

                <div class="amount-note">
                    Inclusive of applicable charges
                </div>
            </div>

            <div class="payment-info">
                <span class="info-label">Order ID</span>
                <span class="info-value">#<?= esc($internalOrderId) ?></span>
            </div>

            <div class="payment-info">
                <span class="info-label">Payment Method</span>
                <span class="info-value payment-method">
                    <span>●</span> Razorpay
                </span>
            </div>

            <div class="payment-info">
                <span class="info-label">Payment Status</span>
                <span class="info-value">Pending</span>
            </div>

            <button type="button" class="pay-button" id="ajPayNow">
                <span class="button-icon">🔒</span>
                Pay Securely Now
            </button>

            <div id="ajPaymentMessage" class="payment-message" role="status"></div>

            <div class="security-note">
                <span>✓</span>
                Secure & encrypted payment powered by Razorpay
            </div>

        </div>
    </div>

    <div class="footer">
        By proceeding, you agree to complete payment for this order.<br>
        © <?= date('Y') ?> <strong>Ajobmart</strong>. All rights reserved.
    </div>

</div>

<form
    id="ajRazorpayVerifyForm"
    method="post"
    action="<?= site_url('checkout/razorpay/verify') ?>"
    hidden
>
    <?= csrf_field() ?>

    <input type="hidden" name="internal_order_id"
           value="<?= esc($internalOrderId) ?>">

    <input type="hidden" name="razorpay_payment_id" id="ajRzpPaymentId">
    <input type="hidden" name="razorpay_order_id" id="ajRzpOrderId">
    <input type="hidden" name="razorpay_signature" id="ajRzpSignature">
</form>

<script>
(function () {
    'use strict';

    const payButton = document.getElementById('ajPayNow');
    const message = document.getElementById('ajPaymentMessage');
    const verifyForm = document.getElementById('ajRazorpayVerifyForm');

    function showMessage(text, type) {
        message.textContent = text;
        message.className = 'payment-message show ' + type;
    }

    payButton.addEventListener('click', function () {

        if (typeof Razorpay === 'undefined') {
            showMessage(
                'Unable to load the secure payment service. Please refresh and try again.',
                'error'
            );
            return;
        }

        payButton.disabled = true;
        payButton.innerHTML = '⏳ Opening secure payment...';

        showMessage('Connecting to Razorpay securely...', 'info');

        const options = {
            key: <?= json_encode($keyId) ?>,
            amount: <?= (int) $amount ?>,
            currency: 'INR',
            name: 'Ajobmart',
            description: 'Order #<?= esc($internalOrderId) ?>',
            order_id: <?= json_encode($razorpayOrderId) ?>,

            prefill: {
                name: <?= json_encode($customerName ?? '') ?>,
                email: <?= json_encode($customerEmail ?? '') ?>,
                contact: <?= json_encode($customerMobile ?? '') ?>
            },

            theme: {
                color: '#1769e0'
            },

            handler: function (response) {

                payButton.innerHTML = '⏳ Verifying Payment...';

                showMessage(
                    'Payment received. Please wait while we securely verify your transaction. Do not close this page.',
                    'info'
                );

                document.getElementById('ajRzpPaymentId').value =
                    response.razorpay_payment_id;

                document.getElementById('ajRzpOrderId').value =
                    response.razorpay_order_id;

                document.getElementById('ajRzpSignature').value =
                    response.razorpay_signature;

                verifyForm.submit();
            },

            modal: {
                ondismiss: function () {
                    payButton.disabled = false;
                    payButton.innerHTML = '<span class="button-icon">🔒</span> Pay Securely Now';

                    showMessage(
                        'Payment window closed. Your order is still pending payment. You can try again.',
                        'error'
                    );
                }
            }
        };

        try {
            const razorpay = new Razorpay(options);

            razorpay.on('payment.failed', function (response) {

                payButton.disabled = false;
                payButton.innerHTML = '<span class="button-icon">🔒</span> Try Payment Again';

                showMessage(
                    response.error?.description ||
                    'Payment failed. Please try again.',
                    'error'
                );
            });

            razorpay.open();

        } catch (error) {

            payButton.disabled = false;
            payButton.innerHTML = '<span class="button-icon">🔒</span> Pay Securely Now';

            showMessage(
                'Unable to start payment. Please try again.',
                'error'
            );

            console.error('Razorpay Error:', error);
        }
    });

})();
</script>

</body>
</html>
