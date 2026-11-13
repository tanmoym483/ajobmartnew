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


    public function under99()
    {
        return $this->jsonProducts(
            $this->productModel->getProductsUnder99()
        );
    }

    public function under199()
    {
        return $this->jsonProducts(
            $this->productModel->getProductsUnder199()
        );
    }

    public function discount30()
    {
        return $this->jsonProducts(
            $this->productModel->getProductsDiscount30()
        );
    }

    public function discount50()
    {
        return $this->jsonProducts(
            $this->productModel->getProductsDiscount50()
        );
    }


    public function moreProducts()
    {
        $page = max(1, (int) $this->request->getGet('page'));

        // First request: 12 products.
        // Subsequent requests: 6 products each.
        $limit = ($page === 1) ? 12 : 6;

        // Calculate database offset.
        $offset = ($page === 1)
            ? 0
            : 12 + (($page - 2) * 6);

        // Fetch one extra product to check if more products exist.
        $products = $this->productModel->getMoreProducts(
            $limit + 1,
            $offset
        );

        $hasMore = count($products) > $limit;

        // Return only the requested number.
        $products = array_slice($products, 0, $limit);

        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status'   => true,
                'products' => $products,
                'has_more' => $hasMore,
                'page'     => $page
            ]);
    }


}