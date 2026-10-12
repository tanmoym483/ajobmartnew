<?php

namespace App\Models;

use CodeIgniter\Model;

class AjobmartEmailOtpModel extends Model
{
    protected $table            = 'ajobmart_email_otps';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'email',
        'otp',
        'expires_at',
        'verified',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Invalidate existing OTP records for an email.
     */
    public function invalidatePreviousOtps(string $email): bool
    {
        return $this->builder()
            ->where('email', $email)
            ->set('verified', 1)
            ->update();
    }

    /**
     * Find the newest unverified OTP.
     */
    public function findLatestPendingOtp(string $email, string $otp)
    {
        return $this->where('email', $email)
            ->where('otp', $otp)
            ->where('verified', 0)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Mark an OTP as verified.
     */
    public function markVerified(int $id): bool
    {
        return $this->update($id, ['verified' => 1]);
    }

    /**
     * Delete a particular OTP if email delivery fails.
     */
    public function deleteOtp(int $id): bool
    {
        return $this->delete($id);
    }
}