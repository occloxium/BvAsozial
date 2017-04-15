<?php
/**
 * GETs a data set of currently a invited users who has not accepted the invitation yet
 * @param $uid the user searched for
 * @param $mysqli the mysqli object refering to the database
 * @return a set of user data
 */
function getInvitedUser($uid, $mysqli){
  if($uid != null){
    $result = $mysqli->query("SELECT * FROM ausstehende_einladungen WHERE uid = '$uid' OR email = '$uid' LIMIT 1");
    $result = $result->fetch_assoc();
    $result['vorname'] = explode(" ", $result['name'])[0];
    return $result;
  }
  return false;
}

/**
 * Checks if an invite is a valid one
 * @param $uid the uid of the user invited
 * @param $password the hashed entered password
 * @param $mysqli the mysqli object refering to the database
 */
function isValidInvite($uid, $password, $mysqli){
  if($stmt = $mysqli->prepare("SELECT password FROM ausstehende_einladungen WHERE (email = ? OR uid = ?) AND password = ? LIMIT 1")){
    $stmt->bind_param("sss", $uid, $uid, $password);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->store_result();
    $stmt->fetch();
    if($db_password == $password){
      return true;
    }
  } else {
    echo $mysqli->error;
  }
  return false;
}

/**
 * Checks if a given uid belongs to an expired or removed invite
 * @param $uid the uid corresponding to the invite
 * @param $mysqli the mysqli object refering to the database
 * @return true, if it's expired or removed, otherwise false
 */
function isExpiredInvite($uid, $mysqli){
  if ($stmt = $mysqli->prepare("SELECT uid FROM entfernte_einladungen WHERE uid = ? LIMIT 1")) {
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 1)
      return true;
    else
      false;
  }
}
?>
