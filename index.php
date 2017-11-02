<?php
session_start();

// dependencies
require_once 'AltoRouter.php';

// new instance of alto router
$router = new AltoRouter();

// homepage
$router->map( 'GET', '/', function() {
    require_once 'home.php';
});

// create new chat
$router->map( 'GET', '/new', function() {
    require_once 'new-chat.php';
});

// go to exist chat
$router->map( 'GET', '/[*:room_id]', function($room_id) {
    require_once 'chat.php';
});

$match = $router->match();

if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
