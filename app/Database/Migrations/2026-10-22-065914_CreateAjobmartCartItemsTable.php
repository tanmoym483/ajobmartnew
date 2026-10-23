<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAjobmartCartItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'customer_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'product_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 1,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(
            ['customer_id', 'product_id'],
            'uq_cart_customer_product'
        );
        $this->forge->addKey('customer_id');
        $this->forge->addKey('product_id');

        $this->forge->createTable('ajobmart_cart_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('ajobmart_cart_items', true);
    }
}
