<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home/coba-parameter/(:alpha)/(:num)/(:alphanum)/','Home::belajar_segment/$1/$2/$3');
// $routes->method('link_samaran', 'Controller::Function');