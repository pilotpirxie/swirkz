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

<<<<<<< HEAD
<<<<<<< HEAD
        if ( $swirkz->createUser($mysqli, $room_name, $nickname)){
            echo $swirkz->getSettings($mysqli,$room_name, $nickname);
			exit;
            if ( ($settings = $swirkz->getSettings($mysqli,$room_name, $nickname)) !== false ){
                echo $settings;
                // echo '{"status":"success"}';
                exit;
            } else {
                echo '{"status":"failed-3"}';
                exit;
            }
=======
        if ( $swirkz->createUser($mysqli, $room_id, $nickname)){
            echo '{"status":"success"}';
            exit;
>>>>>>> parent of 3097f98... Downloading settings after success register
        } else {
            echo '{"status":"failed-2"}';
            exit;
        }
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
});

// find nickname
$router->map( 'POST', '[*:room_name]/find-nickname', function($room_name) use ($mysqli,$location,$swirkz) {
    if ( isset($_POST['room_name']) && !empty($_POST['room_name'])) {

        // grab info
        $room_name = $_POST['room_name'];
		
		echo $swirkz->findUser($mysqli, $room_name);
		exit;
        if ( $nickname = $swirkz->findUser($mysqli, $room_name)  !== false){
            echo $nickname;
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


// send message
$router->map( 'POST', '[*:room_name]/send-message', function($room_name) use ($mysqli,$location,$swirkz) {
    if ( isset($_POST['room_name'],$_POST['message'],$_POST['nickname']) && !empty($_POST['room_name']) && !empty($_POST['message']) && !empty($_POST['nickname'])) {
		
        // grab info
        $room_name = $_POST['room_name'];
        $message = $_POST['message'];
        $nickname = $_POST['nickname'];
		echo $swirkz->sendMessage($mysqli, $room_name,$message,$nickname);
		exit;
        if ( $sendmessage = $swirkz->sendMessage($mysqli, $room_name,$message,$nickname)  !== false){
            echo $sendmessage;
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

// get messages
$router->map( 'POST', '[*:room_name]/get-messages', function($room_name) use ($mysqli,$location,$swirkz) {
    if ( isset($_POST['room_name']) && !empty($_POST['room_name'])){

        // grab info
        $room_name = $_POST['room_name'];
		echo $messages = $swirkz->getMessages($mysqli, $room_name);
		exit;
        if ( $messages = $swirkz->getMessages($mysqli, $room_name) !== false){
			echo $messages;
=======
        if ( $swirkz->createUser($mysqli, $room_id, $nickname)){
            echo '{"status":"success"}';
            exit;
>>>>>>> parent of 3097f98... Downloading settings after success register
        } else {
            echo '{"status":"failed-2"}';
            exit;
        }
    } else {
        echo '{"status":"failed-1"}';
        exit;
    }
});


// check message
$router->map( 'POST', '[*:room_name]/check-message', function($room_name) use ($mysqli,$location,$swirkz) {
    if ( isset($_POST['room_name']) && !empty($_POST['room_name'])){

        // grab info
        $room_name = $_POST['room_name'];
		echo $check = $swirkz->checkMessage($mysqli, $room_name);
		exit;
        if ( $check = $swirkz->checkMessage($mysqli, $room_name) !== false){
			echo $check;
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
