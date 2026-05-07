<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/inscription', 'Auth::registerStepOne');
$routes->post('/inscription', 'Auth::storeRegisterStepOne');
$routes->get('/inscription/etape-2', 'Auth::registerStepTwo');
$routes->post('/inscription/etape-2', 'Auth::storeRegisterStepTwo');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');
$routes->get('/profil', 'Profil::index');
$routes->get('/profil/modifier-infos-perso', 'Profil::editPerso');
$routes->post('/profil/modifier-infos-perso', 'Profil::updatePerso');
$routes->get('/profil/modifier-infos-sante', 'Profil::editSante');
$routes->post('/profil/modifier-infos-sante', 'Profil::updateSante');
