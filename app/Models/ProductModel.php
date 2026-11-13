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


    /**
     * Homepage product limits.
     * Change each value independently whenever required.
     */
    private array $homeProductLimits = [
        'popular'    => 6,
        'new'        => 6,
        'deals'      => 6,
        'trending'   => 6,

        'under99'    => 6,
        'under199'   => 6,

        'discount30' => 6,
        'discount50' => 6,
    ];

    /**
     * Get configured homepage product limit.
     */
    private function getHomeProductLimit(string $section): int
    {
        return max(1, (int) ($this->homeProductLimits[$section] ?? 12));
    }


    private function getHomeProducts()
    {
        return $this->db->table('ajobmart_products p')
            ->select('p.*')
            ->select("
                (SELECT pi.image_path
                FROM ajobmart_product_images pi
                WHERE pi.product_id = p.id
                ORDER BY pi.is_primary DESC, pi.sort_order ASC, pi.id ASC
                LIMIT 1) AS image_path
            ", false)
            ->where('status', 'active')
            ->where('approval_status', 'approved');

    }

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
            ->where('status', 'active')
            ->where('approval_status', 'approved')
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('popular'))
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


    public function getPopularProducts(): array
    {
        return $this->db->table('ajobmart_products p')
            ->select('p.*')
            ->select("
                COALESCE(ps.view_count, 0) AS view_count,
                COALESCE(ps.cart_add_count, 0) AS cart_add_count,
                COALESCE(ps.wishlist_count, 0) AS wishlist_count,
                COALESCE(ps.sold_quantity, 0) AS sold_quantity
            ", false)
            ->select("
                (COALESCE(ps.sold_quantity, 0) * 10
                + COALESCE(ps.cart_add_count, 0) * 3
                + COALESCE(ps.wishlist_count, 0) * 2
                + COALESCE(ps.view_count, 0)) AS popularity_score
            ", false)
            ->select("
                (SELECT pi.image_path
                FROM ajobmart_product_images pi
                WHERE pi.product_id = p.id
                ORDER BY pi.is_primary DESC, pi.sort_order ASC, pi.id ASC
                LIMIT 1) AS image_path
            ", false)
            ->join(
                'ajobmart_product_stats ps',
                'ps.product_id = p.id',
                'left'
            )
            ->where('p.status', 'active')
            ->where('p.approval_status', 'approved')
            ->orderBy('popularity_score', 'DESC')
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('trending'))
            ->get()
            ->getResultArray();
    }

    public function getNewArrivals(): array
    {
        return $this->getHomeProducts()
            ->orderBy('p.created_at', 'DESC')
            ->limit($this->getHomeProductLimit('new'))
            ->get()
            ->getResultArray();
    }

    public function getBestDeals(): array
    {
        return $this->getHomeProducts()
            ->where('p.mrp > p.listing_price', null, false)
            ->orderBy(
                '((p.mrp - p.listing_price) / p.mrp)',
                'DESC',
                false
            )
            ->limit($this->getHomeProductLimit('deals'))
            ->get()
            ->getResultArray();
    }

    public function getTrendingProducts(): array
    {
        return $this->getPopularProducts();
    }

    public function getProductsByCategory(int $categoryId): array
    {
        return $this->getHomeProducts()
            ->where('p.category_id', $categoryId)
            ->orderBy('p.id', 'DESC')
            // ->limit($this->getHomeProductLimit('under99'))
            ->get()
            ->getResultArray();
    }


    /**
     * Products priced at ₹99 or below.
     */
    public function getProductsUnder99(): array
    {
        return $this->getHomeProducts()
            ->where('p.listing_price <=', 99)
            ->where('p.stock_quantity >', 0)
            ->orderBy('p.listing_price', 'ASC')
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('under99'))
            ->get()
            ->getResultArray();
    }

    /**
     * Products priced at ₹199 or below.
     */
    public function getProductsUnder199(): array
    {
        return $this->getHomeProducts()
            ->where('p.listing_price <=', 199)
            ->where('p.stock_quantity >', 0)
            ->orderBy('p.listing_price', 'ASC')
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('under199'))
            ->get()
            ->getResultArray();
    }

    /**
     * Products with 30% or more discount.
     */
    public function getProductsDiscount30(): array
    {
        return $this->getHomeProducts()
            ->where('p.mrp >', 0)
            ->where('p.mrp > p.listing_price', null, false)
            ->where(
                '((p.mrp - p.listing_price) / p.mrp) * 100 >= 30',
                null,
                false
            )
            ->where('p.stock_quantity >', 0)
            ->orderBy(
                '((p.mrp - p.listing_price) / p.mrp)',
                'DESC',
                false
            )
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('discount30'))
            ->get()
            ->getResultArray();
    }

    /**
     * Products with 50% or more discount.
     */
    public function getProductsDiscount50(): array
    {
        return $this->getHomeProducts()
            ->where('p.mrp >', 0)
            ->where('p.mrp > p.listing_price', null, false)
            ->where(
                '((p.mrp - p.listing_price) / p.mrp) * 100 >= 50',
                null,
                false
            )
            ->where('p.stock_quantity >', 0)
            ->orderBy(
                '((p.mrp - p.listing_price) / p.mrp)',
                'DESC',
                false
            )
            ->orderBy('p.id', 'DESC')
            ->limit($this->getHomeProductLimit('discount50'))
            ->get()
            ->getResultArray();
    }


    /**
     * Get products for homepage infinite scrolling.
     */
    public function getMoreProducts(int $limit = 12, int $offset = 0): array
    {
        return $this->getHomeProducts()
            ->orderBy('p.id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }


}
