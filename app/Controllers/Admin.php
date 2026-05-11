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
}