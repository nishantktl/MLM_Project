<?php

namespace App\Models;

use CodeIgniter\Model;

class QrCodeStorageModel extends Model
{
    protected $table            = 'qr_code_storage';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'qr_code_image', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField = '';

    /**
     * Helper function to get an active QR code by User ID
     */
    public function getActiveQr()
    {
        return $this->where('status', 'active')
                    ->first();
    }
}