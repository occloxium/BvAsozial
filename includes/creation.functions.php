<?php
/**
 * Creates a new user
 * @param $user a user data set object
 * @param $mysqli the mysqli object refering to the database
 * @return false in case of any errors(logged and send to client), true in case of success
 */
function createNewUser($user, $mysqli){
  if(!createUserDirectory($user['uid'])){
    error("internalError", 500, "Failed to create directory");
    return false;
  }
  if(!addUserToDatabase($user, $mysqli)){
    if(!revertChanges($user)){
      error("internalError", 500, "Failed to revert changes");
    }
    error("internalError", 500, "Failed to insert into database");
    return false;
  }
  return true;
}

/**
 * Creates a new user data set object prepared for insertion into database
 * @param an array containing required information about the new user
 * @return a ready-to-insert user object
 */
function createUserObject($obj){
  if(isset($obj['name'], $obj['email'], $obj['uid'], $obj['password'])){
    return [
      "name" => $obj['name'],
      "uid" => $obj['uid'],
      "password" => hash('sha384', $obj['password']),
      "email" => $obj['email'],
      "directory" => $obj['uid'],
      "registered_since" => date("Y-m-d")
    ];
  } else {
    error('clientError', 400, 'Not all required information set or POST corrupted');
    return null;
  }
}

/**
 * Adds a user to the database
 * @param $user a fulfilled user object
 * @param $mysqli the mysqli object refering to the database
 * @return true in case of success, otherwise false
 */
function addUserToDatabase($user, $mysqli){
  if(!isset($user['name'], $user['email'], $user['uid'], $user['password'], $user['directory'])){
    echo error('clientError', 400, 'Not all required information set');
  } else {
    if($stmt = $mysqli->prepare("INSERT INTO ausstehende_einladungen (name, uid, password, directory, email) VALUES (?,?,?,?,?)")){
      $stmt->bind_param("sssss", $user['name'], $user['uid'], $user['password'], $user['directory'], $user['email']);
      $stmt->execute();
      if($stmt->errno != 0){
        error('internalError', 500, $mysqli->error);
        return false;
      } else {
        return true;
      }
    }
  }
}

/**
 * Creates a user's directory in the /users/ directory and adds all initial files
 * @param $username the uid of the user which provides the name of the directory
 * @param $override [default = true]
 * @return true in case of success, otherwise false
 */
function createUserDirectory($username, $override = true){
  $path = ABS_PATH . "/users/$username/";
  if(mkdir($path)){
    $json = [
      "spruch" => "",
      "rufnamen" => [],
      "freundesfragen" => [],
      "eigeneFragen" => [],
      "allowedUsers" => []
    ];
    $jsonfile = json_encode($json, JSON_PRETTY_PRINT);
    if(!file_put_contents($path . $username . ".json", $jsonfile)){
      error('internalError', 500, 'Unable to create initial json file. Check if your creating user has rights to create files');
      return false;
    }
    // Copy default avatar from /img/avatar.jpg
    if(!copy(ABS_PATH . "/img/avatar.jpg", $path . "avatar.jpg")){
      error('internalError', 500, 'Unable to copy default avatar. Check if it\'s existant at all and if your creating user has rights to create files');
      return false;
    }
    return true;
  } else {
    error('internalError', 500, 'Unable to create user directory', ["path" => $path, "permissions" => umask()]);
    return false;
  }
}
/**
 * Reverts changes to the filesystem in case the insertion to database, performed after file system changes, fails
 * @param $user the user object containing all data
 * @return true, when done right, false otherwise
 */
function revertChanges($user){
  $path = ABS_PATH . "/users/{$user['uid']}/";
  try {
    if(!unlink($path.$user['uid'].'.json'))
      throw new Exception("File unlink failed");
    if(!unlink($path.'avatar.jpg'))
      throw new Exception("File unlink failed");
    if(!rmdir($path))
      throw new Exception("Directory removal failed");
    return true;
  } catch(Exception $e) {
    error("internalError", 500, $e->getMessage());
    return false;
  }
}

?>
