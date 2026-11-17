<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
// use App\Models\AjobmartProductImageModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Files\UploadedFile;

class Product extends BaseController
{
    protected ProductModel $productModel;
    // protected ProductImageModel $imageModel;
    protected $layout = 'layouts/default';

    public function __construct()
    {
        $this->productModel = new ProductModel();
        // $this->imageModel   = new ProductImageModel();
    }

public function fetchpopularproduct()
{
    $products = $this->productModel->getSellerInventory();

    // echo '<pre>'; print_r($products); exit;

    return $this->response
        ->setContentType('application/json')
        ->setJSON([
            'status'   => true,
            'products' => $products
        ]);
}


/**
 * Customer product search results.
 * URL: /products/search?search=Neem
 */
public function searchResults()
{
    $search = trim((string) $this->request->getGet('search'));

    return $this->view('product/search', [
        'searchQuery' => $search,
    ]);
}

/**
 * Live product suggestions.
 * URL: /products/search-suggestions?q=Neem
 */
public function searchSuggestions()
{
    $search = trim((string) $this->request->getGet('q'));

    if (mb_strlen($search) < 2) {
        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status'   => true,
                'products' => [],
            ]);
    }

    $result = $this->productModel->searchCustomerProducts(
        $search,
        7,
        0
    );

    return $this->response
        ->setContentType('application/json')
        ->setJSON([
            'status'   => true,
            'products' => $result['products'],
        ]);
}

/**
 * Paginated customer search results.
 * URL: /products/search-data?search=Neem&page=1
 */
public function searchData()
{
    $search = trim((string) $this->request->getGet('search'));

    $page = max(1, (int) $this->request->getGet('page'));
    $limit = 12;
    $offset = ($page - 1) * $limit;

    if (mb_strlen($search) < 2) {
        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status'   => true,
                'products' => [],
                'total'    => 0,
                'has_more' => false,
            ]);
    }

    $result = $this->productModel->searchCustomerProducts(
        $search,
        $limit,
        $offset
    );

    return $this->response
        ->setContentType('application/json')
        ->setJSON([
            'status'   => true,
            'products' => $result['products'],
            'total'    => $result['total'],
            'has_more' => ($offset + count($result['products'])) < $result['total'],
        ]);
}


}