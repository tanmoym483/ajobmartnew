<?php

namespace App\Controllers;

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

    public function index()
    {
        $customerId = (int) session('ajobmart_customer_db_id');

        if (
            $customerId <= 0 ||
            !session('ajobmart_customer_logged_in')
        ) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in to view your orders.');
        }

        $orders = $this->db
            ->table('ajobmart_orders')
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
        }

        unset($order);

        return $this->view('orders/index', [
            'orders' => $orders,
        ]);
    }
}
