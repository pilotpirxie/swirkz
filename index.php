<?php

if ( !isset($_SESSION) ){
    session_start();
}

// dependencies
require_once 'config/db_conn.php';
require_once 'lib/AltoRouter.php';
require_once 'lib/SwirkzController.php';
require_once 'lib/LocationController.php';
require_once 'lib/UserController.php';

// new instances
$router = new AltoRouter();
$swirkz = new Swirkz;
$location = new LocationController;
$user = new UserController;

// homepage
$router->map( 'GET', '/', function() {
    require_once 'views/home.php';
});

// help page
$router->map( 'GET', '/help', function() {
    require_once 'views/help.php';
});

// create new post
$router->map( 'POST', '/new/auth/create-new', function() use ($mysqli, $swirkz, $location) {
    if ( isset($_POST['room_id']) ){
        $room_id = $_POST['room_id'];
    } else {
        $location::go('/');
    }

    if ( $swirkz->roomExist($mysqli, $room_id) ){
		header("Location: /".$room_id);
    } else {
        if ( $swirkz->createRoom($mysqli, $room_id, $_POST) ) {
            $location::go("/$room_id");
        }
    }
});

// create new chat
$router->map( 'GET', '/new/[*:room_id]', function($room_id) use ($mysqli, $swirkz, $location) {
    if ( $swirkz->roomExist($mysqli, $room_id) ){
        $location::go("/$room_id");
    } else {
        require_once 'views/new-chat.php';
    }
});

// redirect to valid URL
$router->map( 'POST', '/new', function() use ($location) {
    if ( isset($_POST['room_id']) && !empty($_POST['room_id']) ){
        $location::go("new/" . $_POST['room_id']);
    } else {
        header("Location: new/" . time() . 'r' . rand(1,100));
        exit;
    }
});

$router->map( 'GET', '/new', function() use ($location) {
    $location::go("new/" . time() . 'r' . rand(1,100));
});

$router->map( 'GET', '/new/[*]', function($room_id) use ($location) {
    $location::go("new/" . time() . 'r' . rand(1,100));
});

// 404 error - all other matches
$router->map( 'GET', '/[*]/[*]', function() {
    echo 'Eror 404';
    exit;
});

// go to exist chat
$router->map( 'GET', '/[*:room_id]', function($room_id) use ($mysqli, $swirkz) {
    if ( $swirkz->roomExist($mysqli, $room_id) ){
        require_once 'views/chat.php';
    } else {
        header("Location: /new/".$room_id);
        exit;
    }
});

// redirect to room again
$router->map( 'POST', '[*:room_id]/login', function($room_id) use ($mysqli,$location,$user) {
    if ( isset($_POST['login_input']) && !empty($_POST['login_input']) ){
		if($user->findUser($mysqli,$room_id)){
			$_SESSION['currentRoom'] = substr($room_id,1);
			$location::go("$room_id");
		}else{
			if($user->createUser($mysqli,$room_id,$_POST['login_input'])){
				$_SESSION['currentRoom'] = substr($room_id,1);
				$location::go("$room_id");
			} else {
				$location::go("$room_id");
			}
		}
	} else {
			$location::go("$room_id");
    }
});

// save nickname
$router->map( 'POST', '[*:room_id]/save-nickname', function($room_id) use ($mysqli,$location,$swirkz) {
    if ( isset($_POST['room_id'], $_POST['nickname']) && !empty($_POST['room_id']) && !empty($_POST['nickname']) ){

        // grab info
        $room_id = $_POST['room_id'];
        $nickname = $_POST['nickname'];

        if ( $swirkz->createUser($mysqli, $room_id, $nickname)){
            echo '{"status":"success"}';
            exit;
        } else {
            echo '{"status":"failed-2"}';
            exit;
        }
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
});

$match = $router->match();

if( $match && is_callable( $match['target'] ) ) {
    call_user_func_array( $match['target'], $match['params'] );
} else {
    // no route was matched
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo 'Error 404 - file not found';
    exit;
}
