<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Register extends BaseController
{
    public function index()
    {
        return view('Register');
    }

    public function user_signup()
    {
        $data = [
            'Resp_code' => 'ERR',
            'Resp_desc' => '',
            'data' => []
        ];

        $params = $this->request->getPost();

        $params['username'] = isset($params['username']) ? trim($params['username']) : '';
        $params['phone'] = isset($params['phone']) ? trim($params['phone']) : '';
        $params['email'] = isset($params['email']) ? trim($params['email']) : '';
        $params['password'] = isset($params['password']) ? $params['password'] : '';
        $params['sponser_id'] = isset($params['sponser_id']) ? trim($params['sponser_id']) : '';

        $username = $params['username'];
        $phone = $params['phone'];
        $email = $params['email'];
        $password = $params['password'];
        $sponser_id = $params['sponser_id'];

        
        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) < 3 || strlen($username) > 100) {
            $errors['username'] = 'Username must be between 3 and 100 characters';
        }

        if ($phone === '') {
            $errors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Phone number must be 10 digits';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is not valid';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if( $sponser_id === '') {
            $errors['sponser_id'] = 'Sponser ID is required';
        }

        if (!empty($errors)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Validation failed';
            $data['data'] = $errors;
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

      
        $userModel = new \App\Models\UserModel();

        if ($userModel->userExistsByUsername($username)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Username already exists';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        if(!$userModel->userIdExists($sponser_id)){
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid Sponser ID';
            $data['data'] = $errors;
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if ($userModel->userExistsByPhone($phone)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Phone number already exists';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        if ($userModel->userExistsByEmail($email)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Email already exists';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        $lastUserId = $userModel->getLastUserId();
        if ($lastUserId === null) {
            $userId = 'MW0001';
        } else {
            $lastNumber = (int) substr($lastUserId, 2);
            $newNumber = $lastNumber + 1;
            $userId = 'MW' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        while ($userModel->userIdExists($userId)) {
            $lastNumber = (int) substr($userId, 2);
            $newNumber = $lastNumber + 1;
            $userId = 'MW' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        $txnPin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'user_id' => $userId,
            'parent_id' => $sponser_id,
            'username' => $username,
            'phone' => $phone,
            'email' => $email,
            'hash_password' => $hashedPassword,
            'password' => $password,
            'status' => 'PENDING',
            'txn_pin' => $txnPin,
            'created_by' => $sponser_id
        ];

        $result = $userModel->registerUserWithWallet($userData);
        if (!$result['success']) {
            return $this->response->setJSON([
                'Resp_code' => 'ERR',
                'Resp_desc' => $result['message'],
                'data'    =>  [],
            ]);
        }

        return $this->response->setJSON([
            'Resp_code' => 'RCS',
            'Resp_desc' => 'Registration successful.',
            'data' => [
                'user_id'  => $userData['user_id'],
                'username' => $userData['username'],
                'email'    => $userData['email'],
                'phone'    => $userData['phone'],
                'txn_pin'  => $userData['txn_pin'],
                'password' => $password,
                'bonus'    => 50.00,
            ],
        ]);
    }
}
