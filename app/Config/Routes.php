<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/inscription', 'Auth::registerStepOne');
$routes->get('/inscription/etape-2', 'Auth::registerStepTwo');
$routes->get('/login', 'Auth::login');
$routes->get('/produits', 'Produit::index');
$routes->get('/produits/(:num)', 'Produit::show/$1');
$routes->get('/produit/(:num)', 'Produit::show/$1');
