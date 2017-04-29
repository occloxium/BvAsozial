<?php
/*
 * SECURITY FUNCTIONS START --------------------------------------------------
 */

/**
 * Creates a secure session from an recent session
 */
function secure_session_start(){
  $session_name = 'bvasozial-secure-session';
  $httponly = true;
  $cookieParams = session_get_cookie_params();
  session_set_cookie_params($cookieParams['lifetime'], $cookieParams["path"], $cookieParams["domain"], SECURE, $httponly);
  session_name($session_name);
  session_start();
  session_regenerate_id();
  validate_user_session();
}

/**
 * Logs a user in
 * @param $username the given user to be logged in. Is also able to compare also against an email adress
 * @param $password the hashed password to compare against
 * @param $mysqli the mysqli object refering to the database
 * @return true in case of a correct password & username
 */
function login($username, $password, $mysqli){
  if($stmt = $mysqli->prepare("SELECT * FROM login WHERE uid = ? OR email = ?")){
    $stmt->bind_param('ss',$username, $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $user, $db_password, $email);
    $stmt->fetch();
     // Password already hashed transmitted @since 1.2.25
    if($stmt->num_rows == 1){
      if($db_password == $password){
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
         $_SESSION['id'] = $user_id;
         $_SESSION['user'] = getUser($user, $mysqli);
         _checkGroups($user, $mysqli);
         $_SESSION['login_string'] = hash('sha384', $password . $user_browser);
         return true;
      }
    }
  }
  return false;
}

/**
 * Checks the login state of a user
 * @param $mysqli the mysqli object refering to the database
 */
function login_check($mysqli){
  if(isset($_SESSION['user']['uid'],$_SESSION['login_string'])){
    $login_string = $_SESSION['login_string'];
    $username = $_SESSION['user']['uid'];
    $user_browser = $_SERVER['HTTP_USER_AGENT'];
    // CHANGE: admins and users are stored in the same table, though admins get marked by an extra table containing the bound admin usernames
    // is_admin determines if a found username is an admin
    // $group [boolean]
    if(($password = _findUser($username, $mysqli)) != false){
      $login_check = hash('sha384', $password . $user_browser);
      if($login_check == $login_string){
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  return false;
}
/**
* Logs the user out and kills the current session
*/
function logout(){
  if(!isset($_SESSION))
    secure_session_start();
  $_SESSION = array(); // Reset session variables
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]); // Override session cookie
  session_destroy(); // destroy user session
}

/**
 * Validates the user object of a session
 */
function validate_user_session(){
  if(isset($_SESSION['user'])){
    $u = $_SESSION['user'];
    if(!isset($u['uid'], $u['name'], $u['is_admin'], $u['is_mod'])){
      logout();
    }
  } else {
    logout();
  }
}

/*
 * SECURITY FUNCTIONS END ----------------------------------------------------
 */
?>
