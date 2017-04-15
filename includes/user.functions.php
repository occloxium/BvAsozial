<?php
/*
 * USER FUNCTIONS START ------------------------------------------------------
 */

/**
 * Determines if a given uid is an actual user, wether invited or registered
 * @param $uid the uid of the potential user
 * @param $mysqli the mysqli refering to the database
 * @return true in case the user with the uid exists, otherwise false;
 */
function isUser($uid, $mysqli){
  return is_invited($uid, $mysqli) || userExists($uid, $mysqli);
}

/**
 * GETs all users in the database
 * @param $mysqli the mysqli object refering to the database
 * @return a mysqli::result object for further iteration in loops
 */
function getUsers($mysqli, $query = "SELECT * FROM person"){
  return $mysqli->query($query);
}

/**
 * GETs a reduced form of a user's data from the `person` table
 * @param $username the users id to get data from
 * @param $mysqli the mysqli object refering to the database
 * @return an array containing the user's data OR null in case there was no user found or given
 */
function getMinimalUser($username, $mysqli){
  $result = $mysqli->query("SELECT name, uid, directory FROM person WHERE uid = '$username'");
  if($result != false){
    return $result->fetch_assoc();
  } else {
    return null;
  }
}

/**
 * GETs a user's data from the `person` table
 * @param $username the users id to get data from
 * @param $mysqli the mysqli object refering to the database
 * @return an array containing the user's data OR null in case there was no user found or given
 */
function getUser($username, $mysqli){
  $query = "SELECT * FROM person INNER JOIN login ON person.uid = login.uid WHERE person.uid = '{$username}'";
  $result = $mysqli->query($query);
  if($result != false){
    $return = $result->fetch_assoc();
    $return['vorname'] = explode(" ",$return['name'])[0];
    $return['freunde'] = getFriends($username, $mysqli);
    $return['uid'] = $username;
    $return['freundesanzahl'] = count($return['freunde']);
    return $return;
  } else {
    echo null;
  }
}

/**
 * Returns an array of a user's friends [string]
 * @param $username the user's id
 * @param $mysqli the mysqli object refering to the database
 * @return an array containing the uid of the user's friend
 */
function getFriends($username, $mysqli){
  $friends = [];
  if($stmt = $mysqli->prepare("SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince ASC")){
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($friend);
    $stmt->store_result();
    while($stmt->fetch()){
      $friends[] = $friend;
    }
  }
  return $friends;
}

/**
 * returns an array of a users friends [user objects]
 * @param $freunde array containing the usernames of the friends
 * @param $mysqli the mysqli object refering to the database
 * @param an array containing the user objects of a friend list
 */
function _getFriends($freunde, $mysqli){
  $friends = [];
  foreach($freunde as $freund){
    $friends[] = getUser($freund, $mysqli);
  }
  return $friends;
}

/**
 * Checks if a user exists.
 * @param $uid the uid to be searched
 * @param $mysqli the mysqli object refering to the database
 * @return true in case of a found user, otherwise false
 */
function userExists($uid, $mysqli){
  return _findUser($uid, $mysqli) != false;
}

/**
 * Searches for a user in the login table and returns his hashed password for further usage
 * @param $username the username to be searched for
 * @param $mysqli the mysqli object refering to the database
 */
function _findUser($username, $mysqli){
  $stmt = $mysqli->prepare("SELECT password FROM login WHERE uid = ? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($password);
  if($stmt->num_rows == 1){
    $stmt->fetch();
    return $password;
  } else {
    return false;
  }
}

/*
 * USER ATTRIBUTES SECTION START -----------------------------------------------
 */

/**
 * Checks a user's groups (Mod, Admin) and directs request further
 * @param $username the user to be checked
 * @param $mysqli the mysqli object refering to the database
 */
function _checkGroups($username, $mysqli){
  if(!is_admin($username, $mysqli))
    is_mod($username, $mysqli);
}

/**
 * Checks, if a user is an admin and, in case of that event, sets a session variable to mark the user as an admin
 * @param $username the username to be searched for
 * @param $mysqli the mysqli object refering to the database
 * @param $nochanges determines wether there should not be made any changes to the session
 */
function is_admin($username, $mysqli, $nochanges = false){
  if(isset($_SESSION['user']['is_admin']) && $_SESSION['user']['uid'] === $username){
    if($_SESSION['user']['is_admin']){
      return true;
    } else {
      return false;
    }
  } else {
    $stmt = $mysqli->prepare("SELECT * FROM admins WHERE boundTo = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if(isset($_SESSION) && !$nochanges){
      if($stmt->num_rows == 1){
        $_SESSION['user']['is_admin'] = true;
        return true;
      } else {
        $_SESSION['user']['is_admin'] = false;
        return false;
      }
    } else {
      if($stmt->num_rows == 1){
        return true;
      } else {
        return false;
      }
    }
  }
}

/**
 * Checks if a user is a moderator and, in case of that event, sets a session variable to mark the user as a moderator
 * @param $username the username to be searched for
 * @param $mysqli the mysqli object refering to the database
 * @param $nochanges determines wether there should not be made any changes to the session
 */
function is_mod($username, $mysqli, $nochanges = false){
  if(isset($_SESSION['user']['is_mod']) && $_SESSION['user']['uid'] === $username){
    if($_SESSION['user']['is_mod']){
      return true;
    } else {
      return false;
    }
  } else {
    $stmt = $mysqli->prepare("SELECT * FROM moderatoren WHERE boundTo = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if(isset($_SESSION) && !$nochanges){
      if($stmt->num_rows == 1){
        $_SESSION['user']['is_mod'] = true;
        return true;
      } else {
        $_SESSION['user']['is_mod'] = false;
        return false;
      }
    } else {
      if($stmt->num_rows == 1){
        return true;
      } else {
        return false;
      }
    }
  }
}

/**
 * Checks if a user is allowed to alter another user's additional names
 * @param $accessor the user trying to alter
 * @param $target the user whose profile is to be altered
 * @param $mysqli the mysqli object refering to the database
 * @return true if the $accessor is privileged to alter, otherwise false
 */
function is_privileged($accessor, $target, $mysqli){
  if(is_admin($accessor, $mysqli) || is_mod($accessor, $mysqli) || $accessor == $target){
    return true;
  } else {
    return false;
  }
}

/**
 * Checks if a given user is invited.
 * @param $uid the uid of the potentially invited user
 * @param $mysqli the mysqli object refering to the database
 * @return true in case the uid was found, otherwise false
 */
function is_invited($uid, $mysqli){
  if ($stmt = $mysqli->prepare('SELECT id FROM ausstehende_einladungen WHERE uid = ? LIMIT 1')) {
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1)
      return true;
    else
      return false;
  } else {
    error('internalError', 500, $mysqli->error . " ({$mysqli->errno})");
  }
}

/**
 * Determines if a given users' profile shall be visible to another given user based on firsts privacy settings
 * @param $target the user in question
 * @param $originator the user requesting
 * @param $mysqli the mysqli object refering to the database
 * @return true in case of visible, otherwise false
 */
function is_visible($target, $originator, $mysqli){
  if(isUser($target, $mysqli) && isUser($originator, $mysqli)){
    $visibility = fetch("privacySettings", "person", "uid", $target, $mysqli, "LIMIT 1");
    switch($visibility){
      case 3 :
        return true;
        break;
      case 2 :
        if(isFriendsWith($target, $originator, $mysqli))
          return true;
        else
          return false;
        break;
      case 1 :
        if(is_listed($target, $originator) && isFriendsWith($target, $originator, $mysqli))
          return true;
        else
          return false;
        break;
      case 0 :
        if($target == $originator)
          return true;
        else
          return false;
        break;
      default: echo "We're sorry, something went horribly wrong!"; break;
    }
  }
}

/**
 * Determines wether $originator is listed in $target's allowedUsers list
 * @param $target the user owning the list
 * @param $originator the user in question to be listed
 * @return true wether listed, otherwise false
 */
function is_listed($target, $originator){
  return in_array($originator, getAllowedUsersList($target));
}

/*
 * USER ATTRIBUTES SECTION END -------------------------------------------------
 */
?>
