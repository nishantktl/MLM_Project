<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');   
$routes->get('/login', 'Login::index');
$routes->post('/login/submit', 'Login::submit');
$routes->get('/logout', 'Login::logout');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/all_users', 'Admin::all_users');
$routes->get('/admin/get_user_list', 'Admin::get_user_list');
$routes->get('/register', 'Register::index');
$routes->post('/register/submit', 'Register::submit');
