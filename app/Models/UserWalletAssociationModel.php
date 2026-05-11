<?php

namespace App\Models;

use CodeIgniter\Model;

class UserWalletAssociationModel extends Model
{
    protected $table = 'user_wallet_association';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = ''; // Prevent auto-filling updated_at

    protected $allowedFields = [
        'user_id',
        'deposit_balance',
        'withdrawal_balance',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Get wallet details by user ID.
     *
     * @param string $userId
     * @return array|null
     */
    public function getWalletByUserId(string $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }
}