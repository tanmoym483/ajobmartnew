<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureAjobmartAuthTables extends Migration
{
    public function up()
    {
        $requiredTables = [
            'ajobmart_users',
            'ajobmart_email_otps',
        ];

        foreach ($requiredTables as $table) {
            if (!$this->db->tableExists($table)) {
                throw new \RuntimeException(
                    "Required table '{$table}' does not exist. "
                    . 'Please check your existing database schema.'
                );
            }
        }
    }

    public function down()
    {
        // Keep existing tables and their data intact.
    }
}