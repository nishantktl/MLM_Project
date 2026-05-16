<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'username', 'phone', 'email', 'password','hash_password', 'status', 'txn_pin', 'created_at', 'created_by', 'updated_at', 'updated_by','parent_id','dob','gender', 'address', 'city', 'state', 'pincode'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Get the last user_id from database
     */
    public function getLastUserId()
    {
        $lastUser = $this->orderBy('id', 'DESC')->first();
        return $lastUser ? $lastUser['user_id'] : null;
    }

    /**
     * Check if user exists by email
     */
    public function userExistsByEmail($email)
    {
        return $this->where('email', $email)->first() !== null;
    }

    /**
     * Check if user exists by phone
     */
    public function userExistsByPhone($phone)
    {
        return $this->where('phone', $phone)->first() !== null;
    }

    /**
     * Check if user exists by username
     */
    public function userExistsByUsername($username)
    {
        return $this->where('username', $username)->first() !== null;
    }

    /**
     * Check if user_id exists
     */
    public function userIdExists($userId)
    {
        return $this->where('user_id', $userId)->first() !== null;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get user by ID
     */
    public function getUserById($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

      /**
     * Register user and create wallet with ₹50 signup bonus.
     *
     * @param array $userData
     * @return bool|array
     */
    public function registerUserWithWallet(array $userData)
    {
        $db = \Config\Database::connect();
        $walletModel = new \App\Models\UserWalletAssociationModel();

        try {
            $db->transBegin();

            if (!$this->insert($userData)) {
                throw new \Exception('Failed to create user.');
            }

            if (!$walletModel->insert([
                'user_id'            => $userData['user_id'],
                'income_balance'    => 50.00,
                'withdrawal_balance' => 0.00,
                'created_by'         => $userData['created_by']
            ])) {
                throw new \Exception('Failed to create wallet.');
            }

            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Registration successful.',
            ];
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Registration Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'errors'  => [
                    'user'   => $this->errors(),
                    'wallet' => $walletModel->errors(),
                ],
            ];
        }
    }

    /**
     * Update user profile
     */
    public function updateUserProfile($userId, $userData)
    {
        unset($userData['password']);
        unset($userData['email']);
        return $this->update($userId, $userData);
    }

    /**
     * Change user password
     */
    public function changePassword($userId, $newPassword)
    {
        $hashedPassword = md5($newPassword);
        return $this->update($userId, ['password' => $hashedPassword]);
    }

    public function getActiveUsers(): array
    {
        return $this->select('id, user_id, parent_id, username, phone, email, role, status, created_at')
                    ->where('status !=', 'blocked')
                    ->where('role', 'user')
                    ->orderBy('id', 'desc')
                    ->findAll();
    }

}
