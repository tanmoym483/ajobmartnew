<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Libraries\ShippingQuoteService;


use App\Models\OrderModel;

use App\Models\CartModel;

class Checkout extends BaseController
{
    protected $cartModel;
    protected $orderModel;
    protected $layout = 'layouts/default_checkout';


    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
    }

    
    private function razorpayApi(): Api
    {
        return new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );
    }


    
    public function index()
    {
        $customerId = (int) session()->get('ajobmart_customer_db_id');

        if (
            $customerId <= 0 ||
            ! session()->get('ajobmart_customer_logged_in')
        ) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in to continue checkout.');
        }

        $cartItems = $this->cartModel->getCustomerCart($customerId);

        if (empty($cartItems)) {
            return redirect()->to(site_url('/'))
                ->with('error', 'Your cart is empty.');
        }

        $addressId = (int) $this->request->getGet('address_id');

        $addressQuery = $this->db
            ->table('ajobmart_customer_addresses')
            ->where('customer_id', $customerId);

        if ($addressId > 0) {
            $addressQuery->where('id', $addressId);
        } else {
            $addressQuery
                ->orderBy('is_default', 'DESC')
                ->orderBy('id', 'DESC');
        }

        $address = $addressQuery->get()->getRowArray();

        if (! $address) {
            return redirect()->to(site_url('/'))
                ->with('error', 'Please select a valid delivery address.');
        }

        // Product subtotal and shipping allowance
        $subtotal = 0.0;
        $includedShipping = 0.0;

        foreach ($cartItems as $item) {
            $quantity = max(0, (int) ($item['quantity'] ?? 0));
            $price = (float) ($item['listing_price'] ?? 0);
            $allowance = (float) ($item['shipping_allowance'] ?? 0);

            if ($quantity <= 0 || $price < 0 || $allowance < 0) {
                return redirect()->to(site_url('/'))
                    ->with('error', 'Your cart contains an invalid item.');
            }

            $subtotal += $price * $quantity;
            $includedShipping += $allowance * $quantity;
        }

        $user = $this->db
            ->table('ajobmart_users')
            ->select('total_coupon_amount')
            ->where('id', $customerId)
            ->get()
            ->getRowArray();

        $availableCashback = max(
            0,
            (float) ($user['total_coupon_amount'] ?? 0)
        );

        // 4. CALCULATE CASHBACK DISCOUNT

        $couponDiscount = $subtotal >= 100
            ? min($availableCashback, $subtotal / 6)
            : 0;

        $remainingCashback = max(
            0,
            $availableCashback - $couponDiscount
        );

        // Coupon discount
        $breakdown = session()->get('AJ_CART_BREAKDOWN') ?? [];

        // $couponDiscount = min(
        //     $subtotal,
        //     max(0, (float) ($breakdown['couponDiscount'] ?? 0))
        // );

        // Platform fee
        $platformFee = max(
            0,
            (float) ($breakdown['platformFee'] ?? 5)
        );

        // Shipping needs to be recalculated for this address.
        // Replace this placeholder with your ShippingModel quote result.
        $actualShipping = 0.00;

        $deliveryFee = max(
            0,
            $actualShipping - $includedShipping
        );

        $finalTotal = max(
            0,
            $subtotal +
            $deliveryFee +
            $platformFee -
            $couponDiscount
        );

        return $this->view('checkout/index', [
            'cartItems'        => $cartItems,
            'address'          => $address,
            'subtotal'         => round($subtotal, 2),
            'includedShipping' => round($includedShipping, 2),
            'actualShipping'   => round($actualShipping, 2),
            'deliveryFee'      => round($deliveryFee, 2),
            'couponDiscount'   => round($couponDiscount, 2),
            'platformFee'      => round($platformFee, 2),
            'finalTotal'       => round($finalTotal, 2),
        ]);
    }



    

public function placeOrder()
{
    $customerId = (int) session('ajobmart_customer_db_id');

    if (
        $customerId <= 0 ||
        !session('ajobmart_customer_logged_in')
    ) {
        return redirect()->to(site_url('/'))
            ->with('error', 'Please log in before placing your order.');
    }

    $paymentMethod = (string) $this->request->getPost('payment_method');
    $paymentGateway = $this->request->getPost('payment_gateway');

    // Only Razorpay is implemented for online payments at this stage.
    if ($paymentMethod === 'online') {
        if ($paymentGateway !== 'razorpay') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Currently, Razorpay is the supported online payment gateway.');
        }
    } elseif ($paymentMethod === 'cod') {
        $paymentGateway = null;
    } else {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Invalid payment method.');
    }

    $addressId = (int) $this->request->getPost('address_id');

    if ($addressId <= 0) {
        return redirect()->back()
            ->with('error', 'Please select a delivery address.');
    }

    // Load the customer's real cart.
    $cartItems = $this->cartModel->getCustomerCart($customerId);

    if (empty($cartItems)) {
        return redirect()->to(site_url('checkout'))
            ->with('error', 'Your cart is empty.');
    }

    // Validate cart quantities and calculate product totals from listing_price.
    $subtotal = 0.0;
    $includedShipping = 0.0;

    foreach ($cartItems as $item) {
        $quantity = (int) ($item['quantity'] ?? 0);
        $price = (float) ($item['listing_price'] ?? 0);
        $allowance = (float) ($item['shipping_allowance'] ?? 0);
        $stock = (int) ($item['stock_quantity'] ?? 0);
        $minimum = max(1, (int) ($item['min_order_quantity'] ?? 1));

        if (
            $quantity < $minimum ||
            $price < 0 ||
            $allowance < 0 ||
            $stock < $quantity
        ) {
            return redirect()->back()
                ->with('error', 'A product in your cart has an invalid quantity or insufficient stock.');
        }

        // if (
        //     isset($item['status']) &&
        //     strtolower((string) $item['status']) !== 'active'
        // ) {
        //     return redirect()->back()
        //         ->with('error', 'A product in your cart is no longer available.');
        // }

        // if (
        //     isset($item['approval_status']) &&
        //     strtolower((string) $item['approval_status']) !== 'approved'
        // ) {
        //     return redirect()->back()
        //         ->with('error', 'A product in your cart is not approved for purchase.');
        // }

        $subtotal += $price * $quantity;
        $includedShipping += $allowance * $quantity;
    }

    $subtotal = round($subtotal, 2);
    $includedShipping = round($includedShipping, 2);

    if ($subtotal <= 0) {
        return redirect()->back()
            ->with('error', 'Invalid order subtotal.');
    }

    // Verify the address belongs to this customer.
    $address = $this->db
        ->table('ajobmart_customer_addresses')
        ->where('id', $addressId)
        ->where('customer_id', $customerId)
        ->get()
        ->getRowArray();

    if (!$address) {
        return redirect()->back()
            ->with('error', 'Invalid delivery address.');
    }

    // Recalculate shipping on the server using the saved address PIN.
    $shippingService = new ShippingQuoteService();

    $shippingResult = $shippingService->calculate(
        $cartItems,
        (string) $address['pincode']
    );


    if (empty($shippingResult['success'])) {
        return redirect()->back()
            ->with(
                'error',
                $shippingResult['message'] ?? 'Unable to calculate shipping.'
            );
    }

    $shippingCharge = round(
        (float) $shippingResult['total_customer_shipping'],
        2
    );

    $actualShipping = round(
        (float) $shippingResult['total_actual_shipping'],
        2
    );

    // Coupon discount
    $user = $this->db
        ->table('ajobmart_users')
        ->select('total_coupon_amount')
        ->where('id', $customerId)
        ->get()
        ->getRowArray();

    $availableCashback = max(
        0,
        (float) ($user['total_coupon_amount'] ?? 0)
    );

    // 4. CALCULATE CASHBACK DISCOUNT

    $couponDiscount = $subtotal >= 100
        ? min($availableCashback, $subtotal / 6)
        : 0;

    $remainingCashback = max(
        0,
        $availableCashback - $couponDiscount
    );

    // Platform fee
    $platformFee = 5.00;

    // Final payable amount
    $totalAmount = round(
        $subtotal +
        $shippingCharge +
        $platformFee -
        $couponDiscount,
        2
    );

    if ($totalAmount <= 0) {
        return redirect()->back()
            ->with('error', 'Invalid order amount.');
    }

    $orderNumber = 'AJM-' . date('Ymd') . '-'
        . strtoupper(bin2hex(random_bytes(4)));

    $orderData = [
        'order_number'       => $orderNumber,
        'user_id'            => $customerId,
        'address_id'         => $addressId,
        'subtotal'           => $subtotal,
        'shipping_charge'    => $shippingCharge,
        'actual_shipping'    => $actualShipping,
        'included_shipping'  => $includedShipping,
        'cashback_deduct'    => $couponDiscount,
        'platform_fee'       => $platformFee,
        'total_amount'       => $totalAmount,
        'payment_method'     => $paymentMethod,
        'payment_gateway'    => $paymentGateway,
        'payment_status'     => 'pending',
        'order_status' => $paymentMethod === 'cod'
            ? 'confirmed'
            : 'pending_payment',
    ];

    // echo '<pre>'; print_r($shippingResult['seller_shipments']); die;


    $orderId = $this->orderModel->createOrderWithItems(
        $orderData,
        $cartItems,
        $shippingResult['seller_shipments']
    );



    if (!$orderId) {
        return redirect()->back()
            ->with('error', 'Unable to create your order. Please try again.');
    }

    // COD: confirm the order and clear the cart.
    if ($paymentMethod === 'cod') {
        $this->db->transBegin();

        try {
            $updated = $this->orderModel->updateOrder($orderId, [
                'payment_status' => 'pending',
                'order_status' => 'confirmed',
            ]);

            if (!$updated || !$this->cartModel->clearCustomerCart($customerId)) {
                throw new \RuntimeException('Unable to confirm COD order.');
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('COD order transaction failed.');
            }

            $this->db->transCommit();

            return redirect()->to(site_url('my-orders'))
                ->with('success', 'Your order has been placed successfully.');
        } catch (\Throwable $e) {
            $this->db->transRollback();

            log_message('error', 'COD order confirmation failed: ' . $e->getMessage());

            return redirect()->to(site_url('checkout'))
                ->with('error', 'Unable to confirm your COD order.');
        }
    }

    // Razorpay: create the gateway order.
    try {
        $keyId = trim((string) env('RAZORPAY_KEY_ID'));
        $keySecret = trim((string) env('RAZORPAY_KEY_SECRET'));

        if ($keyId === '' || $keySecret === '') {
            throw new \RuntimeException('Razorpay credentials are missing.');
        }

        $amountInPaise = (int) round($totalAmount * 100);

        $razorpayOrder = $this->razorpayApi()->order->create([
            'receipt' => $orderNumber,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'notes' => [
                'internal_order_id' => (string) $orderId,
                'order_number' => $orderNumber,
            ],
        ]);

        $razorpayOrderId = (string) $razorpayOrder['id'];

        if ($razorpayOrderId === '') {
            throw new \RuntimeException('Razorpay did not return an order ID.');
        }

        $this->db->transBegin();

        $updated = $this->orderModel->updateOrder($orderId, [
            'razorpay_order_id' => $razorpayOrderId,
        ]);

        $paymentAttemptId = $this->orderModel->addPaymentAttempt([
            'order_id' => $orderId,
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => $razorpayOrderId,
            'amount' => $totalAmount,
            'currency' => 'INR',
            'payment_status' => 'pending',
        ]);

        if (
            !$updated ||
            !$paymentAttemptId ||
            $this->db->transStatus() === false
        ) {
            throw new \RuntimeException('Unable to save Razorpay payment details.');
        }

        $this->db->transCommit();

        return $this->view('checkout/razorpay', [
            'keyId' => $keyId,
            'razorpayOrderId' => $razorpayOrderId,
            'internalOrderId' => $orderId,
            'paymentAttemptId' => $paymentAttemptId,
            'amount' => $amountInPaise,
            'customerName' => session('ajobmart_customer_name'),
            'customerEmail' => session('ajobmart_customer_email'),
            'customerMobile' => session('ajobmart_customer_mobile'),
        ]);
    } catch (\Throwable $e) {

        // Roll back any active Razorpay database transaction.
        if ($this->db->transStatus() !== false) {
            $this->db->transRollback();
        }

        // Cancel the unpaid order and restore deducted stock.
        $restored = $this->orderModel->cancelUnpaidOrderAndRestoreStock(
            (int) $orderId,
            'failed'
        );

        if (!$restored) {
            log_message(
                'error',
                'Unable to restore stock for Razorpay order ID: ' . $orderId
            );
        }

        log_message(
            'error',
            'Razorpay initiation failed for order '
            . $orderId . ': ' . $e->getMessage()
        );

        return redirect()->to(site_url('checkout'))
            ->with('error', 'Unable to initiate Razorpay payment. Please try again.');
    }
}


public function verifyRazorpayPayment()
{
    $customerId = (int) session('ajobmart_customer_db_id');

    if (
        $customerId <= 0 ||
        !session('ajobmart_customer_logged_in')
    ) {
        return redirect()->to(site_url('login'))
            ->with('error', 'Please log in to verify your payment.');
    }

    $paymentId = trim((string) $this->request->getPost('razorpay_payment_id'));
    $razorpayOrderId = trim((string) $this->request->getPost('razorpay_order_id'));
    $signature = trim((string) $this->request->getPost('razorpay_signature'));
    $internalOrderId = (int) $this->request->getPost('internal_order_id');

    if (
        $paymentId === '' ||
        $razorpayOrderId === '' ||
        $signature === '' ||
        $internalOrderId <= 0
    ) {
        return redirect()->to(site_url('checkout/payment-failed'))
            ->with('error', 'Invalid payment response.');
    }

    $order = $this->orderModel->getCustomerOrder(
        $internalOrderId,
        $customerId
    );

    if (!$order) {
        return redirect()->to(site_url('checkout/payment-failed'))
            ->with('error', 'Order not found.');
    }

    // Do not accept a gateway order ID supplied only by the browser.
    if (
        empty($order['razorpay_order_id']) ||
        !hash_equals(
            (string) $order['razorpay_order_id'],
            $razorpayOrderId
        )
    ) {
        return redirect()->to(site_url('checkout/payment-failed'))
            ->with('error', 'Payment order mismatch.');
    }

    // Find the matching payment attempt saved on the server.
    $paymentAttempt = $this->db
        ->table('ajobmart_order_payments')
        ->where('order_id', $internalOrderId)
        ->where('payment_gateway', 'razorpay')
        ->where('gateway_order_id', $razorpayOrderId)
        ->orderBy('id', 'DESC')
        ->get()
        ->getRowArray();

    if (!$paymentAttempt) {
        return redirect()->to(site_url('checkout/payment-failed'))
            ->with('error', 'Payment attempt not found.');
    }

    // Idempotency: don't process a previously paid order again.
    if (
        ($order['payment_status'] ?? '') === 'paid' &&
        !empty($order['razorpay_payment_id']) &&
        hash_equals((string) $order['razorpay_payment_id'], $paymentId)
    ) {
        return redirect()->to(site_url('checkout/payment-success'));
    }

    try {
        $api = $this->razorpayApi();

        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);

        // Fetch the payment from Razorpay, not from browser-submitted values.
        $payment = $api->payment->fetch($paymentId);

        $gatewayPaymentOrderId = (string) ($payment['order_id'] ?? '');
        $gatewayAmount = (int) ($payment['amount'] ?? 0);
        $gatewayCurrency = strtoupper((string) ($payment['currency'] ?? ''));
        $gatewayStatus = strtolower((string) ($payment['status'] ?? ''));

        $expectedAmount = (int) round(
            ((float) $order['total_amount']) * 100
        );

        if (!hash_equals($razorpayOrderId, $gatewayPaymentOrderId)) {
            throw new \RuntimeException('Payment belongs to another Razorpay order.');
        }

        if ($gatewayAmount !== $expectedAmount) {
            throw new \RuntimeException('Payment amount mismatch.');
        }

        if ($gatewayCurrency !== 'INR') {
            throw new \RuntimeException('Payment currency mismatch.');
        }

        if ($gatewayStatus !== 'captured') {
            throw new \RuntimeException('Payment has not been captured.');
        }

        // Update order, payment attempt and cart in one database transaction.
        $this->db->transBegin();

        try {

        // 1. GET CASHBACK DEDUCTION SAVED IN THIS ORDER

        $cashbackDeduct = round(
            max(0, (float) ($order['cashback_deduct'] ?? 0)),
            2
        );

        // 2. DEDUCT CASHBACK ONLY AFTER SUCCESSFUL PAYMENT

        if ($cashbackDeduct > 0) {

            $cashbackBalanceAfter = $this->deductOrderCashback(
                $order,
                $customerId,
                $paymentId
            );
        }


        // 3. UPDATE ORDER STATUS

        $orderUpdated = $this->orderModel->updateOrder($internalOrderId, [
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'razorpay_payment_id' => $paymentId,
        ]);

        // 4. UPDATE PAYMENT ATTEMPT

        $attemptUpdated = $this->orderModel->updatePaymentAttempt(
            (int) $paymentAttempt['id'],
            [
                'gateway_payment_id' => $paymentId,
                'payment_status' => 'paid',
                'failure_reason' => null,
            ]
        );

        // 5. CLEAR CUSTOMER CART

        $cartCleared = $this->cartModel->clearCustomerCart($customerId);

        if (
            !$orderUpdated ||
            !$attemptUpdated ||
            !$cartCleared ||
            $this->db->transStatus() === false
        ) {
            throw new \RuntimeException(
                'Unable to finalize paid order.'
            );
        }

        // 6. COMMIT EVERYTHING

        $this->db->transCommit();

        // Update cashback session only when cashback was deducted.

        if ($cashbackDeduct > 0) {

            session()->set(
                'ajobmart_customer_coupon_amount',
                $cashbackBalanceAfter
            );
        }

        return redirect()->to(site_url('checkout/payment-success'));
            } catch (\Throwable $e) {

        $this->db->transRollback();

        throw $e;
    }
    } catch (\Throwable $e) {
        $this->db->transRollback();

        log_message(
            'error',
            'Razorpay verification failed for order '
            . $internalOrderId . ': ' . $e->getMessage()
        );

        return redirect()->to(site_url('checkout/payment-failed'))
            ->with('error', 'Payment verification failed. If money was deducted, please contact support.');
    }
}


/**
 * Deduct cashback and save its transaction history.
 *
 * IMPORTANT:
 * Call this method only inside an active database transaction.
 *
 * @return float Updated cashback balance.
 */
private function deductOrderCashback(
    array $order,
    int $customerId,
    ?string $paymentId = null
): float {
    $orderId = (int) ($order['id'] ?? 0);

    $amount = round(
        max(0, (float) ($order['cashback_deduct'] ?? 0)),
        2
    );

    if ($orderId <= 0 || $customerId <= 0) {
        throw new \RuntimeException('Invalid cashback transaction reference.');
    }

    // No cashback deduction required.
    if ($amount <= 0) {
        return 0.00;
    }

    // Prevent duplicate cashback deductions for this order.
    $existingTransaction = $this->db
        ->table('ajobmart_cashback_transactions')
        ->where('order_id', $orderId)
        ->where('transaction_type', 'debit')
        ->get()
        ->getRowArray();

    if ($existingTransaction) {
        return (float) $existingTransaction['balance_after'];
    }

    // Lock the customer's cashback balance.
    $user = $this->db->query(
        "SELECT total_coupon_amount
         FROM ajobmart_users
         WHERE id = ?
         FOR UPDATE",
        [$customerId]
    )->getRowArray();

    if (!$user) {
        throw new \RuntimeException('Customer cashback account not found.');
    }

    $balanceBefore = round(
        max(0, (float) $user['total_coupon_amount']),
        2
    );

    if ($balanceBefore < $amount) {
        throw new \RuntimeException('Insufficient cashback balance.');
    }

    $balanceAfter = round($balanceBefore - $amount, 2);

    // Update the customer's cashback balance.
    $updated = $this->db
        ->table('ajobmart_users')
        ->where('id', $customerId)
        ->update([
            'total_coupon_amount' => $balanceAfter
        ]);

    if (!$updated || $this->db->transStatus() === false) {
        throw new \RuntimeException('Unable to update cashback balance.');
    }

    // Insert cashback transaction history.
    $inserted = $this->db
        ->table('ajobmart_cashback_transactions')
        ->insert([
            'user_id'            => $customerId,
            'order_id'           => $orderId,
            'order_number'       => $order['order_number'],
            'transaction_type'   => 'debit',
            'amount'             => $amount,
            'balance_before'     => $balanceBefore,
            'balance_after'      => $balanceAfter,
            'payment_gateway'    => $order['payment_gateway'] ?? null,
            'gateway_payment_id' => $paymentId,
            'description'        => 'Cashback redeemed against order ' .
                                    $order['order_number'],
        ]);

    if (!$inserted || $this->db->transStatus() === false) {
        throw new \RuntimeException('Unable to save cashback transaction.');
    }

    return $balanceAfter;
}


public function paymentSuccess()
{
    return view('checkout/payment_success');
}

public function paymentFailed()
{
    return view('checkout/payment_failed');
}


}
