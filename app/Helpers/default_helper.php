<?php

if (! function_exists('dd'))
{
    /**
     * Dump and die helper.
     *
     * @param mixed $data
     * @return void
     */
    function dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }
}

function getuserdata(): ?array
{
    $session = session_data();

    $userId = $session['user_id'] ?? null;

    if (!$userId) {
        return null;
    }

    $userModel = new \App\Models\UserModel();   
    $user = $userModel->getUserById($userId);

    return $user ?: null;
}

if (!function_exists('user_sidebar')) {
    function user_sidebar($data = []): string
    {
        // Safely extract variables first
        $username = isset($data['username']) ? esc($data['username']) : 'User';
        
        $url_dashboard = base_url('dashboard');
        $url_deposit   = base_url('fund_request');
        $url_history   = base_url('admin/active_members');
        $url_p2p_send  = base_url('admin/pending_members');
        $url_p2p_recv  = base_url('admin/user_details');
        $url_wallet    = base_url('admin/block_user');

        // Return pure HTML string directly
        return '
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal">' . $username . '</h5>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="' . $url_dashboard . '">
              <span class="menu-icon"><i class="mdi mdi-speedometer"></i></span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon"><i class="mdi mdi-laptop"></i></span>
              <span class="menu-title">Manage Fund</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="' . $url_deposit . '">Deposit Fund</a></li>
                <li class="nav-item"> <a class="nav-link" href="' . $url_history . '">Deposit History</a></li>
                <li class="nav-item"> <a class="nav-link" href="' . $url_p2p_send . '">P2P Transfer</a></li>
                <li class="nav-item"> <a class="nav-link" href="' . $url_p2p_recv . '">P2P Receive</a></li>
                <li class="nav-item"> <a class="nav-link" href="' . $url_wallet . '">Income To Purchase Wallet</a></li>
              </ul>
            </div>
          </li>   
        </ul>';
    }
}

if(!function_exists('_defaultpackage')){
  function default_package(){
    return ['pk_1' => ['1000','1000'], 'pk_2' => ['2000','2000'], 'pk_3' => ['4000','4000'],'pk_4' => ['10,000','10000'], 'pk_5' => ['20,000','20000'],'pk_6' => ['50,000','50000'],'pk_7' => ['1 Lakh','100000'],'pk_8' => ['2 Lakh','200000'],'pk_9' => ['5 Lakh','500000'],'pk_10'=>['10 Lakh','1000000']];
  }
}

use App\Models\QrCodeModel;

if (!function_exists('get_qr_code')) {
    function get_qr_code()
    {
        $qrModel = new QrCodeModel();

        $qrData = $qrModel
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$qrData) {
            return null;
        }

        // Add full URL for direct use in views
        $qrData['qr_image_url'] = base_url($qrData['qr_image']);

        return $qrData;
    }
}

if(!function_exists('prx')){
  function prx($data){
    print_r($data);
    die;
  }
}