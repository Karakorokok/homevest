<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);
$routes->get('/error', 'ErrorController::error');

$routes->get('/', 'LoginController::login');

$routes->match(['get', 'post'], '/login', 'LoginController::login');
$routes->match(['get', 'post'], '/signup', 'LoginController::signup');
$routes->match(['get', 'post'], '/logout', 'LoginController::logout');
$routes->match(['get', 'post'], '/otp', 'LoginController::otp');

// User - Agent Section
$routes->match(['get', 'post'], '/home', 'UserController::userHome');
$routes->match(['get', 'post'], '/message', 'MessageController::messages');
$routes->match(['get', 'post'], '/messagelanding', 'MessageController::messageLanding');
$routes->match(['get', 'post'], '/homeslisting', 'UserController::homesListing');
$routes->post('/homeslisting/removemodel', 'UserController::homesListingDelete');
$routes->post('/homeslisting/savemodelphoto', 'UserController::saveModelPhoto');
$routes->post('/homeslisting/deletemodelphoto', 'UserController::deleteModelPhoto');
$routes->match(['get', 'post'], '/searchquery', 'UserController::searchResults');

$routes->get('/agentslist', 'UserController::agentsList');
$routes->post('/filteragents', 'UserController::agentsList');
$routes->get('/homeslist', 'UserController::homesList');
$routes->post('/filterhomes', 'UserController::homesList');
$routes->get('/modeldetails/(:any)', 'UserController::modelDetails/$1');
$routes->get('/message/fetch', 'MessageController::fetchMessagesAjax');
$routes->post('/message/send', 'MessageController::sendMessage');

$routes->post('/addtofavorites', 'UserController::addToFavorites');

$routes->match(['get', 'post'], '/profile', 'UserController::profile');
$routes->post('/profile/editprofile', 'UserController::editProfile');
$routes->post('/profile/saveprofilephoto', 'UserController::saveProfilePhoto');
$routes->get('/profile/view/(:any)', 'UserController::profileView/$1');

// Admin Section
$routes->match(['get', 'post'], '/adminhome', 'AdminController::adminHome');
$routes->match(['get', 'post'], '/adminagents', 'AdminController::adminAgents');
$routes->match(['get', 'post'], '/admindevelopers', 'AdminController::adminDevelopers');
$routes->post('/adminagents/saveagent', 'AdminController::adminAddAgent');
$routes->post('/adminagents/removeagent', 'AdminController::adminRemoveAgent');
$routes->post('/admindevelopers/editaffiliation', 'AdminController::adminEditAffiliation');
$routes->post('/admindevelopers/savedeveloper', 'AdminController::adminAddDeveloper');
$routes->post('/admindevelopers/removedeveloper', 'AdminController::adminRemoveDeveloper');
$routes->match(['get', 'post'], '/adminprofile', 'AdminController::adminProfile');



