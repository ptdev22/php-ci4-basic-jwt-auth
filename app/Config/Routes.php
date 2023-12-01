<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->post('/api/create-user', 'UserApiController::create');
$routes->post('/api/login-user', 'UserApiController::login');
$routes->post('/api/read-user', 'UserApiController::readUser');