<?php

class Swirkz {
    function roomExist($link, $room_id) {
      // query that check if message exist and|or was created in last 180 minutes
      $result = $link->query("SELECT TIMESTAMPDIFF( MINUTE, create_date, CURRENT_TIMESTAMP ) as diff FROM messages WHERE room_url = '$room_id' GROUP BY create_date HAVING diff <= 180 ORDER BY create_date DESC LIMIT 1");

      if ($result->num_rows > 0){
        return true;
      } else {
        return false;
      }
    }
	
	function createRoom($link, $room_id, $data) {
	 // query that create room
	 $result = $link->query("INSERT INTO `room` VALUES ('','$room_id','".$data['description']."','".json_encode($data['flags'])."','".$data['password']."',CURRENT_TIMESTAMP,'','')");
	}
}

$swirkz = new Swirkz;
