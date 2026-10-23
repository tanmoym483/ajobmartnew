<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table            = 'ajobmart_cart_items';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'customer_id',
        'product_id',
        'quantity',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all cart items for a customer.
     */
    public function getCustomerCart(int $customerId): array
    {
        return $this->db->table('ajobmart_cart_items c')
            ->select([
                'c.id AS cart_item_id',
                'c.product_id',
                'c.quantity',
                'p.product_name',
                'p.listing_price',
                'p.mrp',
                'p.stock_quantity',
                'p.min_order_quantity',
                'p.status',
                'p.approval_status',
                '(
                    SELECT pi.image_path
                    FROM ajobmart_product_images pi
                    WHERE pi.product_id = p.id
                    ORDER BY pi.is_primary DESC,
                            pi.sort_order ASC,
                            pi.id ASC
                    LIMIT 1
                ) AS image_path',
            ])
            ->join(
                'ajobmart_products p',
                'p.id = c.product_id'
            )
            ->where('c.customer_id', $customerId)
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Find a customer's cart item for a product.
     */
    public function findCustomerProduct(
        int $customerId,
        int $productId
    ): ?array {
        $item = $this->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        return $item ?: null;
    }

    /**
     * Calculate the total quantity of items in the cart.
     */
    public function getCustomerCartCount(int $customerId): int
    {
        $row = $this->selectSum('quantity', 'total_quantity')
            ->where('customer_id', $customerId)
            ->first();

        return (int) ($row['total_quantity'] ?? 0);
    }
}
