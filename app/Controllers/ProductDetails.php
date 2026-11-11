<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProductDetails extends BaseController
{
    protected $layout = 'layouts/default';

    
    public function index($id)
    {
        $id = (int) $id;

        // Fetch product details.
        $product = $this->db
            ->table('ajobmart_products')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Product not found.'
            );
        }

        // Fetch all product images.
        $images = $this->db
            ->table('ajobmart_product_images')
            ->where('product_id', $id)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        // Fetch product attribute values and their attribute labels.
        $attributes = $this->db
            ->table('ajobmart_product_attribute_values pav')
            ->select('pav.attribute_id, pav.attribute_value')
            ->select('a.attribute_name, a.label')
            ->join(
                'ajobmart_category_attributes a',
                'a.id = pav.attribute_id',
                'left'
            )
            ->where('pav.product_id', $id)
            ->get()
            ->getResultArray();

        // Fetch saved WOW offer status for logged-in customer.
        $wowOfferApplied = 0;

        if (session()->get('ajobmart_customer_logged_in')) {
            $customerId = (int) session()->get('ajobmart_customer_db_id');

            if ($customerId > 0) {
                $cartItem = $this->db
                    ->table('ajobmart_cart_items')
                    ->select('wow_offer_applied')
                    ->where('customer_id', $customerId)
                    ->where('product_id', $id)
                    ->get()
                    ->getRowArray();

                $wowOfferApplied = (int) ($cartItem['wow_offer_applied'] ?? 0);
            }
        }

        return $this->view('products/details', [
            'product'         => $product,
            'images'          => $images,
            'attributes'      => $attributes,
            'wowOfferApplied' => $wowOfferApplied,
        ]);
    }

}