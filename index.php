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
	$Dbconnect = mysqli_connect('localhost', 'root', '', 'swirkz');
	$Inquiry = "SELECT * FROM room WHERE name='$room_id'";
	$Result = $Dbconnect->query($Inquiry);
	if (mysqli_num_rows($Result)==0){
		require_once 'views/new-chat.php'; 
	} else {
		$Result_tab = $Result->fetch_assoc();
		$Curr_date = date('Y-m-d H:i:s');
		$Inquiry = "SELECT create_date FROM messages WHERE id=".$Result_tab['id']." ORDER BY create_date DESC LIMIT 1";
		$Result = $Dbconnect->query($Inquiry);
		$Result_tab = $Result->fetch_assoc();
		mysqli_close($Dbconnect);
		
		$start = date_create($Curr_date);
		$end = date_create($Result_tab['create_date']);
		$diff=date_diff($end,$start);
		$time_control = $diff->h*60;
		$time_control += $diff->i;
		if($time_control>=180) {
			require_once 'views/new-chat.php'; 
		}
		else {
			header("Location: /".$room_id);
		}
	}
	// check if room exist or was closed in past 180 minutes
	// then open or go to new-chat page
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
