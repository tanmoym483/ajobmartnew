<?php

namespace App\Controllers;
use App\Controllers\BaseController;


use App\Models\ProductModel;

class HomeProducts extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    private function jsonProducts(array $products)
    {
        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status'   => true,
                'products' => $products
            ]);
    }

    public function popular()
    {
        return $this->jsonProducts(
            $this->productModel->getPopularProducts()
        );
    }

    public function newArrivals()
    {
        return $this->jsonProducts(
            $this->productModel->getNewArrivals()
        );
    }

    public function bestDeals()
    {
        return $this->jsonProducts(
            $this->productModel->getBestDeals()
        );
    }

    public function trending()
    {
        return $this->jsonProducts(
            $this->productModel->getTrendingProducts()
        );
    }

    public function byCategory($categoryId)
    {
        return $this->jsonProducts(
            $this->productModel->getProductsByCategory(
                (int) $categoryId
            )
        );
    }
}