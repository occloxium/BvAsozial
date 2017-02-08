<?php
  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

	secure_session_start();


	if(!isset($_GET['uid']) || $_GET['uid'] != $_SESSION['user']['uid']){
		echo error('clientError', 400, 'Bad Request');
	} else {
		$user = $_SESSION['user'];
		$jsonstr = file_get_contents("../users/{$user['uid']}/{$user['uid']}.json");
		$obj = json_decode($jsonstr, true);
		$obj = $obj['eigeneFragen'];
		$output = "";
		$i = 1;
		foreach($obj as $frage){
			$output .= <<<FRAGE
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			<input type="text" class="mdl-textfield__input" value="{$frage['antwort']}" id="item-$i" data-item="$i">
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
FRAGE;
			$i++:
		}
		echo $output;
	}
?>
