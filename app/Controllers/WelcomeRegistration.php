<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class WelcomeRegistration extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    /**
     * QR registration landing page.
     */
    public function index()
    {
        return view('welcome_registration/index');
    }

    /**
     * Send email OTP.
     */
    public function sendOtp()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please enter a valid email address.',
            ]);
        }

        // Do not send registration OTP to an existing account.
        $existing = $this->db->table('ajobmart_users')
            ->where('email', $email)
            ->countAllResults();

        if ($existing > 0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'This email is already registered. Please log in.',
            ]);
        }

        // Resend cooldown: 60 seconds.
        $lastOtp = $this->db->table('ajobmart_email_otps')
            ->where('email', $email)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if ($lastOtp && ! empty($lastOtp['created_at'])) {
            $lastTime = strtotime($lastOtp['created_at']);

            if ($lastTime !== false && (time() - $lastTime) < 60) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Please wait before requesting another OTP.',
                ]);
            }
        }

        // Six-digit OTP, valid for 10 minutes.
        $otp       = (string) random_int(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', time() + 600);

        // Store a hash rather than the raw OTP.
        $saved = $this->db->table('ajobmart_email_otps')->insert([
            'email'      => $email,
            'otp'        => password_hash($otp, PASSWORD_DEFAULT),
            'expires_at' => $expiresAt,
            'verified'   => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $saved) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Could not create OTP. Please try again.',
            ]);
        }

        // Configure SMTP in app/Config/Email.php first.
        $sent = $this->sendEmailOtp(
            $email,
            $otp,
            'registration',
            'customer'
        );


        if (!$sent) {
            // Remove the unsent OTP so it cannot be used.
            $this->db->table('ajobmart_email_otps')
                ->where('id', $this->db->insertID())
                ->delete();

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to send verification email. Please try again.'
            ]);
        }

        // Clear previous verification state for this email.
        $this->session->remove('welcome_verified_email');

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'OTP sent successfully.',
            'debug_otp' => (string) $otp,
            'csrfHash'  => csrf_hash(),
        ]);
    }

    /**
     * Verify the email OTP.
     */
    public function verifyOtp()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $otp   = trim((string) $this->request->getPost('otp'));

        if (
            ! filter_var($email, FILTER_VALIDATE_EMAIL) ||
            ! preg_match('/^\d{6}$/', $otp)
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Enter a valid email and 6-digit OTP.',
            ]);
        }

        $record = $this->db->table('ajobmart_email_otps')
            ->where('email', $email)
            ->where('verified', 0)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();



        if (! $record) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'OTP not found or already used. Request a new OTP.',
            ]);
        }

        if (strtotime($record['expires_at']) < time()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'OTP expired. Please request a new one.',
            ]);
        }

        if (!password_verify($otp, $record['otp'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Incorrect OTP.',
            ]);
        }

        $this->db->table('ajobmart_email_otps')
            ->where('id', $record['id'])
            ->update(['verified' => 1]);

        // The session binds verification to this email.
        $this->session->set('welcome_verified_email', $email);

        // $sessionotp = $this->session->get('welcome_verified_email');

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Email verified successfully. Complete registration.',
        ]);
    }

    /**
     * Create customer and award welcome coupon.
     */
    public function register()
    {
        $name     = trim((string) $this->request->getPost('name'));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $mobile   = trim((string) $this->request->getPost('mobile'));
        $password = $mobile;

        if (
            $name === '' ||
            ! filter_var($email, FILTER_VALIDATE_EMAIL) ||
            ! preg_match('/^\d{10}$/', $mobile) ||
            strlen($password) < 6
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Enter a valid name, email, 10-digit mobile number and password of at least 6 characters.',
            ]);
        }

        if ($this->session->get('welcome_verified_email') !== $email) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Verify your email OTP before registering.',
            ]);
        }

        $exist = $this->db->table('ajobmart_users')
            ->groupStart()
                ->where('email', $email)
                ->orWhere('mobile', $mobile)
            ->groupEnd()
            ->countAllResults();

        if ( $exist > 0
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'This email or mobile number is already registered.',
            ]);
        }

        $this->db->transBegin();

        try {
            $userId = 'AJM' . strtoupper(
            substr(bin2hex(random_bytes(5)), 0, 7)
        );

            $inserted = $this->db->table('ajobmart_users')->insert([
                'user_id'               => $userId,
                'name'                  => $name,
                'mobile'                => $mobile,
                'email'                 => $email,
                'password'              => password_hash($password, PASSWORD_DEFAULT),
                'status'                => 'active',
                'email_verified'        => 1,
                'total_coupon_amount'   => 100,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);

            if (! $inserted) {
                throw new \RuntimeException('Customer registration failed.');
            }

            $customerId = $this->db->insertID();

            $couponCode = 'AJOB100-' . strtoupper(bin2hex(random_bytes(4)));

            $couponInserted = $this->db
                ->table('ajobmart_user_coupons')
                ->insert([
                    'user_id'           => $customerId,
                    'coupon_code'       => $couponCode,
                    'discount_amount'   => 100.00,
                    'minimum_order_amount' => 0.00,
                    'status'            => 'active',
                    'expires_at'        => null,
                    'used_at'           => null,
                    'created_at'        => date('Y-m-d H:i:s'),
                ]);

            if (! $couponInserted || $this->db->transStatus() === false) {
                throw new \RuntimeException('Welcome coupon creation failed.');
            }

            $this->db->transCommit();

            $this->session->remove('welcome_verified_email');

            // Fetch the newly registered customer.
            $user = $this->db->table('ajobmart_users')
                ->where('id', $customerId)
                ->get()
                ->getRowArray();

            if (! $user) {
                throw new \RuntimeException('Customer created but could not be retrieved.');
            }

            // Regenerate session ID for security.
            $this->session->regenerate(true);

            // Create customer-specific session.
            $this->session->set([
                'ajobmart_customer_logged_in' => true,
                'ajobmart_customer_db_id'     => (int) $user['id'],
                'ajobmart_customer_user_id'   => $user['user_id'],
                'ajobmart_customer_name'      => $user['name'],
                'ajobmart_customer_email'     => $user['email'],
                'ajobmart_customer_mobile'    => $user['mobile'],
                'ajobmart_customer_coupon_amount' => $user['total_coupon_amount'],

            ]);

            // Remove temporary OTP verification session.

            return $this->response->setJSON([
                'status'   => true,
                'message'  => 'Registration successful! Your ₹100 welcome coupon is ready.',
                'coupon'   => $couponCode,
                'redirect' => site_url('/'),
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();

            log_message('error', 'Ajobmart welcome registration failed: {error}', [
                'error' => $e->getMessage(),
            ]);

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Registration could not be completed. Please try again.',
            ]);
        }
    }
}
