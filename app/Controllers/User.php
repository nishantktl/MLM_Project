<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            redirect()->to('/login')->send();
            exit;
        }
        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);
        if($userdata && $userdata['role'] == 'user'){
            $data = ['title' => 'Deposite Fund'];
            return view('layout/header', $data)
            . view('user/fund_request')
            . view('layout/footer');
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function submit_fund_deposit()
    {
        // ================= 1. AUTHENTICATION & SESSION CHECK =================
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Session expired. Please login again.',
                    'data'      => []
                ]);
            }
            redirect()->to('/login')->send();
            exit;
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if ($userdata && $userdata['role'] == 'user') {

            // ================= 2. SERVER-SIDE VALIDATION =================
            $validation = \Config\Services::validation();

            $rules = [
                'fund_amount' => [
                    'rules'  => 'required|numeric|greater_than[0]',
                    'errors' => [
                        'required'     => 'Please enter the deposit amount.',
                        'numeric'      => 'Fund amount must be a valid number.',
                        'greater_than' => 'Fund amount must be greater than zero.'
                    ]
                ],
                'utr' => [
                    'rules'  => 'required|alpha_numeric|is_unique[user_transaction.utr]',
                    'errors' => [
                        'required'      => 'Please enter the UTR / Reference number.',
                        'alpha_numeric' => 'UTR should only contain letters and numbers.',
                        'is_unique'     => 'This UTR number has already been submitted for verification.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Validation failed. Please check your inputs.',
                    'errors'    => $validation->getErrors(),
                    'data'      => [],
                    'csrf_hash' => csrf_hash()
                ]);
            }

            // ================= 3. SAVE & GENERATE RANDOM UNIQUE ID =================
            try {
                $txnModel = new \App\Models\UserTransactionModel();

                $amount = $this->request->getPost('fund_amount');
                $utr    = $this->request->getPost('utr');

                // 👉 Calls our custom Model method to generate unique ID and save data safely
                $generated_portal_id = $txnModel->createSecureTransaction($user_id, $amount, $utr);

                // ================= 4. SUCCESS RESPONSE =================
                return $this->response->setJSON([
                    'Resp_code' => 'RCS',
                    'Resp_desc' => 'Deposit request submitted successfully! Your funds will be credited after verification.',
                    'data'      => [
                        'transaction_id' => $generated_portal_id, // 👉 Passes the TXN-XXXXXX ID back
                        'user_id'        => $user_id,
                        'amount'         => $amount,
                        'utr'            => $utr
                    ],
                    'csrf_hash' => csrf_hash()
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'An internal server error occurred. Please try again later.',
                    'data'      => [],
                    'csrf_hash' => csrf_hash()
                ]);
            }

        } else {
            session()->destroy();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Unauthorized access. Account verification failed.',
                    'data'      => []
                ]);
            }
            redirect()->to('/login')->send();
            exit;
        }
    }

    /**
     * Loads the Deposit History HTML View Page
     */
    public function deposit_history()
    {
        // ================= 1. AUTHENTICATION & SESSION CHECK =================
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            return redirect()->to('/login');
        }

        $user_id  = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        // ================= 2. VERIFY USER EXISTENCE & ROLE =================
        if ($userdata && $userdata['role'] == 'user') {
            
            // Prepare data to send to the view (Title, User details for sidebar, etc.)
            $data = [
                'title' => 'Deposit History'
            ];

            // Load the view file located at: app/Views/deposit_history.php
            return view('layout/header', $data)
                . view('user/deposit_history')
                . view('layout/footer');

        } else {
            // Fallback: Account verification completely failed
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function deposit_history_tbl()
    {
        // ================= 1. UNIVERSAL LOGIN CHECK =================
        // Just check if logged in (Do NOT restrict by role here)
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Session expired. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 2. STRICT USER EXISTENCE CHECK =================
        $user_id  = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        // If user doesn't exist in DB anymore, destroy session immediately
        if (!$userdata) {
            session()->destroy();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Account verification failed. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 3. FETCH DATA BASED ON ROLE =================
        try {
            $txnModel = new \App\Models\UserTransactionModel();
            $userRole = $userdata['role'];

            if ($userRole === 'admin') {
                // 👉 ADMIN: Fetch all pending requests from everyone
                $transactions = $txnModel->getAllTransactions();
                $message      = 'All platform transactions retrieved successfully.';
            } else {
                // 👉 USER: Fetch only this specific user's pending requests
                $transactions = $txnModel->getPendingTransactionsByUserId($user_id);
                $message      = 'Your pending deposit requests retrieved successfully.';
            }

            // ================= 4. SUCCESS RESPONSE =================
            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => $message,
                'data'      => $transactions
            ]);

        } catch (\Exception $e) {
            // Safely catch database connection or query failures
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'A database error occurred while retrieving records.',
                'data'      => []
            ]);
        }
    }

    public function start_trade()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            redirect()->to('/login')->send();
            exit;
        }
        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);
        if($userdata && $userdata['role'] == 'user'){
            // Load the correct model
            $walletModel = new \App\Models\UserWalletAssociationModel();

            // Fetch wallet data for the logged-in user
            $walletData = $walletModel
                ->where('user_id', $user_id)
                ->first();

            $data = [
                'title'              => 'Start Trade',
                'userdata'           => $userdata,
                'walletData'         => $walletData,
                'investment_balance' => $walletData['investment_balance'] ?? 0,
                'current_balance'    => $walletData['current_balance'] ?? 0,
                'deposit_balance'    => $walletData['deposit_balance'] ?? 0,
                'withdrawal_balance' => $walletData['withdrawal_balance'] ?? 0,
            ];

            return view('layout/header', $data)
            . view('user/start_trade')
            . view('layout/footer');
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function get_member_details()
    {
        // ================= 1. AUTHENTICATION & SESSION CHECK =================
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Session expired. Please login again.',
                    'data'      => []
                ]);
            }
            redirect()->to('/login')->send();
            exit;
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if ($userdata && $userdata['role'] == 'user') {
            $memberId = $this->request->getPost('member_id');

            // Validate member ID input
            if (empty($memberId)) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Invalid member ID provided.',
                    'data'      => []
                ]);
            }

            
            $memberDetails = $this->userModel->getUserById($memberId);

            if ($memberDetails) {
                return $this->response->setJSON([
                    'Resp_code' => 'RCS',
                    'Resp_desc' => 'Member details retrieved successfully.',
                    'data'      => $memberDetails
                ]);
            } else {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'No member found with the provided ID.',
                    'data'      => []
                ]);
            }

        } else {
            session()->destroy();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Unauthorized access. Account verification failed.',
                    'data'      => []
                ]);
            }
            redirect()->to('/login')->send();
            exit;
        }
    }

    public function submitInvestment()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'Something went wrong.',
            'data'      => [],
            'csrf_hash' => csrf_hash()
        ];

        $loggedInUserId   = session()->get('user_id');
        $memberId         = trim($this->request->getPost('member_id'));
        // $investmentAmount = (float) $this->request->getPost('investment_amount');
        $packageKey     = trim($this->request->getPost('investment_amount'));
        $actionType = trim($this->request->getPost('action_type'));

        if (!in_array($actionType, ['initial_topup', 're_topup'])) {
            $response['Resp_desc'] = 'Invalid action type.';
            return $this->response->setJSON($response);
        }

        if (empty($memberId)) {
            $response['Resp_desc'] = 'Member ID is required.';
            return $this->response->setJSON($response);
        }

        // ---------------- Validate Package Key ----------------
        if (empty($packageKey)) {
            $response['Resp_desc'] = 'Please select an investment package.';
            return $this->response->setJSON($response);
        }

        $packages = default_package();
        if (!array_key_exists($packageKey, $packages)) {
            $response['Resp_desc'] = 'Invalid investment package selected.';
            return $this->response->setJSON($response);
        }

        $investmentAmount = (float) str_replace(',', '', $packages[$packageKey][1]);

        if ($investmentAmount <= 0) {
            $response['Resp_desc'] = 'Invalid investment amount.';
            return $this->response->setJSON($response);
        }


        $walletModel         = new \App\Models\UserWalletAssociationModel();
        $investmentTxnModel  = new \App\Models\InvestmentTransactionModel();
        $db                  = \Config\Database::connect();

        // ---------------- Check Member ----------------
        $member = $this->userModel
            ->where('user_id', $memberId)
            ->first();

        if (!$member) {
            $response['Resp_desc'] = 'Member ID not found.';
            return $this->response->setJSON($response);
        }

        $memberStatus = strtolower($member['status']);
        

        // Initial Topup → only pending members
        if ($actionType === 'initial_topup' && $memberStatus !== 'pending') {
            $response['Resp_desc'] = 'Initial topup is allowed only for pending members.';
            return $this->response->setJSON($response);
        }

        // Re-Topup → only active members
        if ($actionType === 're_topup' && $memberStatus !== 'active') {
            $response['Resp_desc'] = 'Re-topup is allowed only for active members.';
            return $this->response->setJSON($response);
        }

        // ---------------- Sender Wallet ----------------
        $senderWallet = $walletModel
            ->where('user_id', $loggedInUserId)
            ->first();

        if (!$senderWallet) {
            $response['Resp_desc'] = 'Your wallet was not found.';
            return $this->response->setJSON($response);
        }

        if ((float)$senderWallet['deposit_balance'] < $investmentAmount) {
            $response['Resp_desc'] = 'Insufficient deposit balance.';
            return $this->response->setJSON($response);
        }

        // ---------------- Receiver Wallet ----------------
        $receiverWallet = $walletModel
            ->where('user_id', $memberId)
            ->first();

        if (!$receiverWallet) {
            $response['Resp_desc'] = 'Member wallet not found.';
            return $this->response->setJSON($response);
        }

        // Save balances before update
        $fromBalanceBefore = (float) $senderWallet['deposit_balance'];
        $fromBalanceAfter  = $fromBalanceBefore - $investmentAmount;

        $toBalanceBefore = (float) $receiverWallet['investment_balance'];
        $toBalanceAfter  = $toBalanceBefore + $investmentAmount;

        // Determine transaction type
        $type = ($toBalanceBefore == 0) ? 'initial_topup' : 're_topup';

        // ---------------- Database Transaction ----------------
        $db->transBegin();

        // 1. Deduct sender deposit balance
        $walletModel->update($senderWallet['id'], [
            'deposit_balance' => $fromBalanceAfter,
            'updated_by'      => $loggedInUserId,
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        // 2. Add receiver investment balance
        $walletModel->update($receiverWallet['id'], [
            'investment_balance' => $toBalanceAfter,
            'current_balance'    => $investmentAmount,
            'updated_by'         => $loggedInUserId,
            'updated_at'         => date('Y-m-d H:i:s')
        ]);

        // 3. Activate member
        if ($actionType === 'initial_topup') {
            $this->userModel
                ->where('user_id', $memberId)
                ->set([
                    'status'     => 'active',
                    'updated_at' => date('Y-m-d H:i:s')
                ])
                ->update();
        }

        // 4. Insert transaction history
        $investmentTxnModel->insert([
            'from_user_id'        => $loggedInUserId,
            'to_user_id'          => $memberId,
            'investment_amount'   => $investmentAmount,

            'from_balance_before' => $fromBalanceBefore,
            'from_balance_after'  => $fromBalanceAfter,

            'to_balance_before'   => $toBalanceBefore,
            'to_balance_after'    => $toBalanceAfter,

            'type' => $actionType,
            'status'              => 'success',
            'remarks'             => 'Investment transferred successfully.',
            'created_by'          => $loggedInUserId,
        ]);

        // Check all queries succeeded
        if ($db->transStatus() === false) {
            $db->transRollback();

            $response['Resp_desc'] = 'Transaction failed. Please try again.';
            return $this->response->setJSON($response);
        }

        $db->transCommit();

        $response['Resp_code'] = 'SUCCESS';
        $response['Resp_desc'] = 'Investment transferred successfully.';
        $response['data'] = [
            'member_id'         => $memberId,
            'member_name'       => $member['name'] ?? '',
            'investment_amount' => $investmentAmount,
            'remaining_balance' => $fromBalanceAfter
        ];

        return $this->response->setJSON($response);
    }

    public function re_topup()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            redirect()->to('/login')->send();
            exit;
        }
        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);
        if($userdata && $userdata['role'] == 'user'){
            // Load the correct model
            $walletModel = new \App\Models\UserWalletAssociationModel();

            // Fetch wallet data for the logged-in user
            $walletData = $walletModel
                ->where('user_id', $user_id)
                ->first();

            $data = [
                'title'              => 'Re - Trade',
                'userdata'           => $userdata,
                'walletData'         => $walletData,
                'investment_balance' => $walletData['investment_balance'] ?? 0,
                'current_balance'    => $walletData['current_balance'] ?? 0,
                'deposit_balance'    => $walletData['deposit_balance'] ?? 0,
                'withdrawal_balance' => $walletData['withdrawal_balance'] ?? 0,
            ];

            return view('layout/header', $data)
            . view('user/re_topup')
            . view('layout/footer');
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function trade_history()
    {
        // ================= 1. AUTHENTICATION & SESSION CHECK =================
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            return redirect()->to('/login');
        }

        $user_id  = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        // ================= 2. VERIFY USER EXISTENCE & ROLE =================
        if ($userdata && $userdata['role'] == 'user') {
            
            // Prepare data to send to the view (Title, User details for sidebar, etc.)
            $data = [
                'title' => 'Trade History'
            ];

            // Load the view file located at: app/Views/income_history.php
            return view('layout/header', $data)
                . view('user/investment_transaction')
                . view('layout/footer');

        } else {
            // Fallback: Account verification completely failed
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function income_history_tbl()
    {
        // ================= 1. UNIVERSAL LOGIN CHECK =================
        // Just check if logged in (Do NOT restrict by role here)
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Session expired. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 2. STRICT USER EXISTENCE CHECK =================
        $user_id  = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        // If user doesn't exist in DB anymore, destroy session immediately
        if (!$userdata) {
            session()->destroy();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Account verification failed. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 3. FETCH DATA BASED ON ROLE =================
        try {
            $txnModel = new \App\Models\InvestmentTransactionModel();
            $userRole = $userdata['role'];
            
            // 👉 USER: Fetch only this specific user's pending requests
            $transactions = $txnModel->getTransactionsByUserId($user_id);
            $message      = 'Your income transaction history requests retrieved successfully.';

            // ================= 4. SUCCESS RESPONSE =================
            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => $message,
                'data'      => $transactions
            ]);

        } catch (\Exception $e) {
            // Safely catch database connection or query failures
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'A database error occurred while retrieving records.',
                'data'      => []
            ]);
        }
    }

    public function wallet_withdraw()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            redirect()->to('/login')->send();
            exit;
        }
        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);
        if($userdata && $userdata['role'] == 'user'){

            $walletModel = new \App\Models\UserWalletAssociationModel();

            // Fetch wallet data for the logged-in user
            $walletData = $walletModel
                ->where('user_id', $user_id)
                ->first();

            $data = [
                'title'              => 'Withdraw Fund',
                'userdata'           => $userdata,
                'walletData'         => $walletData,
                'investment_balance' => $walletData['investment_balance'] ?? 0,
                'current_balance'    => $walletData['current_balance'] ?? 0,
                'deposit_balance'    => $walletData['deposit_balance'] ?? 0,
                'withdrawal_balance' => $walletData['withdrawal_balance'] ?? 0,
                'income_balance'     => $walletData['income_balance'] ?? 0, 
            ];

            return view('layout/header', $data)
            . view('user/wallet/wallet_withdraw')
            . view('layout/footer');
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function submitWithdrawal()
    {
        $response = [
            'Resp_code' => 'ERR',
            'Resp_desc' => 'Something went wrong.',
            'data'      => [],
            'csrf_hash' => csrf_hash()
        ];

        $userId         = session()->get('user_id');
        $withdrawAmount = (float) $this->request->getPost('withdraw_amount');
        $transactionPin = trim($this->request->getPost('transaction_pin'));

        // ---------------- Validation ----------------
        if ($withdrawAmount <= 0) {
            $response['Resp_desc'] = 'Please enter a valid withdrawal amount.';
            return $this->response->setJSON($response);
        }

        if (empty($transactionPin)) {
            $response['Resp_desc'] = 'Transaction PIN is required.';
            return $this->response->setJSON($response);
        }

        // ---------------- Fetch User ----------------
        $user = $this->userModel
            ->where('user_id', $userId)
            ->first();

        if (!$user) {
            $response['Resp_desc'] = 'User not found.';
            return $this->response->setJSON($response);
        }

        // ---------------- Verify Transaction PIN ----------------
        // Adjust this if you use password_hash()
        if ($user['txn_pin'] !== $transactionPin) {
            $response['Resp_desc'] = 'Invalid transaction PIN.';
            return $this->response->setJSON($response);
        }

        // ---------------- Wallet ----------------
        $walletModel = new \App\Models\UserWalletAssociationModel();
        $wallet      = $walletModel
            ->where('user_id', $userId)
            ->first();

        if (!$wallet) {
            $response['Resp_desc'] = 'Wallet not found.';
            return $this->response->setJSON($response);
        }

        $incomeBalance   = (float) ($wallet['income_balance'] ?? 0);
        $withdrawBalance = (float) ($wallet['withdrawal_balance'] ?? 0);

        $availableBalance = $incomeBalance - $withdrawBalance;

        if ($withdrawAmount > $availableBalance) {
            $response['Resp_desc'] = 'Withdrawal amount exceeds available balance.';
            return $this->response->setJSON($response);
        }

        // ---------------- Calculations ----------------
        $chargedAmt = round($withdrawAmount * 0.10, 2); // 10%
        $paidAmt    = round($withdrawAmount - $chargedAmt, 2);

        // New balances
        $newIncomeBalance   = $incomeBalance - $withdrawAmount;
        $newWithdrawBalance = $withdrawBalance + $withdrawAmount;
        $remaining_balance = $newIncomeBalance - $newWithdrawBalance;
       
        $withdrawTxnModel = new \App\Models\WithdrawTransactionModel();
        $db               = \Config\Database::connect();

        // ---------------- DB Transaction ----------------
        $db->transBegin();

        // 1. Insert withdrawal request
        // Prepare insert data for withdraw_transactions table
        $insertData = [
            'user_id'      => $userId,
            'req_amt'      => $withdrawAmount,
            'charged_amt'  => $chargedAmt,
            'paid_amt'     => $paidAmt,
            'status'       => 'pending',
            'remarks'      => 'Withdrawal request submitted.',
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'   => $userId,
        ];

        // Insert withdrawal request
        $withdrawTxnModel->insert($insertData);

        // 2. Update wallet
        $walletModel->update($wallet['id'], [
            //'income_balance'   => $newIncomeBalance,
            'withdrawal_balance' => $newWithdrawBalance,
            'updated_by'       => $userId,
            'updated_at'       => date('Y-m-d H:i:s')
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();

            $response['Resp_desc'] = 'Withdrawal request failed. Please try again.';
            return $this->response->setJSON($response);
        }

        $db->transCommit();

        // ---------------- Success ----------------
        $response['Resp_code'] = 'SUCCESS';
        $response['Resp_desc'] = 'Withdrawal request submitted successfully.';
        $response['data'] = [
            'withdraw_amount'   => $withdrawAmount,
            'charged_amount'    => $chargedAmt,
            'paid_amount'       => $paidAmt,
            'remaining_balance' => $incomeBalance - $newWithdrawBalance
        ];

        return $this->response->setJSON($response);
    }

    public function withdraw_report()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') != 'user') {
            redirect()->to('/login')->send();
            exit;
        }
        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);
        if($userdata && $userdata['role'] == 'user'){

            $data = [
                'title' => 'Withdrawal History'
            ];

            return view('layout/header', $data)
            . view('user/wallet/withdraw_report')
            . view('layout/footer');
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function withdraw_history_tbl()
    {
        // ================= 1. UNIVERSAL LOGIN CHECK =================
        // Just check if logged in (Do NOT restrict by role here)
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Session expired. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 2. STRICT USER EXISTENCE CHECK =================
        $user_id  = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        // If user doesn't exist in DB anymore, destroy session immediately
        if (!$userdata) {
            session()->destroy();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'Resp_code' => 'ERR',
                    'Resp_desc' => 'Account verification failed. Please login again.',
                    'data'      => []
                ]);
            }
            return redirect()->to('/login');
        }

        // ================= 3. FETCH DATA BASED ON ROLE =================
        try {
            $txnModel = new \App\Models\WithdrawTransactionModel();
            $userRole = $userdata['role'];

            if ($userRole === 'admin') {
                // 👉 ADMIN: Fetch all pending requests from everyone
                $transactions = $txnModel->getAllTransactions();
                $message      = 'All platform transactions retrieved successfully.';
            } else {
                // 👉 USER: Fetch only this specific user's pending requests
                $transactions = $txnModel->getPendingTransactionsByUserId($user_id);
                $message      = 'Your pending withdrawal requests retrieved successfully.';
            }

            // ================= 4. SUCCESS RESPONSE =================
            return $this->response->setJSON([
                'Resp_code' => 'RCS',
                'Resp_desc' => $message,
                'data'      => $transactions
            ]);

        } catch (\Exception $e) {
            // Safely catch database connection or query failures
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => 'A database error occurred while retrieving records.',
                'data'      => []
            ]);
        }
    }

    // public function payout_history()
    // {
    //     // ================= 1. UNIVERSAL LOGIN CHECK =================
    //     // Just check if logged in (Do NOT restrict by role here)
    //     if (!session()->get('isLoggedIn')) {
    //         if ($this->request->isAJAX()) {
    //             return $this->response->setJSON([
    //                 'Resp_code' => 'ERR',
    //                 'Resp_desc' => 'Session expired. Please login again.',
    //                 'data'      => []
    //             ]);
    //         }
    //         return redirect()->to('/login');
    //     }

    //     // ================= 2. STRICT USER EXISTENCE CHECK =================
    //     $user_id  = session()->get('user_id');
    //     $userdata = $this->userModel->getUserById($user_id);

    //     // If user doesn't exist in DB anymore, destroy session immediately
    //     if (!$userdata) {
    //         session()->destroy();
            
    //         if ($this->request->isAJAX()) {
    //             return $this->response->setJSON([
    //                 'Resp_code' => 'ERR',
    //                 'Resp_desc' => 'Account verification failed. Please login again.',
    //                 'data'      => []
    //             ]);
    //         }
    //         return redirect()->to('/login');
    //     }

    //     // ================= 3. FETCH DATA BASED ON ROLE =================
    //     try {
    //         $txnModel = new \App\Models\UserTransactionModel();
    //         $userRole = $userdata['role'];

    //         if ($userRole === 'admin') {
    //             // 👉 ADMIN: Fetch all pending requests from everyone
    //             $transactions = $txnModel->getAllTransactions();
    //             $message      = 'All platform transactions retrieved successfully.';
    //         } else {
    //             // 👉 USER: Fetch only this specific user's pending requests
    //             $transactions = $txnModel->getPendingTransactionsByUserId($user_id);
    //             $message      = 'Your pending deposit requests retrieved successfully.';
    //         }
    //         print_r($transactions);
    //         die;

    //         // ================= 4. SUCCESS RESPONSE =================
    //         return $this->response->setJSON([
    //             'Resp_code' => 'RCS',
    //             'Resp_desc' => $message,
    //             'data'      => $transactions
    //         ]);

    //     } catch (\Exception $e) {
    //         // Safely catch database connection or query failures
    //         return $this->response->setJSON([
    //             'Resp_code' => 'ERR',
    //             'Resp_desc' => 'A database error occurred while retrieving records.',
    //             'data'      => []
    //         ]);
    //     }
    // }
}
