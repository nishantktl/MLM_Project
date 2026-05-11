<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index()
    {

        if (session()->get('isLoggedIn') || session()->get('role') == 'admin') {
            redirect()->to('/admin/dashboard')->send();
            exit;
        }
        if(session()->get('isLoggedIn') || session()->get('role') == 'user') {
            redirect()->to('/dashboard')->send();
            exit;
        }
        return view('Login');
    }

    public function submit()
    {
        $data = [
            'Resp_code' => 'ERR',
            'Resp_desc' => '',
            'data' => []
        ];

        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        // Validate input
        if (empty($email) || empty($password)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Email and password are required';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid email format';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $userModel = new \App\Models\UserModel();
        
        // Get user by email
        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'User not found';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Check user status
        if ($user['status'] === 'BLOCKED') {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Your account has been blocked. Please contact support.';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Verify password
        if (md5($password) !== $user['password']) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid password';
            $data['data'] = [];
            return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        session()->set([
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'parent_id' => $user['parent_id'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'status' => $user['status'],
            'role'      => $user['role'] ?? 'user',
            'isLoggedIn' => true,
        ]);

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Login successful';
        $data['data'] = [
            'redirect_url' => ($user['role'] ?? 'user') === 'admin' ? base_url('admin/dashboard') : base_url('dashboard'),
        ];
        return $this->response->setJSON($data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
