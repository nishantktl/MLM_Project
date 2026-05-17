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

    public function user_sign_in()
    {
        $data = [
            'Resp_code' => 'ERR',
            'Resp_desc' => '',
            'data' => []
        ];

        $params = $this->request->getPost();

        $params['login_id'] = isset($params['login_id'])
            ? trim($params['login_id'])
            : '';

        $params['password'] = isset($params['password'])
            ? $params['password']
            : '';

        if ($params['login_id'] === '' || $params['password'] === '') {
            $data['Resp_desc'] = 'User ID/Username and password are required.';
            return $this->response->setJSON($data);
        }

        $userModel = new \App\Models\UserModel();
        
        // Get user by email
        $loginId = trim($params['login_id']);

        $user = $userModel
            ->groupStart()
                ->where('user_id', $loginId)
                ->orWhere('username', $loginId)
            ->groupEnd()
            ->first();

        if (!$user) {
            $data['Resp_code'] = 'ERR';
            $data['Resp_desc'] = 'Invalid User ID/Username or password.';
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
        if (!password_verify($params['password'], $user['hash_password'])) {
        $data['Resp_desc'] = 'Invalid password';
        return $this->response->setJSON($data)
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
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
