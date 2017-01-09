<?php
	include_once '../../includes/db_connect.php';
	include_once '../../includes/functions.php';

	secure_session_start();

	
	if(!isset($_GET['uid']) || $_GET['uid'] != $_SESSION['username']){
		echo error('clientError', 400, 'Bad Request');
	} else {
		$user = getMinimalUser($_GET['uid'], $mysqli);
		$path = "../users/{$user['directory']}/{$user['uid']}.json";
		$jsonstr = file_get_contents($path);
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