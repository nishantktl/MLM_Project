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
            $users = $this->userModel->getUsers();

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

    public function qr_code()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Upload Qr Image'];
            return view('layout/header', $data)
            . view('admin/add_qr')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function save_qr_code()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'Something went wrong.',
            'data'      => [],
            'csrf_hash' => csrf_hash()
        ];

        $params = $this->request->getPost();

        $params['qr_name'] = isset($params['qr_name'])
            ? trim($params['qr_name'])
            : '';

        // ---------------- Validation ----------------
        if ($params['qr_name'] === '') {
            $response['Resp_desc'] = 'Validation failed.';
            $response['data'] = [
                'qr_name' => 'QR Code Name is required.'
            ];

            return $this->response->setJSON($response);
        }

        $file = $this->request->getFile('qr_image');

        if (!$file || !$file->isValid()) {
            $response['Resp_desc'] = 'Validation failed.';
            $response['data'] = [
                'qr_image' => 'Please select a valid image.'
            ];

            return $this->response->setJSON($response);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($file->getExtension()), $allowedExtensions)) {
            $response['Resp_desc'] = 'Validation failed.';
            $response['data'] = [
                'qr_image' => 'Only JPG, JPEG, PNG and WEBP files are allowed.'
            ];

            return $this->response->setJSON($response);
        }

        // ---------------- Model ----------------
        $qrModel = new \App\Models\QrCodeModel();

        // ---------------- Delete Old Record and File ----------------
        $oldQr = $qrModel->first();

        if ($oldQr) {
            // Delete old image file if it exists
            if (!empty($oldQr['qr_image'])) {
                $oldFilePath = FCPATH . $oldQr['qr_image'];

                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Delete old database record
            $qrModel->delete($oldQr['id']);
        }

        // ---------------- Upload New File ----------------
        $uploadPath = FCPATH . 'assets/images/qr_code/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newFileName = 'qr_' . time() . '.' . strtolower($file->getExtension());

        $file->move($uploadPath, $newFileName);

        // Path stored in database
        $imagePath = 'assets/images/qr_code/' . $newFileName;

        // ---------------- Insert New Record ----------------
        $insertData = [
            'qr_name'    => $params['qr_name'],
            'qr_image'   => $imagePath,
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        $qrModel->insert($insertData);

        // ---------------- Success Response ----------------
        $response['Resp_code'] = 'RCS';
        $response['Resp_desc'] = 'QR Code uploaded successfully.';
        $response['data'] = [
            'qr_name'       => $params['qr_name'],
            'qr_image_path' => $imagePath,
            'qr_image_url'  => base_url($imagePath)
        ];

        return $this->response->setJSON($response);
    }

    public function active_members()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Active Users'];
            return view('layout/header', $data)
            . view('admin/activemembers')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function get_active_user_list()
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

    public function pending_members()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Pending Users'];
            return view('layout/header', $data)
            . view('admin/pendingmembers')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function get_pending_user_list()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
        $user_id = session()->get('user_id');

        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $users = $this->userModel->getPendingUsers();

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

    public function block_members()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Block Users'];
            return view('layout/header', $data)
            . view('admin/blockmembers')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function get_block_user_list()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
        $user_id = session()->get('user_id');

        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $users = $this->userModel->getBlockUsers();

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

    public function user_details()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Users details'];
            return view('layout/header', $data)
            . view('admin/user_details')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function login_as_user()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'Something went wrong.',
            'data'      => []
        ];

        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            $response['Resp_desc'] = 'Unauthorized access.';
            return $this->response->setJSON($response);
        }

        $userId = trim($this->request->getPost('user_id'));

        if (empty($userId)) {
            $response['Resp_desc'] = 'User ID is required.';
            return $this->response->setJSON($response);
        }

        $user = $this->userModel
            ->where('user_id', $userId)
            ->first();

        if (!$user) {
            $response['Resp_desc'] = 'User not found.';
            return $this->response->setJSON($response);
        }

        // Save admin ID so admin can return later if needed
        session()->set('admin_user_id', session()->get('user_id'));

        session()->set([
            'user_id'    => $user['user_id'],
            'username'   => $user['username'],
            'parent_id'  => $user['parent_id'],
            'email'      => $user['email'],
            'phone'      => $user['phone'],
            'status'     => $user['status'],
            'role'       => $user['role'] ?? 'user',
            'isLoggedIn' => true,
        ]);

        // Success response
        $response['Resp_code'] = 'RCS';
        $response['Resp_desc'] = 'Logged in as user successfully.';
        $response['data'] = [
            'redirect_url' => ($user['role'] ?? 'user') === 'admin'
                ? base_url('admin/dashboard')
                : base_url('dashboard'),
        ];

        return $this->response->setJSON($response);
    }

    // Admin Controller
    public function get_user_details()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'User not found.',
            'data'      => []
        ];

        $userId = $this->request->getGet('user_id');

        $user = $this->userModel
            ->where('user_id', $userId)
            ->first();

        if ($user) {
            $response['Resp_code'] = 'RCS';
            $response['Resp_desc'] = 'User details fetched successfully.';
            $response['data'] = $user;
        }

        return $this->response->setJSON($response);
    }

    public function update_user_details()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'Update failed.',
            'data'      => []
        ];

        $id = $this->request->getPost('id');

        // Validate ID
        if (empty($id)) {
            $response['Resp_desc'] = 'User ID is required.';
            return $this->response->setJSON($response);
        }

        // Get all fields
        $username    = trim($this->request->getPost('username'));
        $father_name = trim($this->request->getPost('father_name'));
        $password    = trim($this->request->getPost('password'));
        $phone       = trim($this->request->getPost('phone'));
        $email       = trim($this->request->getPost('email'));
        $dob         = trim($this->request->getPost('dob'));
        $gender      = trim($this->request->getPost('gender'));
        $address     = trim($this->request->getPost('address'));
        $city        = trim($this->request->getPost('city'));
        $state       = trim($this->request->getPost('state'));
        $pincode     = trim($this->request->getPost('pincode'));

        // Mandatory field validation
        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Username is required.';
        }

        if ($father_name === '') {
            $errors['father_name'] = 'Father name is required.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Phone number is required.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        }

        if ($dob === '') {
            $errors['dob'] = 'Date of birth is required.';
        }

        if ($gender === '') {
            $errors['gender'] = 'Gender is required.';
        }

        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }

        if ($city === '') {
            $errors['city'] = 'City is required.';
        }

        if ($state === '') {
            $errors['state'] = 'State is required.';
        }

        if ($pincode === '') {
            $errors['pincode'] = 'Pincode is required.';
        }

        // Return validation errors
        if (!empty($errors)) {
            // Show first validation error in Resp_desc
            $response['Resp_desc'] = reset($errors);

            // Send all field errors in data
            $response['data'] = $errors;
            return $this->response->setJSON($response);
        }

        // Prepare update data
        $updateData = [
            'username'    => $username,
            'father_name' => $father_name,
            'password'    => $password,
            'phone'       => $phone,
            'email'       => $email,
            'dob'         => $dob,
            'gender'      => $gender,
            'address'     => $address,
            'city'        => $city,
            'state'       => $state,
            'pincode'     => $pincode,
            'updated_by'  => session()->get('user_id'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        // Update user
        if ($this->userModel->update($id, $updateData)) {
            $response['Resp_code'] = 'RCS';
            $response['Resp_desc'] = 'User updated successfully.';
        }

        return $this->response->setJSON($response);
    }

    public function get_active_users_by_date()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'No users found.',
            'data'      => []
        ];

        $fromDate = $this->request->getPost('from_date');
        $toDate   = $this->request->getPost('to_date');

        if (empty($fromDate) || empty($toDate)) {
            $response['Resp_desc'] = 'Both dates are required.';
            return $this->response->setJSON($response);
        }

        $users = $this->userModel
            ->select('user_id, username, phone, email, created_at')
            ->where('role', 'user')
            ->where('status', 'active')
            ->where('DATE(created_at) >=', $fromDate)
            ->where('DATE(created_at) <=', $toDate)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (!empty($users)) {
            $response['Resp_code'] = 'RCS';
            $response['Resp_desc'] = 'Users fetched successfully.';
            $response['data']      = $users;
        }

        return $this->response->setJSON($response);
    }

    public function datewise_active_members()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'admin'){
            $data = ['title' => 'Block Users'];
            return view('layout/header', $data)
            . view('admin/datewise_active_members')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }
}