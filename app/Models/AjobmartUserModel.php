<?php
namespace App\Models;

use CodeIgniter\Model;

class AjobmartUserModel extends Model
{
    protected $table            = 'ajobmart_users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'name',
        'mobile',
        'email',
        'password',
        'status',
        'email_verified',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByMobile(string $mobile)
    {
        return $this->where('mobile', $mobile)->first();
    }

    public function createAccount(array $data)
    {
        return $this->insert($data);
    }
}