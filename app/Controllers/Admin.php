<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function dashboard()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Admin Dashboard'];
            return view('layout/header', $data)
            . view('AdminDashboard')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function all_users()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'All Users'];
            return view('layout/header', $data)
            . view('admin/AllUsers')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function get_user_list()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
        $user_id = session()->get('user_id');

        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $users = $this->userModel->getActiveUsers();

            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => 'User list retrieved successfully',
                'data' => [
                    'users' => $users
                ]
            ]);   
        } else{
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function fund_request_tbl()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
        $user_id = session()->get('user_id');

        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = [
                'title' => 'Fund Requests'
            ];

            // Load the view file located at: app/Views/admin/Fund_request.php
            return view('layout/header', $data)
                . view('admin/Fund_request')
                . view('layout/footer');  
        } else{
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function process_deposit_request()
    {
        // Ensure request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        // ================= 1. STRICT ADMIN AUTHENTICATION =================
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['Resp_code' => 'ERR', 'Resp_desc' => 'Session expired. Please login.']);
        }

        $admin_id  = session()->get('user_id');
        $adminData = $this->userModel->getUserById($admin_id);

        // Verify user exists and is an Admin
        if (!$adminData || $adminData['role'] !== 'admin') {
            session()->destroy();
            return $this->response->setJSON(['Resp_code' => 'ERR', 'Resp_desc' => 'Unauthorized access. Verification failed.']);
        }

        // Grab post parameters
        $txnId  = $this->request->getPost('txn_id');
        $action = $this->request->getPost('action'); // 'approve' or 'reject'

        $txnModel = new \App\Models\UserTransactionModel();
        $txn      = $txnModel->find($txnId);

        // ================= 2. TRANSACTION VERIFICATIONS =================
        if (!$txn) {
            return $this->response->setJSON(['Resp_code' => 'ERR', 'Resp_desc' => 'Transaction record not found.']);
        }

        // Ensure status is still pending (Stops admin double-clicking or race conditions)
        if ($txn['status'] !== 'pending') {
            return $this->response->setJSON(['Resp_code' => 'ERR', 'Resp_desc' => 'This request has already been processed.']);
        }

        // ================= 3. ATOMIC FINANCIAL TRANSACTION =================
        $db = \Config\Database::connect();
        
        // Start Database Transaction locks
        $db->transStart();

        try {
            if ($action === 'approve') {
                
                // A. Update transaction status to approved
                $txnModel->update($txnId, ['status' => 'approved']);

                // B. Add funds directly to the user's wallet safely using MySQL atomic addition
                // 👉 CHANGE 'wallet' below if your column is named fund_wallet, balance, etc.
                $db->table('user_wallet_association')
                ->where('user_id', $txn['user_id'])
                ->set('deposit_balance', 'deposit_balance + ' . (float)$txn['txn_amt'], false) 
                ->update();

                $description = 'Transaction approved and ₹' . $txn['txn_amt'] . ' successfully credited to user wallet.';
                
            } else {
                // Reject Request
                $txnModel->update($txnId, ['status' => 'rejected']);
                $description = 'Deposit request rejected successfully.';
            }

            // Complete the transaction locks
            $db->transComplete();

            // Check if query rollbacks occurred
            if ($db->transStatus() === false) {
                return $this->response->setJSON(['Resp_code' => 'ERR', 'Resp_desc' => 'Database operation failed. No funds added.']);
            }

            // Return Success
            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => $description,
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            // Rollback any financial changes if a query crashes halfway through
            $db->transRollback();
            
            return $this->response->setJSON([
                'Resp_code' => 'ERR', 
                'Resp_desc' => 'A fatal error occurred while processing funds. Operations rolled back safely.'
            ]);
        }
    }

    public function payout_history()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
        $user_id = session()->get('user_id');

        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = [
                'title' => 'Payout Requests'
            ];

            // Load the view file located at: app/Views/admin/payout_request.php
            return view('layout/header', $data)
                . view('admin/withdraw_request')
                . view('layout/footer');  
        } else{
            session()->destroy();
            return redirect()->to('/login');
        }
    }
    
    public function process_withdraw_request()
    {
        // Ensure request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        // ================= 1. STRICT ADMIN AUTHENTICATION =================
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'Session expired. Please login.',
                'data'      => []
            ]);
        }

        $adminId   = session()->get('user_id');
        $adminData = $this->userModel->getUserById($adminId);

        // Verify user exists and is an Admin
        if (!$adminData || ($adminData['role'] ?? '') !== 'admin') {
            session()->destroy();

            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'Unauthorized access. Verification failed.',
                'data'      => []
            ]);
        }

        // ================= 2. GET POST PARAMETERS =================
        $params = $this->request->getPost();

        $params['txn_id'] = isset($params['txn_id'])
            ? (int) $params['txn_id']
            : 0;

        $params['action'] = isset($params['action'])
            ? strtolower(trim($params['action']))
            : '';

        if ($params['txn_id'] <= 0) {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'Invalid transaction ID.',
                'data'      => []
            ]);
        }

        if (!in_array($params['action'], ['approve', 'reject'])) {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'Invalid action.',
                'data'      => []
            ]);
        }

        $withdrawTxnModel = new \App\Models\WithdrawTransactionModel();
        $walletModel      = new \App\Models\UserWalletAssociationModel();

        $txn = $withdrawTxnModel->find($params['txn_id']);  

        // ================= 3. TRANSACTION VERIFICATIONS =================
        if (!$txn) {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'Transaction record not found.',
                'data'      => []
            ]);
        }

        // Ensure status is still pending
        if (strtolower($txn['status']) !== 'pending') {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'This request has already been processed.',
                'data'      => []
            ]);
        }

        // ================= 4. ATOMIC FINANCIAL TRANSACTION =================
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if ($params['action'] === 'approve') {

                // A. Update transaction status
                $withdrawTxnModel->update($params['txn_id'], [
                    'status'     => 'approved',
                    'remarks'    => 'Withdrawal request approved.',
                    'updated_by' => $adminId,
                ]);

                $description = 'Withdrawal request approved successfully.';
            } else {

                // Reject request
                $withdrawTxnModel->update($params['txn_id'], [
                    'status'     => 'rejected',
                    'remarks'    => 'Withdrawal request rejected.',
                    'updated_by' => $adminId,
                ]);

                $db->table('user_wallet_association')
                    ->where('user_id', $txn['user_id'])
                    ->set(
                        'withdrawal_balance',
                        'withdrawal_balance - ' . (float) $txn['req_amt'],
                        false
                    )
                ->update();

                $description = 'Withdrawal request rejected successfully.';
            }

            // Complete transaction
            $db->transComplete();

            // Check transaction status
            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Database operation failed.',
                    'data'      => []
                ]);
            }

            // Success response
            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => $description,
                'data'      => [],
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            // Rollback on failure
            $db->transRollback();

            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'A fatal error occurred while processing the request.',
                'data'      => []
            ]);
        }
    }
}