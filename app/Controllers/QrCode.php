<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class QrCode extends BaseController
{
    public function index()
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
}
