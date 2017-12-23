<?php echo <<<FRAGE
<li class="frage">
<b>$frage</b>
<div class="flex-container">
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
<input type="text" class="mdl-textfield__input" value="$antwort" id="item-$i" data-item="$i">
<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
</div>
<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$user['uid']}" data-item="$i" data-category="freundesfragen" data-freund="{$user['uid']}">
<i class="material-icons">save</i>
</button>
</div>
</li>
FRAGE;
