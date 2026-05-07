<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', static function () {
    return redirect()->to('/login');
});
$routes->get('/inscription', 'Auth::registerStepOne');
$routes->post('/inscription', 'Auth::storeRegisterStepOne');
$routes->get('/inscription/etape-2', 'Auth::registerStepTwo');
$routes->post('/inscription/etape-2', 'Auth::storeRegisterStepTwo');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');
$routes->get('/profil', 'Profil::index');
$routes->get('/admin/login', 'AdminAuth::login');
$routes->post('/admin/login', 'AdminAuth::authenticate');
$routes->get('/admin/logout', 'AdminAuth::logout');
