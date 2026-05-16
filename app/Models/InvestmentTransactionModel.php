<?php

namespace App\Models;

use CodeIgniter\Model;

class InvestmentTransactionModel extends Model
{
    protected $table            = 'investment_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $allowedFields = [
        'from_user_id',
        'to_user_id',
        'investment_amount',
        'from_balance_before',
        'from_balance_after',
        'to_balance_before',
        'to_balance_after',
        'type',
        'status',
        'remarks',
        'created_by',
    ];

    public function getAllTransactions()
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getTransactionsByUserId($userId)
    {
        
        return $this->where('from_user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}