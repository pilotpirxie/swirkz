<?php
if ( !isset($_SESSION) ){
    session_start();
}

class UserController
{
	function findUser($link,$room_id)
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		$url = substr($room_id,1);
		$id_room_result = $link->query("SELECT id from room WHERE url='$url' ORDER BY id DESC LIMIT 1");
		$id_room=$id_room_result->fetch_array();
		
		$result = $link->query("SELECT nickname from users WHERE ip='$ip_address' AND room_id='$id_room[0]' LIMIT 1");

		if ($result->num_rows > 0){
			$user=$result->fetch_row();
			$_SESSION['login']=$user[0];	
			return true;
		} else {
			return false;
		}
	}
	
    function createUser($link,$room_id, $user)
	{
		$url = substr($room_id,1);
		$id_room_result = $link->query("SELECT id from room WHERE url='$url' ORDER BY id DESC LIMIT 1");
		$id_room=$id_room_result->fetch_array();

		$ip_address = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		
		$result = $link->query("SELECT nickname from users WHERE nickname='$user' LIMIT 1");
		if ($result->num_rows > 0){
			return false;
		} else {
			
			$result = $link->query("SELECT nickname from users WHERE room_id='$id_room[0]' LIMIT 1");
			if ($result->num_rows > 0){
				if($add = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `permissions`, `status`, `create_date`) VALUES (NULL, '$user', '		   $ip_address', '$id_room[0]', '0', '', CURRENT_TIMESTAMP)")){
					$_SESSION['login']=$user;
					return true;
				} else {
					return false;
				}
			} else {
				if($add = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `permissions`, `status`, `create_date`) VALUES (NULL, '$user', '$ip_address', '$id_room[0]', '1', '', CURRENT_TIMESTAMP)")){
					$_SESSION['login']=$user;
					$id_user_result = $link->query("SELECT id from users WHERE room_id='$id_room[0]' AND permissions='1' LIMIT 1");
					$id_user=$id_user_result->fetch_array();
					if($add = $link->query("UPDATE room SET admin_id = '$id_user[0]'  WHERE id = '$id_room[0]'")) {
						return true;
					} else {
						return false;
					}
					return true;
				} else {
					return false;
				}
			}
		}
	}
}
