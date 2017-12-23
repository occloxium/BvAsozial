<?php

  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

	/**
	*	Returns an HTML representation of a given user question and differs between friend questions and own ones.
	* 0 = eigeneFrage ; 1 = antwortAufEigeneFrage ; 2 = freundesfrage
	*/
	function frage($frage, $user, $freund, $i, $typ){
		switch($typ){
			case 0 :
				if(strlen($frage['antwort']) <= 35){
					return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			<input type="text" class="mdl-textfield__input" value="{$frage['antwort']}" id="item-$i" data-item="$i">
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$user['uid']}"data-category="eigeneFragen" data-item="$i">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
EOT;
				} else {
					return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			<textarea class="mdl-textfield__input" type="text" id="item-$i" data-item="$i">{$frage['antwort']}</textarea>
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$user['uid']}"data-category="eigeneFragen" data-item="$i">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
EOT;
				}
			break;
		case 1 :
			if(strlen($frage['antworten'][$freund['uid']]) <= 35){
				return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
			<input type="text" class="mdl-textfield__input" value="{$frage['antworten'][$freund['uid']]}" id="item-$i" data-item="$i" readonly>
			<label class="mdl-textfield__label" for="item-$i">{$freund['vorname']}'s Antwort</label>
		</div>
	</div>
</li>
EOT;
			} else {
				return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
			<textarea type="text" class="mdl-textfield__input" id="item-$i" data-item="$i" readonly>{$frage['antworten'][$freund['uid']]}</textarea>
			<label class="mdl-textfield__label" for="item-$i">{$freund['vorname']}'s Antwort</label>
		</div>
	</div>
</li>
EOT;
			}
			break;
		case 2 :
			if(strlen($frage['antworten'][$user['uid']]) <= 35){
				return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
			<input type="text" class="mdl-textfield__input" value="{$frage['antworten'][$user['uid']]}" id="item-$i" data-item="$i">
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$freund['uid']}" data-item="$i" data-category="freundesfragen" data-freund="{$freund['uid']}">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
EOT;
			} else {
				return <<<EOT
<li class="frage">
	<b>{$frage['frage']}</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
			<textarea type="text" class="mdl-textfield__input" id="item-$i" data-item="$i">{$frage['antworten'][$user['uid']]}</textarea>
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$freund['uid']}" data-item="$i" data-category="freundesfragen" data-freund="{$freund['uid']}">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
EOT;
			}
			break;
		}
	}
?>
