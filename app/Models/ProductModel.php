<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'ajobmart_products';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'seller_id',
        'category',
        'product_name',
        'brand',
        'sku',
        'description',
        'product_condition',
        'category_attributes',
        'package_length',
        'package_width',
        'package_height',
        'mrp',
        'selling_price',
        'listing_price',
        'shipping_allowance',
        'stock_quantity',
        'low_stock',
        'shipping_weight',
        'shipping_days',
        'shipping_type',
        'status',
    ];


    public function getSellerInventory(): array
    {
        return $this->db->table('ajobmart_products p')
            ->select('
                p.*
            ')
            ->select(
                '(SELECT pi.image_path
                  FROM ajobmart_product_images pi
                  WHERE pi.product_id = p.id
                  ORDER BY pi.is_primary DESC,
                           pi.sort_order ASC,
                           pi.id ASC
                  LIMIT 1) AS image_path',
                false
            )
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Calculate inventory totals for one seller.
     */
    public function getInventoryStats(int $sellerId): array
    {
        $result = $this->db->table('ajobmart_products p')
            ->select('COUNT(*) AS total_products', false)
            ->select(
                'COALESCE(SUM(GREATEST(p.stock_quantity, 0)), 0) AS total_stock',
                false
            )
            ->select(
                'COALESCE(SUM(
                    CASE
                        WHEN p.stock_quantity > 0
                         AND p.stock_quantity <= GREATEST(p.low_stock, 0)
                        THEN 1 ELSE 0
                    END
                ), 0) AS low_stock_products',
                false
            )
            ->select(
                'COALESCE(SUM(
                    CASE
                        WHEN p.stock_quantity <= 0
                        THEN 1 ELSE 0
                    END
                ), 0) AS out_of_stock_products',
                false
            )
            ->where('p.seller_id', $sellerId)
            ->get()
            ->getRowArray();

        return $result ?? [
            'total_products'       => 0,
            'total_stock'          => 0,
            'low_stock_products'   => 0,
            'out_of_stock_products'=> 0,
        ];
    }
}
