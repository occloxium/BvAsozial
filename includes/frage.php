<?php
  /**
   *  Helper script providing function for various variations of a
   *  Q&A HTML block
   *  @author Alexander Bartolomey | 2017
   *  @package BvAsozial 1.2
   */
  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

	/**
	*	Generates an HTML representation of a Q&A block
  * @param $frage the question array containing all required data about the question
  * @param $user user object containing data about the issued user
  * @param $freund user object of the person answering a foreign question
  * @param $i numerical index used for the identification of the question by JS
  * @param $typ numerical representation of the type of question
  *        0 = eigeneFrage ; 1 = antwortAufEigeneFrage ; 2 = freundesfrage;
  * @param $for variable defining the originator of an answer for a question
  *        Defaults to "Deine" for no value given
  * @param $no_button override for no save icon buttons - defaults to false
  * @param $multiple_inputs puts out multiple input (read only) fields for multiple
  *        friends answering one question
  * @param $readonly whether an answer is readonly for the requesting user or not
  * @return returns an HTML string of a Q&A block
	*/
	function frage($frage, $user, $freund, $i, $typ, $for = "", $no_button = false, $multiple_inputs = false, $readonly = false){
    $type_long; // define a literal representation of the type of question
    switch($typ){
      case 0 : $type_long = "eigeneFrage"; break;
      case 1 : $type_long = "eigeneFrage"; break;
      case 2 : $type_long = "freundesfrage"; break;
    }
    $button; // Define the HTML for the save button
    $input; // Define the HTML of the input section - in case content bigger than 35 chars, use a textarea
    if($multiple_inputs){ // There are no buttons allowed if multiple input boxes are present. The UI gets too messy
      $input = _multipleInputs($frage, $user, $i, $type_long, $readonly);
      $button = "";
      return '<li class="frage"><b>'.$frage['frage'].'</b><div class="antworten">'.$input.'</div></li>';
    } else {
      $for = ($for !== "" ? $for : "Deine"); // If not otherly specified, the questions response is users response
      $for = ($typ === 1 ? $freund['vorname'] : $for); // Overide in case of type 1 - friend answered users question
      $_freund = 'data-freund="' . ($typ === 2 ? $user['uid'] : $freund['uid']) . '"'; // HTML attr for JS
      $_for = 'data-for="'. ($typ === 2 ? $freund['uid'] : $user['uid']) .'"'; // HTML attr for JS
      $_antwort;
      switch($typ){
        case 0 :
          $_antwort = $frage['antwort'];
          break;
        case 1 :
          $_antwort = $frage['antworten'][$freund['uid']];
          break;
        case 2 :
          if(!isset($frage['antworten'][$user['uid']]))
            $_antwort = "";
          else
            $_antwort = $frage['antworten'][$user['uid']];
          break;
        default :
          $_antwort = "";
          break;
      }

      $_readonly = ($typ === 1 || $readonly ? "readonly" : ''); // readonly for type 1

      if(strlen($_antwort) <= 35){
        $input = '<input '.$_for.' data-category="'.$type_long.'" '.$_freund.' type="text" class="mdl-textfield__input" '.$_readonly.' value="'. htmlspecialchars($_antwort) .'" id="item-'.$i.'" data-item="'.$i.'">';
      } else {
        $input = '<textarea '.$_for.' data-category="'.$type_long.'" '.$_freund.' class="mdl-textfield__input" '.$_readonly.' type="text" id="item-'.$i.'" data-item="'.$i.'">'. htmlspecialchars($_antwort) .'</textarea>';
      }
      $input .= '<label class="mdl-textfield__label" for="item-$i">'.$for.' Antwort</label>';
      $button = '<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-item="'.$i.'"><i class="material-icons">save</i></button>';

      $button = ($typ === 1 || $no_button ? "" : $button); // override in case of type 1 - Type 1 questions do not have a save button
      return '<li class="frage"><b>'.$frage['frage'].'</b><div class="flex-container"><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">'.$input.'</div>'.$button.'</div></li>';
    }

	}
  function _multipleInputs($frage, $user, $i, $type_long, $readonly){
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    $html = "";
    $_readonly = ($readonly ? "readonly" : "");
    if(count($frage['antworten']) > 0){
      foreach($frage['antworten'] as $uid=>$antwort){
        $freund = getUser($uid, $mysqli);
        $for = $freund['vorname'];
        $_antwort = $frage['antworten'][$freund['uid']];
        $_for = 'data-for="'. $user['uid'] . '"';
        $_freund = 'data-freund="' . $freund['uid'] . '"';
        $input;
        if(strlen($antwort) <= 35){
          $input = '<input '.$_for.' '.$_readonly.' data-category="'.$type_long.'" '.$_freund.' type="text" class="mdl-textfield__input" value="'. htmlspecialchars($_antwort) .'" id="item-'.$i.'" data-item="'.$i.'">';
        } else {
          $input = '<textarea '.$_readonly.' '.$_for.' '.$_readonly.' data-category="'.$type_long.'" '.$_freund.' class="mdl-textfield__input" type="text" id="item-'.$i.'" data-item="'.$i.'">'. htmlspecialchars($_antwort) .'</textarea>';
        }
        $input .= '<label class="mdl-textfield__label" for="item-$i">Antwort von '.$for.'</label>';
        $html .= ('<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">'. $input ."</div>");
      }
    } else {
      $html = '<span class="none">Auf diese Frage hat noch niemand geantwortet</span>';
    }
    return $html;
  }
?>
