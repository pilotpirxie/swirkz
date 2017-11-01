<?php
session_start();

if ( isset($_GET['parameter']) && !empty($_GET['parameter']) ) {
	// include or redirect to chat page
	echo $_GET['parameter'];
} else {
	// include or redirect to home page
	include_once('home.php');
}
