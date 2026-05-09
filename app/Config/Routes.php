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

$routes->get('/logout', 'Auth::logout');

$routes->get('/profil', 'Profil::index');

$routes->get('/profil/objectif', 'Objectifs::index');
$routes->post('/profil/objectif', 'Objectifs::save');

$routes->get('/profil/modifier-infos-perso', 'Profil::editPerso');
$routes->post('/profil/modifier-infos-perso', 'Profil::updatePerso');

$routes->get('/profil/modifier-infos-sante', 'Profil::editSante');
$routes->post('/profil/modifier-infos-sante', 'Profil::updateSante');

$routes->post('/profil/objectif', 'Profil::updateObjectif');

$routes->get('/regimes', 'Regimes::index');
$routes->get('/regimes/(:num)/activites', 'Activites::recommandees/$1');
$routes->get('/regimes/(:num)/pdf', 'Regimes::pdf/$1');

$routes->get('/wallet', 'Wallet::index');
$routes->post('/wallet/recharge', 'Wallet::recharge');

$routes->post('/gold/activer', 'Gold::activate');

$routes->get('/admin/login', 'AdminAuth::login');
$routes->post('/admin/login', 'AdminAuth::authenticate');

$routes->get('/admin/logout', 'AdminAuth::logout');

$routes->get('/admin/dashboard', 'AdminDashboard::index');

$routes->get('/admin/regimes', 'AdminRegimes::index');
$routes->get('/admin/regimes/creer', 'AdminRegimes::create');
$routes->post('/admin/regimes/creer', 'AdminRegimes::store');

$routes->get('/admin/regimes/(:num)/modifier', 'AdminRegimes::edit/$1');
$routes->post('/admin/regimes/(:num)/modifier', 'AdminRegimes::update/$1');

$routes->post('/admin/regimes/(:num)/supprimer', 'AdminRegimes::delete/$1');

$routes->post('/admin/regimes/(:num)/activites', 'Activites::associerAuRegime/$1');

$routes->get('/admin/activites', 'AdminActivites::index');
$routes->get('/admin/activites/creer', 'AdminActivites::create');
$routes->post('/admin/activites/creer', 'AdminActivites::store');

$routes->get('/admin/activites/(:num)/modifier', 'AdminActivites::edit/$1');
$routes->post('/admin/activites/(:num)/modifier', 'AdminActivites::update/$1');

$routes->post('/admin/activites/(:num)/supprimer', 'AdminActivites::delete/$1');