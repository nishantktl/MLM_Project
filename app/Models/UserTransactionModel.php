<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTransactionModel extends Model
{
    protected $table            = 'user_transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // 👉 Added portal_txn_id here
    protected $allowedFields    = [
        'portal_txn_id', 
        'user_id', 
        'txn_amt', 
        'utr', 
        'request_dt', 
        'status'
    ];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'request_dt';
    protected $updatedField     = '';

    /**
     * Generates a guaranteed unique portal transaction ID
     * Example Output: TXN-8A9FB2C1D4
     */
    public function generateUniquePortalTxnId()
    {
        do {
            $randomId = 'TXN-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
            
            $exists = $this->where('portal_txn_id', $randomId)->countAllResults();
            
        } while ($exists > 0); 

        return $randomId;
    }

    /**
     * Safely creates a transaction and returns the generated portal ID
     */
    public function createSecureTransaction($userId, $amount, $utr)
    {
        // 1. Get a verified unique ID
        $portalTxnId = $this->generateUniquePortalTxnId();

        $insertData = [
            'portal_txn_id' => $portalTxnId,
            'user_id'       => $userId,
            'txn_amt'       => $amount,
            'utr'           => $utr,
            'status'        => 'pending'
        ];

        $this->save($insertData);

        return $portalTxnId;
    }

        /**
     * ADMIN: Fetch ALL pending transactions across the entire platform
     */
    public function getAllTransactions()
    {
        return $this->orderBy('request_dt', 'DESC')
                    ->findAll();
    }

    /**
     * USER: Fetch pending transactions for a specific user only
     */
    public function getPendingTransactionsByUserId($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('status', 'pending')
                    ->orderBy('request_dt', 'DESC')
                    ->findAll();
    }
}