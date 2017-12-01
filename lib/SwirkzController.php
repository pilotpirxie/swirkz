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
    function roomExist( $link, $room_url ) {

        // query that check if message exist and|or was created in last 180 minutes
        if ( $result = $link->query( "SELECT TIMESTAMPDIFF( MINUTE, create_date, CURRENT_TIMESTAMP ) as diff FROM messages WHERE room_url = '$room_url' GROUP BY create_date HAVING diff <= 180 ORDER BY create_date DESC LIMIT 1" ) ) {
            if ( $result->num_rows > 0 ) {
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
    function createRoom( $link, $room_url, $data ) {
        $ip_address = $_SERVER[ 'REMOTE_ADDR' ];
        $room_token = hash( 'sha256', $room_url . time() ) . rand( 1, 100 );
        if ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) ) {
            $ip_address = array_pop( explode( ',', $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) );
        }

        // query that create room
        if ( $link->query( "INSERT INTO `rooms` (`id`, `url`, `create_date`, `admin_id`, `admin_ip`, `room_token`) VALUES (NULL, '$room_url', CURRENT_TIMESTAMP, '0', '$ip_address', '$room_token')" ) ) {
            $room_id       = $link->insert_id;
            $message_token = hash( 'sha256', $room_url . '1' . time() ) . rand( 1, 100 );
            if ( $link->query( "INSERT INTO `messages` (`id`, `content`, `room_id`, `room_url`, `user_id`, `user_nickname`, `create_date`, `status`, `message_token`, `room_token`) VALUES (NULL, 'Welcome, say `Hello`', '$room_id', '$room_url', '0', 'Swirkz', CURRENT_TIMESTAMP, '0', '$message_token', '$room_token')" ) ) {
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
    function findUser( $link, $room_url) {
        if ( $result = $link->query( "SELECT id FROM rooms WHERE url = '$room_url' ORDER BY create_date DESC LIMIT 1" ) ) {
            if ( $result->num_rows > 0 ) {

                // grab numeric room_id
                $room_data  = $result->fetch_assoc();
                $room_id    = $room_data[ 'id' ];

				 // create new one
				$ip_address = $_SERVER[ 'REMOTE_ADDR' ];
				if ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) ) {
					$ip_address = array_pop( explode( ',', $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) );
				}

				if($result=$link->query("SELECT nickname FROM users WHERE room_id='$room_id' AND  ip='$ip_address' AND room_url='$room_url'")) {
					if($result->num_rows>0) {
							// grab nickname
							$user_data  = $result->fetch_assoc();
							$nickname    = $user_data[ 'nickname' ];
							return $nickname;
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
     * Insert new user into database and make the admin if not exist
     * @param {mysqli} $link
     * @param {text} $room_url
     * @param {text} $nickname
     * @return {bool} status
     */
    function createUser( $link, $room_url, $nickname ) {
        // search for room
        if ( $result = $link->query( "SELECT id FROM rooms WHERE url = '$room_url' ORDER BY create_date DESC LIMIT 1" ) ) {
            if ( $result->num_rows > 0 ) {

                // grab numeric room_id
                $room_data  = $result->fetch_assoc();
                $room_id    = $room_data[ 'id' ];
                $user_token = hash( 'sha256', $room_id . time() ) . rand( 1, 100 );
                // search for user
                if ( $result = $link->query( "SELECT id FROM users WHERE nickname = '$nickname' AND room_id = '$room_id' ORDER BY create_date DESC LIMIT 1" ) ) {

                    // if user not exist
                    if ( $result->num_rows === 0 ) {

                        // create new one
                        $ip_address = $_SERVER[ 'REMOTE_ADDR' ];
                        if ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) ) {
                            $ip_address = array_pop( explode( ',', $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) );
                        }

                        // search for admins (permissions = 0 [users = 1])
                        if ( $result = $link->query( "SELECT id FROM users WHERE permissions = '0' AND room_id = '$room_id' ORDER BY create_date DESC " ) ) {

                            // if admin not exist
                            if ( $result->num_rows === 0 ) {

                                // insert user and set to admin
                                if ( $result = $link->query( "INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `room_url`, `permissions`, `status`, `create_date`, `user_token`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '$room_url', '0', '0', CURRENT_TIMESTAMP, '$user_token')" ) ) {
                                    return true;
                                } else {
                                    return false;
                                }
                            } else {

                                // insert common user
                                if ( $result = $link->query( "INSERT INTO `users` (`id`, `nickname`, `ip`, `room_id`, `room_url`, `permissions`, `status`, `create_date`, `user_token`) VALUES (NULL, '$nickname', '$ip_address', '$room_id', '$room_url', '1', '0', CURRENT_TIMESTAMP, '$user_token')" ) ) {
                                    return true;
                                } else {
                                    return false;
                                }
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
    public function getSettings( $link, $room_url, $nickname ) {
        // search for user
        if ( $result = $link->query( "SELECT * FROM users WHERE nickname = '$nickname' AND room_url = '$room_url' ORDER BY create_date DESC LIMIT 1" ) ) {

            // if user exist
            if ( $result->num_rows > 0 ) {

                // set user info for the future
                $user_data = $result->fetch_assoc();
                $room_id   = $user_data[ 'room_id' ];

                if ( $result = $link->query( "SELECT * FROM rooms WHERE `url` = '$room_url' AND `id` = '$room_id' ORDER BY create_date DESC LIMIT 1" ) ) {
                    // if room exist
                    if ( $result->num_rows > 0 ) {
                        // get all room data
                        $room_data = $result->fetch_assoc();
                        // json string for return
                        return json_encode( array(
                            'userData' => $user_data,
                            'roomData' => $room_data,
                            'status' => true
                        ) );
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
     * Search in database for the messages for specific room
     *
     * @param {mysqli} link
     * @param {text} room_url
     * @param {text} room_token
     * @param {int} latest_message_count
     * @return {array} messages
     */
    function getMessages( $link, $room_url, $room_token, $latest_message_count ) {
        // get message count
        if ( $result = $link->query( "SELECT COUNT(id) as `msg_count` FROM messages WHERE room_token = '$room_token' AND room_url = '$room_url' AND `status` = '0' " ) ) {
            // compare with client count
            $messages_count = $result->fetch_assoc();
            if ( $messages_count[ 'msg_count' ] !== $latest_message_count ) {
                // if different, then get all messages and save into array
                if ( $result = $link->query( "SELECT * FROM messages WHERE room_token = '$room_token' AND room_url = '$room_url' AND `status` = '0' " ) ) {
                    foreach ( $result as $key => $value ) {
                        $messages[ $key ] = $value;
                    }
                    // return array with messages as json
                    return json_encode( array(
                        'messages' => $messages,
                        'messages_count' => $messages_count[ 'msg_count' ],
                        'status' => 'new-messages'
                    ) );
                } else {
                    return false;
                }
            } else {
                return json_encode( array(
                    'status' => 'no-new-messages'
                ) );
            }
        }
    }

    /**
     * Add new message into database
     *
     * @param {mysqli} link
     * @param {text} room_url
     * @param {text} room_token
     * @param {text} content
     * @param {int} user_id
     * @param {text} user_token
     * @param {text} nickname
     * @return {bool} status
     */
    function sendMessage( $link, $room_url, $room_token, $content, $user_id, $user_token, $nickname ) {
        // count users with param credentials
        if ( $result = $link->query( "SELECT COUNT(id) as `user_count` FROM users WHERE id = '$user_id' AND user_token = '$user_token' AND `status` = '0' AND `room_url` = '$room_url' " ) ) {
            // if user exist (is minimum one instance)
            $user_count = $result->fetch_assoc();
            if ( $user_count[ 'user_count' ] == 1 ) {
                // get all settings and compare with client side info
                $data = json_decode( $this->getSettings( $link, $room_url, $nickname ), true );

                if ( $data[ 'roomData' ][ 'room_token' ] == $room_token ) {
                    // if everything is good, add new message into db
                    $room_id       = $data[ 'roomData' ][ 'id' ];
                    $message_token = hash( 'sha256', $room_id . time() ) . rand( 1, 100 );
                    $user_nickname = $data[ 'userData' ][ 'nickname' ];

                    if ( $result = $link->query( "INSERT INTO `messages` (`id`, `content`, `room_id`, `room_url`, `user_id`, `user_nickname`, `create_date`, `status`, `message_token`, `room_token`) VALUES (NULL, '$content', '$room_id', '$room_url', '$user_id', '$user_nickname', CURRENT_TIMESTAMP, '0', '$message_token', '$room_token')" )) {
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

}
