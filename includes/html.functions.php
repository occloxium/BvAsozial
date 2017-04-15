<?php
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
function rufnamenliste($user, $logged_in_user, $rufnamen, $mysqli){
  $html = "";
  $detailedUser = getMinimalUser($user, $mysqli);
  $i = 0;
  if(empty($rufnamen))
    return "<li class=\"none\">{$detailedUser['name']} ist (noch) anonym</li>";
  if(is_privileged($logged_in_user, $user, $mysqli)){
    foreach($rufnamen as $name){
      $html .= "<span id=\"r-$i\" class=\"mdl-chip mdl-chip--deletable\" contenteditable=\"true\"><span class=\"mdl-chip__text\">$name</span><button type=\"button\" class=\"mdl-chip__action\"><i class=\"material-icons\">cancel</i></button></span>";
      $i++;
    }
  } else {
    foreach($rufnamen as $name){
      $html .= "<span id=\"r-$i\" class=\"mdl-chip\" data-name=\"$name\"><span class=\"mdl-chip__text\">$name</span></span>";
      $i++;
    }
  }
  return $html;
}

/*
 * HTML FUNCTIONS END --------------------------------------------------------
 */
?>
