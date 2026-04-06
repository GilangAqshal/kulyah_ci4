<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Admin::login');
$routes->get('/admin/login-admin', 'Admin::login');
$routes->post('/admin/autentikasi_login', 'Admin::autentikasi');
$routes->get('/admin/dashboardAdmin', 'Admin::dashboard');
$routes->get('/admin/logout', 'Admin::logout');
