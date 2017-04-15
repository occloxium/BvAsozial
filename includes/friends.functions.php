<?php
/*
* FREINDS FUNCTIONS START ----------------------------------------------------
*/

/**
 * Checks if a certain user is friends with another
 * @param $user search node of friendship graph
 * @param $friend to be searched for friend
 * @param $mysqli the mysqli object refering to the database
 * @return true, in case users are friends, otherwise false
 */
function isFriendsWith($user, $friend, $mysqli){
  if($friend != null && $user != null){
    if($user == $friend){
      // Vereinbarung: Man ist mit sich selbst "befreundet" - erspart pro benutzer ein feld in der datenbank bzw. einen komplizierteren query
      return true;
    } else {
      $stmt = $mysqli->prepare("SELECT * FROM freunde WHERE uid = ? AND friend = ?");
      $stmt->bind_param("ss",$user,$friend);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 1){ // genau eine Zeile gefunden, in der der freund und der benutzer vorkommen
        return true;
      }
    }
  }
  return false;
}

/**
 * Determines if a user send a friend request to another user
 * @param $from the requestor
 * @param $to the requested
 * @param $mysqli the mysqli object refering to the database
 */
function requestSent($from, $to, $mysqli){
  if($from !== $to){
    $result = $mysqli->query("SELECT von, an FROM anfragen WHERE von = '$from' AND an = '$to' LIMIT 1");
    if($result->num_rows == 0){
      return false;
    } else {
      return true;
    }
  } else {
    return false;
  }
}

/**
 * Creates a friend request from a given user to a given user
 * @param $from the requestor
 * @param $to the requested
 * @param $mysqli the mysqli object refering to the database
 * @return true in case of no error, otherwise the error message
 */
function sendFriendRequest($from, $to, $mysqli){
    $result = $mysqli->query("INSERT INTO anfragen (von, an, sent) VALUES ('$from','$to', CURRENT_DATE)");
    if($mysqli->errno == 00000){
        $mysqli->multi_query("UPDATE person SET sent_requests = sent_requests + 1 WHERE uid = '$from'; UPDATE person SET received_requests = received_requests + 1 WHERE uid = '$to'");
        return true;
    } else {
        return $mysqli->error;
    }
}

/**
 * Executes an acceptance of a friend request and adds the requesting user to the list of friends of the accepting user
 * @param $friend the friend to be added to the friend list
 * @param $self the user accepting himself
 * @param $mysqli the mysqli object refering to the database
 * @return an array containing more data about the operation. Error array in case of an error, success array in case of success
 */
function addAsFriend($friend, $self, $mysqli){
  if($friend == $self || isFriendsWith($self, $friend, $mysqli) || !requestSent($friend, $self, $mysqli)){
    if(isFriendsWith($self, $friend, $mysqli) && requestSent($friend, $self, $mysqli)){
      $mysqli->query("DELETE FROM anfragen WHERE von = '$friend' AND an = '$self'");
    }
    return error("internalError", 502, "No request found or already friends");
  } else {
    if(!$mysqli->query("INSERT INTO freunde (uid, friend, friendsSince) VALUES ('$self','$friend',CURRENT_DATE)")){
      return error("internalError", 500, "Unable to update friends");
    }
    if(!$mysqli->query("INSERT INTO freunde (uid, friend, friendsSince) VALUES ('$friend','$self',CURRENT_DATE)")){
      return error("internalError", 500, "Unable to update friends");
    }
    if(!$mysqli->query("DELETE FROM anfragen WHERE von = '$friend' AND an = '$self'")){
      return error("internalError", 500, "Unable to delete request");
    }
  }
  return success(["msg" => "Freundschaftsanfrage gesendet"]);
}

/**
 * GETs a users friends by their name - quite inefficient but no current options - algorithm used for searching in a user's friends
 * @param $uid the users node in the friendship graph
 * @param $name the friend's name searched for
 * @param $mysqli the mysqli object refering to the database
 * @return a set of uid's of the user's friends
 */
function getFriendsByName($uid, $name, $mysqli){
  $friends = [];
  $result = getUsers($mysqli);
  while($row = $result->fetch_assoc()){
    if(stripos($row['name'], $name) !== false && isFriendsWith($uid, $row['uid'], $mysqli)){
      $friends[] = $row['uid'];
    }
  }
  return $friends;
}

/*
 * FRIENDS FUNCTIONS END -----------------------------------------------------
 */
?>
