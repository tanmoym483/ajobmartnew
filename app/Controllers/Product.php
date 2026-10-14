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

}