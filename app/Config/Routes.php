<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// -- * --
// USERS ROUTES
// -- * --

$routes->get('/users/id/(:any)', 'Users::getUserById/$1');
// /users/:email/:password
$routes->get('/users/(:any)/(:any)', 'Users::getSingleUser/$1/$2');
$routes->get('/users', 'Users::index');
$routes->post('/users', 'Users::addUser');

// -- * --
// BOOKS ROUTES
// -- * --

// books/proposed/bookId
// gets the book for which a given book is proposed as trade.
$routes->get('books/proposed/(:any)','Books::proposed/$1');

$routes->put('books/proposed','Books::setPropose');

// UPLOAD BOOK COVER IN ASSETS
$routes->post('books/cover/upload', 'FileUpload::index');
// INSERT BOOK COVER IN DB
$routes->post('books/cover/', 'Books::addBookCover/$id');

// GET BOOKS WITH OFFSET
$routes->get('books/offset/(:any)','Books::getWithOffset/$1');

// SEARCH
// books/searchCriteria/value
$routes->get('books/(:any)/(:any)','Books::search/$1/$2');

// GET SINGLE BOOK
// books/bookId
$routes->get('books/(:any)','Books::single/$1');

// CRUD 
$routes->post('books', 'Books::addBook');
$routes->put('books', 'Books::updateBook');
$routes->delete('books/(:any)','Books::deleteBook/$1');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
