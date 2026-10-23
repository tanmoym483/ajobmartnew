<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAjobmartUserCouponsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'coupon_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 100.00,
            ],
            'minimum_order_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'used', 'expired'],
                'default'    => 'active',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addUniqueKey('coupon_code');

        $this->forge->createTable('ajobmart_user_coupons', true);
    }

    public function down()
    {
        $this->forge->dropTable('ajobmart_user_coupons', true);
    }
}
