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

        return $this->view('products/details', [
            'product'    => $product,
            'images'     => $images,
            'attributes' => $attributes,
        ]);
    }
}