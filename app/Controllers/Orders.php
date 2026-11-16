<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $db;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->db = db_connect();
    }

    private function customerId(): int
    {
        if (!session('ajobmart_customer_logged_in')) {
            return 0;
        }

        return (int) session('ajobmart_customer_db_id');
    }

    private function requireCustomer()
    {
        $customerId = $this->customerId();

        if ($customerId <= 0) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in to view your orders.');
        }

        return $customerId;
    }

    /**
     * Customer order list.
     */
    public function index()
    {
        $customerId = $this->requireCustomer();

        if ($customerId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $customerId;
        }

        $orders = $this->db->table('ajobmart_orders')
            ->where('user_id', $customerId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($orders as &$order) {
            $orderId = (int) $order['id'];

            $order['items'] = $this->orderModel
                ->getOrderItems($orderId);

            $order['shipments'] = $this->orderModel
                ->getOrderShipments($orderId);

            $order['item_count'] = count($order['items']);

            $order['total_quantity'] = array_sum(
                array_map(
                    static fn($item) => (int) ($item['quantity'] ?? 0),
                    $order['items']
                )
            );

            $order['cancellation_request'] = $this->getCancellationRequest(
                $orderId,
                $customerId
            );
        }

        unset($order);

        return $this->view('orders/index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Complete order details.
     */
    public function details($orderId)
    {
        $customerId = $this->requireCustomer();

        if ($customerId instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $customerId;
        }

        $orderId = (int) $orderId;

        $order = $this->orderModel->getCustomerOrder(
            $orderId,
            $customerId
        );

        if (!$order) {
            return redirect()->to(site_url('my-orders'))
                ->with('error', 'Order not found.');
        }

        $items = $this->orderModel->getOrderItems($orderId);

        $shipments = $this->orderModel->getOrderShipments($orderId);

        // Delivery address belonging to this customer.
        $address = $this->db->table('ajobmart_customer_addresses')
            ->where('id', (int) $order['address_id'])
            ->where('customer_id', $customerId)
            ->get()
            ->getRowArray();

        // Payment attempts.
        $payments = $this->db->table('ajobmart_order_payments')
            ->where('order_id', $orderId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        // Seller tracking information.
        $fulfillments = $this->db
            ->table('ajobmart_seller_order_fulfillments')
            ->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        // Seller shipping labels.
        $labels = $this->db->table('ajobmart_seller_order_labels')
            ->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        // India Post booking and tracking information.
        $bookings = $this->db
            ->table('ajobmart_shipping_label_bookings')
            ->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $cancellationRequest = $this->getCancellationRequest(
            $orderId,
            $customerId
        );

        return $this->view('orders/details', [
            'order'               => $order,
            'items'               => $items,
            'address'             => $address,
            'payments'            => $payments,
            'shipments'           => $shipments,
            'fulfillments'        => $fulfillments,
            'labels'              => $labels,
            'bookings'            => $bookings,
            'cancellationRequest' => $cancellationRequest,
        ]);
    }

    /**
     * Submit a cancellation request.
     * This does NOT cancel the order.
     */
    public function requestCancellation($orderId)
    {
        $customerId = $this->customerId();

        if ($customerId <= 0) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in first.');
        }

        if (!$this->request->is('post')) {
            return redirect()->to(site_url('my-orders'));
        }

        $orderId = (int) $orderId;

        $order = $this->orderModel->getCustomerOrder(
            $orderId,
            $customerId
        );

        if (!$order) {
            return redirect()->to(site_url('my-orders'))
                ->with('error', 'Order not found.');
        }

        $reason = trim((string) $this->request->getPost('reason'));

        if ($reason === '') {
            return redirect()->back()
                ->with('error', 'Please select or enter a cancellation reason.');
        }

        if (mb_strlen($reason) > 2000) {
            return redirect()->back()
                ->with('error', 'Cancellation reason is too long.');
        }

        // Prevent duplicate pending requests.
        $existing = $this->db
            ->table('ajobmart_order_cancellation_requests')
            ->where('order_id', $orderId)
            ->where('customer_id', $customerId)
            ->where('request_status', 'pending')
            ->get()
            ->getRowArray();

        if ($existing) {
            return redirect()->to(site_url('my-orders/' . $orderId))
                ->with('info', 'Your cancellation request is already awaiting admin approval.');
        }

        $inserted = $this->db
            ->table('ajobmart_order_cancellation_requests')
            ->insert([
                'order_id'       => $orderId,
                'customer_id'    => $customerId,
                'reason'         => $reason,
                'request_status' => 'pending',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);

        if (!$inserted) {
            log_message(
                'error',
                'Cancellation request failed for order ' . $orderId
            );

            return redirect()->back()
                ->with('error', 'Unable to submit your request. Please try again.');
        }

        return redirect()->to(site_url('my-orders/' . $orderId))
            ->with('success', 'Your cancellation request has been submitted. Awaiting admin approval.');
    }

    /**
     * Get the latest cancellation request for this customer/order.
     */
    private function getCancellationRequest(
        int $orderId,
        int $customerId
    ): ?array {
        return $this->db
            ->table('ajobmart_order_cancellation_requests')
            ->where('order_id', $orderId)
            ->where('customer_id', $customerId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();
    }
}
