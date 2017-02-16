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
  * @return returns an HTML string of a Q&A block
	*/
	function frage($frage, $user, $freund, $i, $typ, $for = "", $no_button = false){
    $for = ($for !== "" ? $for .= 's' : "Deine"); // If not otherly specified, the questions response is users response
    $for = ($typ === 1 ? $freund['vorname'] : $for); // Overide in case of type 1 - friend answered users question
    $type_long; // define a literal representation of the type of question
    switch($typ){
      case 0 : $type_long = "eigeneFrage"; break;
      case 1 : $type_long = "eigeneFrage"; break;
      case 2 : $type_long = "freundesfrage"; break;
    }
    $_freund = ($freund != null ? 'data-freund="' . $freund['uid'] . '"' : ""); // HTML attr for JS
    $_for = ($typ == 2 ? 'data-for="'. $freund['uid'] .'"' : 'data-for="'. $user['uid'] .'"'); // HTML attr for JS
    $button = <<<BUTTON
<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" $_for data-category="$type_long" data-item="$i" $_freund>
  <i class="material-icons">save</i>
</button>
BUTTON;
    $button = ($typ === 1 || $no_button ? "" : $button); // override in case of type 1 - Type 1 questions do not have a save button
    $_antwort = ($typ === 1 ? $frage['antworten'][$freund['uid']] : $frage['antwort']); // override for type 1
    $_antwort = ($typ === 2 ? $frage['antworten'][$user['uid']] : $_antwort); // override for type 2
    $_readonly = ($typ === 1 ? "readonly" : ''); // readonly for type 1
    $input; // Define the HTML of the input section - in case content bigger than 35 chars, use a textarea
    if(strlen($frage['antwort']) <= 35){
      $input = '<input type="text" class="mdl-textfield__input" '.$_readonly.' value="'. $_antwort .'" id="item-'.$i.'" data-item="'.$i.'">';
    } else {
      $input = '<textarea class="mdl-textfield__input" '.$_readonly.' type="text" id="item-'.$i.'" data-item="'.$i.'">'. $_antwort .'</textarea>';
    }
    return <<<FRAGE
<li class="frage">
  <b>{$frage['frage']}</b>
  <div class="flex-container">
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
      $input
      <label class="mdl-textfield__label" for="item-$i">$for Antwort</label>
    </div>
    $button
  </div>
</li>
FRAGE;
	}
?>
