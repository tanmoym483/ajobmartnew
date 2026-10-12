<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AjobmartUserModel;
use App\Models\AjobmartEmailOtpModel;

class Auth extends BaseController
{
    protected AjobmartUserModel $userModel;
    protected AjobmartEmailOtpModel $otpModel;

    public function __construct()
    {
        $this->userModel = new AjobmartUserModel();
        $this->otpModel  = new AjobmartEmailOtpModel();
    }

    /**
     * SEND REGISTRATION OTP
     */
    public function sendRegistrationOtp()
    {
        $email = strtolower(trim(
            (string) $this->request->getPost('email')
        ));

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please enter a valid email address.'
            ]);
        }

        // Check existing account
        if ($this->userModel->findByEmail($email)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'An account already exists with this email.'
            ]);
        }

        // Generate six-digit OTP
        $otp = (string) random_int(100000, 999999);

        // Invalidate previous OTPs
        if (!$this->otpModel->invalidatePreviousOtps($email)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to generate OTP. Please try again.'
            ]);
        }

        // OTP expires after 10 minutes
        $now = time();

        $expiresAt = date(
            'Y-m-d H:i:s',
            $now + (10 * 60)
        );

        // Save OTP
        $otpId = $this->otpModel->insert([
            'email'      => $email,
            'otp'        => $otp,
            'expires_at' => $expiresAt,
            'verified'   => 0,
            'created_at' => date('Y-m-d H:i:s', $now)
        ]);

        if (!$otpId) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to generate OTP. Please try again.'
            ]);
        }

        // Send email using your existing BaseController method
        $sent = $this->sendEmailOtp(
            $email,
            $otp,
            'registration',
            'customer'
        );

        if (!$sent) {
            // Delete only the OTP created for this request
            $this->otpModel->deleteOtp((int) $otpId);

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to send verification email. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'OTP has been sent to your email address.'
        ]);
    }

    /**
     * VERIFY REGISTRATION OTP
     */
    public function verifyRegistrationOtp()
    {
        $email = strtolower(trim(
            (string) $this->request->getPost('email')
        ));

        $otp = trim(
            (string) $this->request->getPost('otp')
        );

        if (
            !$email ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !preg_match('/^[0-9]{6}$/', $otp)
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please enter a valid email address and six-digit OTP.'
            ]);
        }

        // Find latest pending OTP
        $otpRow = $this->otpModel->findLatestPendingOtp(
            $email,
            $otp
        );



        if (!$otpRow) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid OTP.'
            ]);
        }

        // Check expiration
        if (strtotime($otpRow->expires_at) < time()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'OTP has expired. Please request a new OTP.'
            ]);
        }

        // Mark OTP verified
        if (!$this->otpModel->markVerified((int) $otpRow->id)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to verify OTP. Please try again.'
            ]);
        }

        // Store verified email temporarily
        $this->session->set(
            'ajobmart_verified_registration_email',
            $email
        );

        log_message('debug', 'OTP Session ID: ' . session_id());

        log_message(
            'debug',
            'OTP Session Email: ' .
            (string) $this->session->get(
                'ajobmart_verified_registration_email'
            )
        );

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * FINAL REGISTRATION
     */
    public function register()
    {

    log_message('debug', 'Register Session ID: ' . session_id());

log_message(
    'debug',
    'Register Session Email: ' .
    (string) $this->session->get(
        'ajobmart_verified_registration_email'
    )
);
        $email = strtolower(trim(
            (string) $this->request->getPost('email')
        ));



        $name = trim(
            (string) $this->request->getPost('name')
        );

        $mobile = trim(
            (string) $this->request->getPost('mobile')
        );

        $password = (string) $this->request->getPost('password');


        // Verify email was actually verified
        $verifiedEmail = $this->session->get(
            'ajobmart_verified_registration_email'
        );


        if (!$verifiedEmail || $verifiedEmail !== $email) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please verify your email first.'
            ]);
        }

        // Validate name
        if ($name === '') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Name is required.'
            ]);
        }

        // Validate email
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please enter a valid email address.'
            ]);
        }

        // Validate mobile
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please enter a valid 10-digit mobile number.'
            ]);
        }

        // Validate password
        if (strlen($password) < 6) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Password must contain at least 6 characters.'
            ]);
        }

        // Check email
        if ($this->userModel->findByEmail($email)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'This email is already registered.'
            ]);
        }

        // Check mobile
        if ($this->userModel->findByMobile($mobile)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'This mobile number is already registered.'
            ]);
        }

        // Generate User ID
        $userId = 'AJM' . strtoupper(
            substr(bin2hex(random_bytes(5)), 0, 7)
        );

        // Hash password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $now = date('Y-m-d H:i:s');

        // Insert account through model
        $inserted = $this->userModel->createAccount([
            'user_id'        => $userId,
            'name'           => $name,
            'mobile'         => $mobile,
            'email'          => $email,
            'password'       => $hashedPassword,
            'status'         => 'active',
            'email_verified' => 1,
            'created_at'     => $now,
            'updated_at'     => $now
        ]);

        if (!$inserted) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to create account. Please try again.'
            ]);
        }

        // Remove temporary verification
        session()->remove(
            'ajobmart_verified_registration_email'
        );

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Your Ajobmart account has been created successfully.',
            'user_id' => $userId
        ]);
    }

    
public function login()
{
    $identifier = trim((string) $this->request->getPost('user'));
    $password   = (string) $this->request->getPost('password');

    if ($identifier === '' || $password === '') {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Please enter your login details.'
        ]);
    }

    // Search only the Ajobmart customer table.
    $db = \Config\Database::connect();

    $user = $db->table('ajobmart_users')
        ->groupStart()
            ->where('user_id', strtoupper($identifier))
            ->orWhere('email', strtolower($identifier))
            ->orWhere('mobile', $identifier)
        ->groupEnd()
        ->get()
        ->getRowArray();

    // Use the same password_hash() format as registration.
    if (!$user || !password_verify($password, $user['password'])) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Invalid User ID, email, mobile number or password.'
        ]);
    }

    // Reject inactive or unverified customer accounts.
    if (strtolower((string) ($user['status'] ?? '')) !== 'active') {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Your customer account is inactive. Please contact support.'
        ]);
    }

    if ((int) ($user['email_verified'] ?? 0) !== 1) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Please verify your email before logging in.'
        ]);
    }

    // Regenerate the session ID after successful authentication.
    $this->session->regenerate(true);

    // Create a customer-specific session.
    $this->session->set([
        'ajobmart_customer_logged_in' => true,
        'ajobmart_customer_db_id'     => (int) $user['id'],
        'ajobmart_customer_user_id'   => $user['user_id'],
        'ajobmart_customer_name'      => $user['name'],
        'ajobmart_customer_email'     => $user['email'],
        'ajobmart_customer_mobile'    => $user['mobile'],
    ]);

    return $this->response->setJSON([
        'status'   => true,
        'message'  => 'Login successful.',
        'redirect' => site_url('/')
    ]);
}

    public function customerLogout()
    {
        $this->session->remove([
            'ajobmart_customer_logged_in',
            'ajobmart_customer_db_id',
            'ajobmart_customer_user_id',
            'ajobmart_customer_name',
            'ajobmart_customer_email',
            'ajobmart_customer_mobile',
        ]);

        return redirect()->to(site_url('/'));
    }
}
