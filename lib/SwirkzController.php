<?php

class Swirkz
{
    /**
     * Look up in database for last message in specified room, if it is in less than 180
     * minutes then room are still live
     * @param {mysqli} $link
     * @param {text} $room_url
     * @return {bool} status
     */
    function roomExist($link, $room_url) {

        // query that check if message exist and|or was created in last 180 minutes
        if ( $result = $link->query("SELECT TIMESTAMPDIFF( MINUTE, create_date, CURRENT_TIMESTAMP ) as diff FROM messages WHERE room_url = '$room_url' GROUP BY create_date HAVING diff <= 180 ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Insert into database new room
     * @param {mysqli} $link
     * @param {text} $room_url
     * @param {array} $data
     * @return {bool} status
     */
    function createRoom($link, $room_url, $data) {
        $description = preg_replace('/[^A-Za-z0-9\-]/', '',strip_tags($data['description'],''));
        $settings = json_encode($data['flags']);
        $password = preg_replace('/[^A-Za-z0-9\-]/', '',strip_tags($data['password'],''));
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        // query that create room
        if ($link->query("INSERT INTO `room` (`id`, `url`, `description`, `settings`, `password`, `create_date`, `admin_id`, `admin_ip`) VALUES (NULL, '$room_url', '$description', '$settings', '$password', CURRENT_TIMESTAMP, '0', '$ip_address')") ){
            $room_id = $link->insert_id;
            if ($link->query("INSERT INTO `messages` (`id`, `content`, `room_id`, `room_url`, `user_id`, `create_date`, `status`) VALUES (NULL, 'Welcome, say `Hello`', '$room_id', '$room_url', '0', CURRENT_TIMESTAMP, '0')") ){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Insert new user into database and make the admin if not exist
    * @param {mysqli} $link
    * @param {text} $room_url
    * @param {text} $nickname
    * @return {bool} status
    */
    function createUser($link, $room_url, $nickname){
        // search for room
        if ( $result = $link->query("SELECT id FROM room WHERE url = '$room_url' ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){

                // grab numeric room_id
                $room_data = $result->fetch_assoc();
                $room_id = $room_data['id'];

                // search for user
                if ( $result = $link->query("SELECT id FROM users WHERE nickname = '$nickname' AND room_id = '$room_id' ORDER BY create_date DESC LIMIT 1") ){

                    // if user not exist
                    if ($result->num_rows === 0){

                        // create new one
                        $ip_address = $_SERVER['REMOTE_ADDR'];
                        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
                        }

                        // search for admins (permissions = 0 [users = 1])
                        if ( $result = $link->query("SELECT id FROM users WHERE permissions = '0' AND room_id = '$room_id' ORDER BY create_date DESC ") ){

                            // if admin not exist
                            if ($result->num_rows === 0){

                                // insert user and set to admin
<<<<<<< HEAD
                                if ( $result = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `room_url`, `permissions`, `status`, `create_date`, `user_token`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '$room_url', '0', '0', CURRENT_TIMESTAMP, '$user_token')") ){
									//admin_id
									if( $result = $link->query("SELECT id FROM users WHERE user_token='$user_token'") ){
										if ($result->num_rows> 0){
											$admin_data= $result->fetch_assoc();
											$admin_id = $admin_data['id'];
											if($result = $link->query("UPDATE `rooms` SET `admin_id` = '$admin_id' WHERE `id` = '$room_id'") ) {
												setcookie("userName", $nickname, time()+36000, "/", "",  0);
												setcookie("currentRoom", $room_url, time()+36000, "/", "",  0);
												return true;
											} else {
												return false;
											}
										} else {
											return false;
										}
									} else {
										return false;
									}
								} else {

									// insert common user
									if ( $result = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `room_url`, `permissions`, `status`, `create_date`, `user_token`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '$room_url', '1', '0', CURRENT_TIMESTAMP, '$user_token')") ){
										return true;
									} else {
										return false;
									}
								}
							 } else {
								return false;
							}
=======
                                if ( $result = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `permissions`, `status`, `create_date`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '0', '0', CURRENT_TIMESTAMP)") ){
                                    return true;
                                } else {
                                    return false;
                                }
                            } else {

                                // insert common user
                                if ( $result = $link->query("INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `permissions`, `status`, `create_date`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '1', '0', CURRENT_TIMESTAMP)") ){
                                    return true;
                                } else {
                                    return false;
                                }
                            }
>>>>>>> parent of 3097f98... Downloading settings after success register
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
<<<<<<< HEAD
	
	  /**
     * Search in database for the information of user
     *
     * @param {mysqli} link
     * @param {int} room_id
     * @param {text} ip_address
     */
	
	function findUser($link,$room_name)
	{
	  // search for room
        if ( $result = $link->query("SELECT id FROM rooms WHERE url = '$room_name' ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){
				// grab numeric room_id
                $room_data = $result->fetch_assoc();
                $room_id = $room_data['id'];
				
				$ip_address = $_SERVER['REMOTE_ADDR'];
				if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
					$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
				}
				
				$result = $link->query("SELECT nickname from users WHERE ip='$ip_address' AND room_id='$room_id' LIMIT 1");

				if ($result->num_rows > 0){
					$user=$result->fetch_assoc();
					$nickname=$user['nickname'];
					return json_encode(array('nickname' => $nickname, 'status' => true));
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	
	function sendMessage($link,$room_name,$message,$nickname)
	{
	  // search for room
        if ( $result = $link->query("SELECT id,room_token FROM rooms WHERE url = '$room_name' ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){
				// grab numeric room_id
                $room_data = $result->fetch_assoc();
                $room_id = $room_data['id'];
                $room_token = $room_data['room_token'];
				$message_token = hash('sha256', $room_id . time()) . rand(1, 100);
				$result = $link->query("SELECT id FROM users WHERE nickname='$nickname' LIMIT 1"); // AND room_id='$room_id'
				if ($result->num_rows > 0){
					$user=$result->fetch_assoc();
					$user_id=$user['id'];
					if ( $result = $link->query("INSERT INTO `messages`(`id`, `content`, `room_id`, `room_url`, `user_id`, `create_date`, `status`, `message_token`, `room_token`) VALUES (NULL,'$message','$room_id','$room_name','$user_id',CURRENT_TIMESTAMP,0,'$message_token','$room_token')")){
						
						return true;
					} else {
						return false;
					}	
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	function getMessages($link,$room_name)
	{
	  // search for room
        if ( $result = $link->query("SELECT id,room_token FROM rooms WHERE url = '$room_name' ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){
				// grab numeric room_id
                $room_data = $result->fetch_assoc();
                $room_id = $room_data['id'];
                $room_token = $room_data['room_token'];
				if ( $result = $link->query("SELECT content,messages.status,messages.create_date,nickname FROM messages,users WHERE messages.user_id=users.id AND messages.room_id='$room_id' AND messages.room_token='$room_token'") or die(mysqli_error($link))){
					
					$messages[]=array('status'=>true);
					setcookie("lines", $result->num_rows, time()+36000, "/", "",  0);
					
					if($result->num_rows>0)
					{
						while($messages_data = $result->fetch_assoc()) {
							$messages[]=$messages_data;
						}
					} 
					
					return json_encode($messages , JSON_FORCE_OBJECT);
					
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function checkMessage($link,$room_name) {
		if ( $result = $link->query("SELECT id,room_token FROM rooms WHERE url = '$room_name' ORDER BY create_date DESC LIMIT 1") ){
            if ($result->num_rows > 0){
				// grab numeric room_id
				if(isset($_COOKIE['lines'])){
					$lines = $_COOKIE['lines'];
				} else {
					$lines = 0;
				}
                $room_data = $result->fetch_assoc();
                $room_id = $room_data['id'];
                $room_token = $room_data['room_token'];
				if ( $result = $link->query("SELECT content,messages.status,messages.create_date,nickname FROM messages,users WHERE messages.user_id=users.id AND messages.room_id='$room_id' AND messages.room_token='$room_token'") or die(mysqli_error($link))){
					if($lines!=$result->num_rows){
						setcookie("lines", $result->num_rows, time()+36000, "/", "",  0);
						
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	
    /**
     * Search in database for the information of user and room
     *
     * @param {mysqli} link
     * @param {int} room_id
     * @param {text} nickname
     * @return {mixed} bool or data
     */
    function getSettings($link, $room_url, $nickname) {
        // search for user
        if ( $result = $link->query("SELECT * FROM users WHERE nickname = '$nickname' AND room_url = '$room_url' ORDER BY create_date DESC LIMIT 1") ){

            // if user exist
            if ($result->num_rows > 0){

                // set user info for the future
                $user_data = $result->fetch_assoc();
                $room_id = $user_data['room_id'];

                if ( $result = $link->query("SELECT * FROM rooms WHERE `url` = '$room_url' AND `id` = '$room_id' ORDER BY create_date DESC LIMIT 1") ) {
                    // if room exist
                    if ($result->num_rows > 0) {
                        // get all room data
                        $room_data = $result->fetch_assoc();
                        // json string for return
                        return json_encode(array('userData' => $user_data, 'roomData' => $room_data, 'status' => true,'nickname'=>$nickname));
                    } else {
                        echo 'bad';
                        return false;
                    }
                } else {
                    return false;
                }

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

=======
>>>>>>> parent of 3097f98... Downloading settings after success register
}
