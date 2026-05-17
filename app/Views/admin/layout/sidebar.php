<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
        <div class="profile-desc">
            <div class="profile-pic">
                <div class="count-indicator">
                <img class="img-xs rounded-circle " src="assets/images/faces/face15.jpg" alt="">
                <span class="count bg-success"></span>
            </div>
            <div class="profile-name">
                <h5 class="mb-0 font-weight-normal"><?= isset($data['username']) ? $data['username'] : 'User'; ?></h5>
            </div>
            </div>
            <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-onepassword  text-info"></i>
                </div>
                </div>
                <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                </div>
            </a>
            </div>
        </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
            <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">User Management</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/all_users') ?>">All Members</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/active_members') ?>">Active Members</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/pending_members') ?>">Pending Members</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/user_details') ?>">User Details</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/block_user') ?>">For Block User</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/datewise_active_members') ?>">Datewise Active Member List</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#manage_income" aria-expanded="false" aria-controls="manage_income">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">Manage Income</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="manage_income">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/income_history') ?>">User Income History</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#manage_fund" aria-expanded="false" aria-controls="manage_fund">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">Manage Fund</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="manage_fund">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/add_fund') ?>">Add Admin Fund</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/user_fund_requests') ?>">User Fund Request</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#withdraw_details" aria-expanded="false" aria-controls="withdraw_details">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">Withdraw Details</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="withdraw_details">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/payout_history') ?>">Payout History</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?= base_url('admin/qr_code') ?>">
            <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Add Qr Code</span>
            </a>
        </li>
    </ul>
</nav>