<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CartModel;

class Checkout extends BaseController
{
    protected $cartModel;
    protected $layout = 'layouts/default_checkout';


    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    public function index()
    {
        $customerId = (int) session()->get('ajobmart_customer_db_id');

        // Checkout requires customer login.
        if (
            $customerId <= 0 ||
            ! session()->get('ajobmart_customer_logged_in')
        ) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in to continue checkout.');
        }

        // Retrieve cart from the existing cart model.
        $cartItems = $this->cartModel->getCustomerCart($customerId);

        if (empty($cartItems)) {
            return redirect()->to(site_url('/'))
                ->with('error', 'Your cart is empty.');
        }

        // Use the selected saved address, if supplied.
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

        // return view('checkout/index', [
        //     'cartItems'        => $cartItems,
        //     'address'          => $address,
        //     'subtotal'         => round($subtotal, 2),
        //     'includedShipping' => round($includedShipping, 2),
        // ]);

        return $this->view('checkout/index', [
            'cartItems'        => $cartItems,
            'address'          => $address,
            'subtotal'         => round($subtotal, 2),
            'includedShipping' => round($includedShipping, 2),
        ]);
    }


    public function placeOrder()
    {
        // 1. Keep your existing customer login check here.

        // 2. Validate payment method and gateway.
        $paymentMethod = $this->request->getPost('payment_method');
        $paymentGateway = $this->request->getPost('payment_gateway');

        if ($paymentMethod === 'online') {
            $allowedGateways = ['razorpay', 'cashfree', 'payu'];

            if (!in_array($paymentGateway, $allowedGateways, true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please select an online payment gateway.');
            }
        } elseif ($paymentMethod !== 'cod') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select a valid payment method.');
        }

        // 3. Continue your existing checkout logic here:
        // Validate address, cart, stock, and prices.
        // Calculate shipping and total.
        // Create the order and order items.
    }
}
