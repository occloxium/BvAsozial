
<?php
  /**
  *   Response function for communicating with the client in case of a successful request
  *   Can take any addition data as an array to return as JSON
  */
	function success($args){
		$array = ["success" => true];
		foreach($args as $k=>$e){
			$array[$k] = $e;
		}
		echo json_encode($array, JSON_PRETTY_PRINT);
	}
  /**
  *   Response function for communicating with the client in case of an error.
  *   Can take any additional data to return as JSON
  */
	function error($type, $code, $msg, $data = null){
		echo $data === null ? json_encode($error = [
			"error" => $type,
			"code" => $code,
			"message" => $msg
		], JSON_PRETTY_PRINT) : json_encode($error = [
			"error" => $type,
			"code" => $code,
			"message" => $msg,
			"data" => $data
		], JSON_PRETTY_PRINT);
	}

  /**
  *   Generates HTML for the additional names a user can have and decides wether to include removal buttons or not
  */
  function rufnamenliste($user, $logged_in_user, $rufnamen){
    $html = "";
    // signed-in user and requesting user are equivalent -> add option button to remove names
    if(empty($rufnamen))
        return "<li class=\"list__item\" id=\"noname\">{$user['name']} ist (noch) anonym</li>";
    if($user['uid'] === $logged_in_user){
        foreach($rufnamen as $name)
            $html .= "<span class=\"mdl-chip mdl-chip--deletable\" data-name=\"$name\"><span class=\"mdl-chip__text\">$name</span><button type=\"button\" class=\"mdl-chip__action\"><i class=\"material-icons\">cancel</i></button></span>";
    } else {
        foreach($rufnamen as $name)
            $html .= "<span class=\"mdl-chip\" data-name=\"$name\"><span class=\"mdl-chip__text\">$name</span></span>";
    }
    return $html;
  }
  /**
  *   GETs a users friends by their name
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

  /**
  *   Resolves all potential german language characters that would break the readability of the main database
	* 	Ümit Genc, der Übeltäter.
  */
	function replaceSpecialCharacters($str){
		return str_replace(["ä","ö","ü","Ä","Ö","Ü","ß"],["ae","oe","ue","Ae","Oe","Ue","ss"],$str);
	}

	/**
	*	GETs all users in the database as a mysqli::result object for further iteration in loops
	*/
	function getUsers($mysqli){
		return $mysqli->query("SELECT * FROM person");
	}
	/**
	*	Executes an acceptance of a friend request and adds the requesting user to the list of friends of the accepting user
	*	Returns true on success, false if there's no place left for friends to be added and null on error
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
   * Checks if the logging user is a validly invited user by comparing the preset password entered against the one in the database
   */
	function isValidInvite($uid, $password, $mysqli){
		$password = sha1($password);
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
   * Returns temporary user entry for requested $uid
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
  /*
      Returns TRUE if a user $from sent a friend request to a user $to, otherwise returns false
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
  /*
      Creates a friend request from $from to $to
  */
  function sendFriendRequest($from, $to, $mysqli){
      $result = $mysqli->query("INSERT INTO anfragen (von, an, sent) VALUES ('{$from}','{$to}', CURRENT_DATE)");
      if($mysqli->errno == 00000){
          $mysqli->query("UPDATE person SET sent_requests = sent_requests + 1 WHERE uid = '{$from}'");
          $mysqli->query("UPDATE person SET received_requests = received_requests + 1 WHERE uid = '{$to}'");
          return true;
      } else {
          return $mysqli->error;
      }
  }
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
	function getFriends($username, $mysqli){
		if($stmt = $mysqli->prepare("SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince ASC")){
			$friends = [];
			$stmt->bind_param('s',$username);
			$stmt->execute();
			$stmt->bind_result($friend);
			$stmt->store_result();
			while($stmt->fetch()){
				$friends[] = $friend;
			}
			return $friends;
		} else {
			return array();
		}
	}
	function getMinimalUser($username, $mysqli){
		$result = $mysqli->query("SELECT name, uid, directory FROM person WHERE uid = '$username'");
		if($result != false){
			return $result->fetch_assoc();
		} else {
			return null;
		}
	}
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
	function _getNav($active = ''){
	  require_once('navigation.php');
		echo '<script>var active = $("nav").attr("data-active"); $("a" + active).addClass("active");</script>';
	}
  /**
   * loads the basic html head tag and extends the output by the given
   * filename link tag
   */
  function _getHead($extension = ''){
    require_once('html-head.php');
    echo '<link rel="stylesheet" type="text/css" href="/css/'. $extension .'.css" />';
  }
	function secure_session_start(){
		//print_r($_SESSION);
		$session_name = 'bvasozial-secure-session';   // vergib einen Sessionnamen
		// Damit wird verhindert, dass JavaScript auf die session id zugreifen kann.
		$httponly = true;
		// Holt Cookie-Parameter.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams['lifetime'],
				$cookieParams["path"],
				$cookieParams["domain"],
				false,
				$httponly);
		// Setzt den Session-Name zu oben angegebenem.
		session_name($session_name);
		session_start(); // Startet die PHP-Sitzung
		session_regenerate_id();    // Erneuert die Session, löscht die alte.
	}
	function login($username, $password, $mysqli){
		if($stmt = $mysqli->prepare("SELECT * FROM login WHERE uid = ? OR email = ?")){
			$stmt->bind_param('ss',$username, $username);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($user_id, $user, $db_password, $email);
			$stmt->fetch();

			$password = sha1($password);
			if($stmt->num_rows == 1){
				if($db_password == $password){
					secure_session_start();
					$user_browser = $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['id'] = $user_id;
					$_SESSION['username'] = $user;
					$_SESSION['login_string'] = md5($password . $user_browser);
					return true;
				}
			}
		}
		return false;
	}
	function login_check($mysqli){
		if(isset($_SESSION['id'],$_SESSION['username'],$_SESSION['login_string'])){
			$user_id = $_SESSION['id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];

			$user_browser = $_SERVER['HTTP_USER_AGENT'];
			if($stmt = $mysqli->prepare("SELECT password FROM login WHERE id = ? LIMIT 1")){
				$stmt->bind_param('i', $user_id);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows == 1){
					$stmt->bind_result($password);
					$stmt->fetch();
					$login_check = md5($password . $user_browser);
					if($login_check == $login_string){
						return true;
					} else {
						return false;
					}
				}
			}
		}
		return false;
	}
	/**
	* Logs the user out and kills the current session
	*/
	function logout(){
		secure_session_start();
		$_SESSION = array(); // Reset session variables
		$params = session_get_cookie_params();
		setcookie(session_name(),
						'', time() - 42000,
						$params["path"],
						$params["domain"],
						$params["secure"],
						$params["httponly"]); // Override session cookie
		session_destroy(); // destroy user session
	}
	function in_array_case_insensitive($needle, $haystack){
		foreach($haystack as $element){
			$element = strtolower($element);
		}
		return in_array(strtolower($needle), $haystack);
	}
	/**
	*	If the entered array is empty, the function returns 1, otherwise 0
	*/
	function array_empty($arr){
		$j = 0;
		foreach($arr as $e){
			if(empty($e))
				$j++;
		}
		return ($j >= count($arr) ? true : false);
	}
?>
