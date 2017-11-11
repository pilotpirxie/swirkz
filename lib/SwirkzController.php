<?php

class Swirkz
{
    /**
     * Look up in database for last message in specified room, if it is in less than 180
     * minutes then room are still live
     * @param {mysqli} $link
     * @param {int} $room_id
     * @return {bool} status
     */
    function roomExist($link, $room_id) {
      // query that check if message exist and|or was created in last 180 minutes
      $result = $link->query("SELECT TIMESTAMPDIFF( MINUTE, create_date, CURRENT_TIMESTAMP ) as diff FROM messages WHERE room_url = '$room_id' GROUP BY create_date HAVING diff <= 180 ORDER BY create_date DESC LIMIT 1");

      if ($result->num_rows > 0){
        return true;
      } else {
        return false;
      }
    }

    /**
     * Insert into database new room
     * @param {mysqli} $link
     * @param {int} $room_id
     * @param {array} $data
     * @return {bool} status
     */
    function createRoom($link, $room_id, $data) {
        $room_url = $room_id;
        $description = $data['description'];
        $settings = json_encode($data['flags']);
        $password = $data['password'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        // query that create room
        if ($link->query("INSERT INTO `room` (`id`, `url`, `description`, `settings`, `password`, `create_date`, `admin_id`, `admin_ip`) VALUES (NULL, '$room_id', '$description', '$settings', '$password', CURRENT_TIMESTAMP, '0', '$ip_address')") ){
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
}
