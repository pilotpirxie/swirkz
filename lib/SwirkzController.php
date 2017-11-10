<?php

class Swirkz {
    function roomExist($link, $room_id) {
      // query that check if message exist and|or was created in last 180 minutes
      $result = $link->query("SELECT TIMESTAMPDIFF( MINUTE, create_date, CURRENT_TIMESTAMP ) as diff FROM messages WHERE room_url = '$room_id' GROUP BY create_date HAVING diff <= 180 ORDER BY create_date DESC LIMIT 1");

      if ($result->num_rows == 0){
        return false;
      } else {
        return true;
      }
    }
}

$swirkz = new Swirkz;
