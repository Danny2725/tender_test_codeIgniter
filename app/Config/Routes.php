<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'TenderController::store', ['filter' => 'auth']);
$routes->get('/tender/create', 'TenderController::create', ['filter' => 'auth']);
$routes->post('/tender/createTender', 'TenderController::createTender', ['filter' => 'auth']);
$routes->get('/tender/list_contractor', 'TenderController::listContractor', ['filter' => 'auth']);
$routes->get('/tender/list_supplier', 'TenderController::listSupplier', ['filter' => 'auth']);


$routes->put('tender/update/(:num)', 'TenderController::updateTender/$1');
$routes->get('tender/edit/(:num)', 'TenderController::editTender/$1');
$routes->delete('tender/delete/(:num)', 'TenderController::deleteTender/$1');
$routes->get('tender/view/(:num)', 'TenderController::viewTender/$1');
// Auth 
$routes->post('auth/register', 'AuthController::register');
$routes->get('/register', 'AuthController::signUp');
$routes->get('/login', 'AuthController::index');
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/user', 'AuthController::getUserInfo', ['filter' => 'auth']);
$routes->get('/logout', 'AuthController::logout', ['filter' => 'auth']);