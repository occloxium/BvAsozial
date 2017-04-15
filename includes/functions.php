
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
			"message" => $msg,
      "backtrace" => debug_backtrace()
		], JSON_PRETTY_PRINT) : json_encode($error = [
			"error" => $type,
			"code" => $code,
			"message" => $msg,
			"data" => $data,
      "backtrace" => debug_backtrace()
		], JSON_PRETTY_PRINT);
	}

  /*
   * COMMUNICATION FUNCTIONS END -----------------------------------------------
   */

  require_once('user.functions.php');

  require_once('creation.functions.php');

  require_once('invites.functions.php');

  /**
   * Filters an array of friends of a given user by their visibility
   * @param $username the user the friends are referenced from
   * @param $friends the friends array
   * @param $mysqli the mysqli object refering to the database
   * @return a filtered array of friends based on their privacy settings
   */
  function filterUsers($username, $users, $mysqli){
    $filtered = [];
    foreach($users as $user){
      if(is_visible($user, $username, $mysqli))
        $filtered[] = $user;
    }
    return $filtered;
  }

  require_once('friends.functions.php');

  require_once('html.functions.php');

  require_once('security.functions.php');

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

  /*
   * DATA FETCH FUNCTIONS START ------------------------------------------------
   */

  /**
   * Gets the allowedUsers part of a users file
   * @param $user the requested user
   * @return array containing user handles allowed to inspect the $users profile
   */

  function getAllowedUsersList($user){
    return (json_decode(file_get_contents(ABS_PATH . "/users/data/$user/$user.json"), true)['allowedUsers']);
  }

  /*
   * DATA FETCH FUNCTIONS END --------------------------------------------------
   */

   /*
    * DATABASE FUNCTIONS START ------------------------------------------------
    */

   /**
    * Fetches single entries from a given table in the database.
    * Capable of multiple row results
    * @param $key the key of the result property
    * @param $table the table holding the requested data
    * @param $mysqli the mysqli object refering to the database
    * @param $comparator comparing operator to restrict the result set
    * @return either a single variable containing the data or the whole iterable
    */

  function fetch($key, $table, $comparatorKey, $comparator, $mysqli, $restriction = ""){
    if($stmt = $mysqli->prepare("SELECT $key FROM $table WHERE $comparatorKey = ? $restriction")){
      $stmt->bind_param("s", $comparator);
      $stmt->bind_result($res);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows > 1){
        return $stmt;
      } else {
        $stmt->fetch();
        return $res;
      }
    } else {
      return false;
    }
  }

   /*
    * DATABASE FUNCTIONS END --------------------------------------------------
    */
?>
