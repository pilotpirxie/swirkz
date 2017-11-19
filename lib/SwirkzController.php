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
}
