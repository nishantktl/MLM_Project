<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->post('/login/submit', 'Login::user_sign_in');
$routes->get('/logout', 'Login::logout');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/all_users', 'Admin::all_users');
$routes->get('/admin/active_members', 'Admin::active_members');
$routes->get('/admin/pending_members', 'Admin::pending_members');
$routes->get('/admin/block_user', 'Admin::block_members');
$routes->get('/admin/get_user_list', 'Admin::get_user_list');
$routes->get('/admin/user_details', 'Admin::user_details');
$routes->get('/admin/get_active_user_list', 'Admin::get_active_user_list');
$routes->get('/admin/get_pending_user_list', 'Admin::get_pending_user_list');
$routes->get('/admin/get_block_user_list', 'Admin::get_block_user_list');
$routes->get('/register', 'Register::index');
$routes->post('/register/submit', 'Register::user_signup');
$routes->post('/user/submit_fund_deposit', 'User::submit_fund_deposit');
$routes->get('/fund_request', 'User::index');
$routes->get('/deposit_history', 'User::deposit_history');
$routes->get('/trade_history', 'User::trade_history');
$routes->get('/user/deposit_history_tbl', 'User::deposit_history_tbl');
$routes->post('/user/get_member_details', 'User::get_member_details');
$routes->post('/user/submit-investment', 'User::submitInvestment');
$routes->post('user/submit-re-topup', 'User::submitReTopup');
$routes->get('/re_trade', 'User::re_topup');
$routes->get('/start_trade', 'User::start_trade');
$routes->get('/user/income_history_tbl', 'User::income_history_tbl');
$routes->get('/admin/user_fund_requests', 'Admin::fund_request_tbl');
$routes->get('/p2p_transfer', 'User::p2p_transfer');
$routes->post('/admin/process_deposit_request', 'Admin::process_deposit_request');
$routes->get('/wallet_withdraw', 'User::wallet_withdraw');
$routes->get('/withdraw_report', 'User::withdraw_report');
$routes->post('user/submit-withdrawal', 'User::submitWithdrawal');
$routes->get('/user/withdraw_history_tbl', 'User::withdraw_history_tbl');
$routes->get('/admin/payout_history', 'Admin::payout_history');
$routes->post('/admin/process_withdraw_request', 'Admin::process_withdraw_request');
$routes->get('/admin/qr_code', 'Admin::qr_code');
$routes->post('/admin/save_qr_code', 'Admin::save_qr_code');
$routes->get('referral/(:any)', 'User::referral/$1');
$routes->post('/admin/login_as_user', 'Admin::login_as_user');
$routes->get('admin/get_user_details', 'Admin::get_user_details');
$routes->post('admin/update_user_details', 'Admin::update_user_details');
$routes->get('admin/datewise_active_members', 'Admin::datewise_active_members');
$routes->post('admin/get_active_users_by_date', 'Admin::get_active_users_by_date');