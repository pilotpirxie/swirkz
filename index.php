<?php
session_start();

// dependencies
require_once 'lib/AltoRouter.php';

// new instance of alto router
$router = new AltoRouter();

// homepage
$router->map( 'GET', '/', function() {
    require_once 'views/home.php';
});

// homepage
$router->map( 'GET', '/help', function() {
    require_once 'views/help.php';
});

// create new chat
$router->map( 'GET', '/new/[*:room_id]', function($room_id) {
	// check if room exist or was closed in past 180 minutes
	// then open or go to new-chat page
	require_once 'views/new-chat.php';
});

// redirect to valid URL
$router->map( 'POST', '/new', function() {
	if ( isset($_POST['room_id']) && !empty($_POST['room_id']) ){
		header("Location: new/" . $_POST['room_id']);
		exit;
	} else {
		header("Location: new/" . time() . 'r' . rand(1,100));
		exit;
	}

});

$router->map( 'GET', '/new', function($room_id) {
	header("Location: new/" . time() . 'r' . rand(1,100));
	exit;
});

$router->map( 'GET', '/new/', function($room_id) {
	header("Location: ./" . time() . 'r' . rand(1,100));
	exit;
});

// go to exist chat
$router->map( 'GET', '/[*:room_id]', function($room_id) {
	// check if room exist or was closed in past 180 minutes
	// then open or redirect to /new/id page
    require_once 'views/chat.php';
});

$match = $router->match();

if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
