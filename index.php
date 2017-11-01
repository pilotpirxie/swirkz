<?php
session_start();

if ( isset($_GET['parameter']) && !empty($_GET['parameter']) ) {

	// we need to check if this chat exist in past 180 minutes
	// depends on this, user can create new room on this link
	// or view chat (optionally with password input)

	// temp
	$exist = false;

	if ( $exist ) {
		// include or redirect to chat page
		require_once('chat.php');
	} else {
		// create new page
		require_once('new_chat.php');
	}
} else {
	// include or redirect to home page
	require_once('home.php');
}
