<?php
/*
 * Controller directed web-routing scheme.
 *
 *     Eze Onukwube <ezeuchey2k@gmail.com>
 *
 * This file was my attempt to establish a routing system in the vein of Laravel's web routes
 * utilizing Symphony's RouteCollection class.
 */
require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__."./RouterService.php";

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('getAllPlaylist', new Route('/Controllers', array('controller' => 'PlaylistController', 'method' => 'findAll')));

$routes->add('getAlbum', new Route('/Controllers', 
            array('controller' => 'AlbumController', 'method' => 'getAlbum', 'genre' => isset($_GET["genre"])? $_GET["genre"] : null )));
			
$routes->add('getGenreAlbum', new Route('/Controllers', 
            array('controller' => 'AlbumController', 'method' => 'getGenreAlbum', 'genre' => isset($_GET["genre"])? $_GET["genre"] : null )));
			
$routes->add('addAlbum', new Route('/Controllers', 
            array('controller' => 'AlbumController', 'method' => 'addAlbum', 'genre' => isset($_POST["genre"])? $_POST["genre"] : null )));
			
$routes->add('getLogin', new Route('/Controllers', 
            array('controller' => 'LoginController', 'method' => 'login' )));
			
$routes->add('getRegister', new Route('/Controllers', 
            array('controller' => 'LoginController', 'method' => 'register' )));
			
$routes->add('getLogout', new Route('/Controllers', 
            array('controller' => 'LoginController', 'method' => 'logout' )));
			
$routes->add('addCart', new Route('/Controllers', 
            array('controller' => 'CartController', 'method' => 'addToCart', 'checkout' => isset($_GET["checkout"])? $_GET["checkout"] : null )));
			
$routes->add('removeCart', new Route('/Controllers', 
            array('controller' => 'CartController', 'method' => 'removeFromCart', 'checkout' => isset($_GET["checkout"])? $_GET["checkout"] : null )));

$routes->add('viewCart', new Route('/Controllers', 
            array('controller' => 'CartController', 'method' => 'getCart' )));
						
$routes->add('addOrder', new Route('/Controllers', 
            array('controller' => 'OrderController', 'method' => 'purchase', 'checkout' => isset($_GET["purchase"])? $_GET["purchase"] : null )));		

$routes->add('getSession', new Route('/Controllers', 
            array('controller' => 'LoginController', 'method' => 'isLoggedIn' )));			
			
processRoute($routes);
			