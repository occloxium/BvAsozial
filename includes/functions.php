
<?php
  /**
   * functions.php
   * Contains all of the most significant procedural and scripty functions operating silently in the background
   * @author Alexander Bartolomey | 2017
   * @package BvAsozial 1.2
   */

  /*
   * COMMUNITCATION FUNCTIONS START --------------------------------------------
   */

  /**
   * Response function for communicating with the client in case of a successful request. Posts its return as text back to the client. Always contains boolean success = true
   * @param $args all additional arguments as an array. A message is helpful most of the times
   */
	function success($args){
		$array = ["success" => true];
		foreach($args as $k=>$e){
			$array[$k] = $e;
		}
		echo json_encode($array, JSON_PRETTY_PRINT);
	}
  /**
   * Response function for communicating with the client in case of an error. Posts its return as text back to the client
   * @param $type either if the error occured is a client error caused by a malicious request or a server error
   * @param $code the HTTP code linked to the error type
   * @param $msg a human-readable error message
   * @param $data an array containing all additional data
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

  /*
   * COMMUNICATION FUNCTIONS END -----------------------------------------------
   */

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
    return isInvited($uid, $mysqli) || userExists($uid, $mysqli);
  }

  /**
	 * GETs all users in the database
   * @param $mysqli the mysqli object refering to the database
   * @return a mysqli::result object for further iteration in loops
	 */
	function getUsers($mysqli){
		return $mysqli->query("SELECT * FROM person");
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
   * GETs a list of a user's friends
   * @param $username the user's id
   * @param $mysqli the mysqli refering to the database
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
        "eigeneFragen" => []
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
  /**
   * Checks if a user exists by searching in the `person` table at all, including the invited users. Reports if there's something horribly wrong with the database. Also see @link _findUser
   * @param $uid the uid to be searched
   * @param $mysqli the mysqli object refering to the database
   * @return true in case of a found user, otherwise false
   */
  function userExists($uid, $mysqli){
    if ($stmt = $mysqli->prepare('SELECT id FROM person WHERE uid = ? LIMIT 1')) {
      $stmt->bind_param('s', $uid);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows == 1) {
        return true;
      } elseif ($stmt->num_rows == 0) {
        return isInvited($uid, $mysqli);
      } else {
        error('internalError', 500, 'Potential dublicate! Fix the database immediately');
      }
    } else {
      error('internalError', 500, $mysqli->error . " ({$mysqli->errno})");
    }
    return;
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
   */
  function is_admin($username, $mysqli){
    if(isset($_SESSION['user']['is_admin'])){
      if($_SESSION['user']['is_admin']){
        return true;
      } else {
        return false;
      }
    } else {
      $stmt = $mysqli->prepare("SELECT boundTo FROM admins WHERE boundTo = ? LIMIT 1");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->store_result();
      if(isset($_SESSION)){
        if($stmt->num_rows == 1){
          $_SESSION['user']['is_admin'] = true;
          return true;
        } else {
          $_SESSION['user']['is_admin'] = false;
          return false;
        }
      } else {
        return false;
      }
    }
  }

  /**
   * Checks if a user is a moderator and, in case of that event, sets a session variable to mark the user as a moderator
   * @param $username the username to be searched for
   * @param $mysqli the mysqli object refering to the database
   */
  function is_mod($username, $mysqli){
    if(isset($_SESSION['user']['is_mod'])){
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
      if(isset($_SESSION)){
        if($stmt->num_rows == 1){
          $_SESSION['user']['is_mod'] = true;
          return true;
        } else {
          $_SESSION['user']['is_mod'] = false;
          return false;
        }
      } else {
        return false;
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
    if(is_admin($accessor) || is_mod($accessor) || $accessor == $target){
      return true;
    } else {
      return false;
    }
  }



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
   * Checks if a given user is invited.
   * @param $uid the uid of the potentially invited user
   * @param $mysqli the mysqli object refering to the database
   * @return true in case the uid was found, otherwise false
   */
  function isInvited($uid, $mysqli){
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


  /*
  * USER FUNCTIONS END ---------------------------------------------------------
  */

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

  /*
   * HTML FUNCTIONS START ------------------------------------------------------
   */

  /**
   * Loads up the general navigation and marks the active element
   * @param $active currently active section in navigation
   */
	function _getNav($active = ''){
	  require_once('navigation.php');
		echo '<script>var active = $("nav").attr("data-active"); $("a" + active).addClass("active");</script>';
	}
  /**
   * loads the basic html head tag and appends an optional css file
   * @param $extension optional css files to be appended
   */
  function _getHead($extension = []){
    require_once('html-head.php');
    $param = func_get_args();
    foreach($param as $p){
      $v = md5(microtime());
      echo '<link rel="stylesheet" type="text/css" href="/css/'. $p .'.css?v='. $v . '" />';
    }
  }

  /**
   * Generates HTML for the additional names a user can have and decides wether to include removal buttons based on relation between $user and $logged_in_user
   * @param $user the user whose names are requested
   * @param $logged_in_user currently logged user
   * @param $rufnamen an array containing all additional names a user can have
   * @return a string containing the HTML to be inserted
   */
  function rufnamenliste($user, $logged_in_user, $rufnamen){
    $html = "";
    $detailedUser = getMinimalUser($user);
    // signed-in user and requesting user are equivalent -> add option button to remove names
    if(empty($rufnamen))
      return "<li class=\"list__item\" id=\"noname\">{$detailedUser['name']} ist (noch) anonym</li>";
    if(is_privileged($logged_in_user, $user)){
      foreach($rufnamen as $name)
        $html .= "<span class=\"mdl-chip mdl-chip--deletable\" data-name=\"$name\"><span class=\"mdl-chip__text\">$name</span><button type=\"button\" class=\"mdl-chip__action\"><i class=\"material-icons\">cancel</i></button></span>";
    } else {
      foreach($rufnamen as $name)
        $html .= "<span class=\"mdl-chip\" data-name=\"$name\"><span class=\"mdl-chip__text\">$name</span></span>";
    }
    return $html;
  }

  /*
   * HTML FUNCTIONS END --------------------------------------------------------
   */

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
   * Checks the login state of a user by comparing hash sums of the current browser user agent concated with h
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

  /*
   * SECURITY FUNCTIONS END ----------------------------------------------------
   */

  /*
   * HELPER FUNCTIONS START ----------------------------------------------------
   */

  /**
   * Resolves all potential german language characters that would break the readability of the main database. Ümit Genc, der Übeltäter.
	 * @param a string potentially contaminated with special characters
   * @return a cleaned up string
   */
	function replaceSpecialCharacters($str){
		return str_replace(["ä","ö","ü","Ä","Ö","Ü","ß"],["ae","oe","ue","Ae","Oe","Ue","ss"],$str);
	}

  /**
   * Searches in an array of strings for certain element without case sensitivity
   * @param $needle the searched string
   * @param $haystack the search foundation to be searched in
   */
	function in_array_case_insensitive($needle, $haystack){
		foreach($haystack as $element){
			$element = strtolower($element);
		}
		return in_array(strtolower($needle), $haystack);
	}

	/**
	*	Checks if an entered array of is empty
  * @param $arr the 'to-be-checked' array
  * @return true in case the array is empty
	*/
	function array_empty($arr){
		$j = 0;
		foreach($arr as $e){
			if(empty($e))
				$j++;
		}
		return ($j >= count($arr) ? true : false);
	}

  /**
   * Converts a given image to jpg in case of all usual image types (@see http://stackoverflow.com/a/14549647/3306373)
   * @param $originalImage the image given
   * @param $outputImage path to output the existing image
   * @param $quality quality setting, default 100
   * @return 1 in case of success, otherwise 0
   */
  function convertImage($originalImage, $outputImage, $quality = 100)
  {
    $exploded = explode('.',$originalImage['name']);
    $ext = $exploded[count($exploded) - 1];
    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($originalImage['tmp_name']);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($originalImage['tmp_name']);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($originalImage['tmp_name']);
    else if (preg_match('/bmp/i',$ext))
        $imageTmp=imagecreatefrombmp($originalImage['tmp_name']);
    else
        return 0;

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
  }

  /*
   * HELPER FUNCTIONS END ------------------------------------------------------
   */
?>
