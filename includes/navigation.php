<?php
if(isset($_SESSION['username'])){
  if($_SESSION['user']['is_admin']){
    ?>
    <nav class="navigation mdl-navigation mdl-color--blue-grey-800" data-active="#<?= $active ?>">
      <a class="mdl-navigation__link" id="home" href="/admin/"><i class="material-icons">home</i><span>Admin-Bereich</span></a>
      <a class="mdl-navigation__link" id="manage-users" href="/admin/manage-users/"><i class="material-icons">view_headline</i><span>Benutzer verwalten</span></a>
      <a class="mdl-navigation__link" id="register-user" href="/admin/register-user/"><i class="material-icons">playlist_add</i><span>Benutzer einladen</span></a>
      <a class="mdl-navigation__link" id="manage-invitations" href="/admin/manage-invitations/"><i class="material-icons">dns</i><span>Einladungen verwalten</span></a>
      <a class="mdl-navigation__link" id="manage-questions" href="/admin/manage-questions/"><i class="material-icons">question_answer</i><span>Fragen verwalten</span></a>
      <a class="mdl-navigation__link" href="/admin/logout/"><i class="material-icons">exit_to_app</i><span>Abmelden</span></a>
      <div class="mdl-layout-spacer"></div>
      <a class="mdl-navigation__link" id="support" href="/support/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
    </nav>
    <?php
  } else {
    ?>
    <nav class="navigation mdl-navigation mdl-color--blue-grey-800" data-active="#<?= $active ?>">
      <a class="mdl-navigation__link" href="/" id="root"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i><span>Übersicht</span></a>
      <a class="mdl-navigation__link" href="/users/me/" id="profil"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">face</i><span>Mein Profil</span></a>
      <a class="mdl-navigation__link" href="/freunde/meine-freunde/" id="meine-freunde"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">people</i><span>Meine Freunde</span></a>
      <a class="mdl-navigation__link" href="/freunde/" id="freunde"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">person_pin</i><span>Freunde finden</span></a>
      <a class="mdl-navigation__link" href="/freunde/anfragen/" id="anfragen"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">person_add</i><span>Freundschafts-<br>anfragen</span></a>
      <a class="mdl-navigation__link" href="/fragen/" id="fragen"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">question_answer</i><span>Fragen</span></a>
      <a class="mdl-navigation__link" href="/logout/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">exit_to_app</i><span>Abmelden</span></a>
      <div class="mdl-layout-spacer"></div>
      <a class="mdl-navigation__link" href="/support/" id="hilfe"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
    </nav>
    <?php
  }
} else {
  ?>
  <nav class="navigation mdl-navigation mdl-color--blue-grey-800" data-active="#<?= $active ?>">
    <a class="mdl-navigation__link" id="root" href="/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i><span>BvAsozial</span></a>
    <a class="mdl-navigation__link" id="login" href="/anmelden/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">exit_to_app</i><span>Anmelden</span></a>
    <a class="mdl-navigation__link" id="registrieren" href="/registrieren/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">face</i><span>Registrieren</span></a>
    <a class="mdl-navigation__link" id="über" href="/about/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">supervisor_account</i><span>Über Uns</span></a>
    <div class="mdl-layout-spacer"></div>
    <a class="mdl-navigation__link" href="/admin/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">supervisor_account</i><span>Administration</span></a>
    <a class="mdl-navigation__link" href="/support/" id="hilfe"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
  </nav>
  <?php
}
?>
