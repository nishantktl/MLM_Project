<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'user') {
            session()->destroy();
            return redirect()->to('/login');
        }

        $user_id = session()->get('user_id');
        $userdata = $this->userModel->getUserById($user_id);

        if($userdata && $userdata['role'] == 'user'){
            $data = ['title' => 'User Dashboard'];
            return view('layout/header', $data)
            . view('UserDashboard')
            . view('layout/footer');   
        } else {
            session()->destroy();
            return redirect()->to('/login');
        }
    }
}
