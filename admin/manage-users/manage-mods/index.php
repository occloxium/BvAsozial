<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli) == true && $_SESSION['user']['is_admin']) :
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead('admin'); ?>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
		  <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<?php _getNav('manage-users'); ?>
			  </div>
			  <main class="mdl-layout__content mdl-color--grey-100">
				  <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="../../">Admin</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../">Benutzer verwalten</a>
              </li>
              <li class="breadcrumb__item">
                Moderatoren verwalten
              </li>
            </div>
					  <p class="mdl-typography--headline">Moderatoren verwalten<a href="add/" class="to-subsite"><i class="material-icons">subdirectory_arrow_right</i><span>Neuen Moderator ernennen</span></a></p>

					  <ul class="mdl-list">
					  <?php
              $query = 'SELECT * FROM person INNER JOIN moderatoren ON moderatoren.boundTo = person.uid;';
              if (!($stmt = $mysqli->prepare($query))) :
                echo $mysqli->error;
              else :
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows < 1) :
            ?>
				      <p class="none">Keine kürzlichen Registrierungen. <a class="none__anchor" href="/admin/register-user/">Lade neue Leute ein!</a></p>
					  <?php
                else :
                  $stmt->bind_result($id, $name, $uid, $erhaltene_anfragen, $gesendete_anfragen, $verzeichnis, $datum, $finalisiert, $allowedEmails, $id_2, $boundTo, $strikes, $suspended);
                  while ($stmt->fetch()) :
            ?>
  						<li class="mdl-list__item mdl-list__item--two-line">
  							<span class="mdl-list__item-primary-content">
  								<i class="material-icons mdl-list__item-avatar">person</i>
  								<span><?php echo $name ?></span>
  								<span class="mdl-list__item-sub-title"><?php echo $uid ?> | Verwarnungen: <?= $strikes ?> | Suspendiert: <?= ($suspended ? "Ja" : "Nein")?></span>
  							</span>
  							<span class="mdl-list__item-secondary-content">
  								<a href="./strike/?_=<?php echo base64_encode($uid) ?>" class="mdl-navigation__link"><i class="material-icons">mood_bad</i><span class="visuallyhidden">Verwarnen</span></a>
  								<a href="./suspend/?_=<?php echo base64_encode($uid) ?>" class="mdl-navigation__link"><i class="material-icons">block</i><span class="visuallyhidden">Suspendieren</span></a>
  							</span>
  						</li>
						<?php
	                endwhile;
	                $stmt->close();
		            endif;
			        endif;
			      ?>
					  </ul>
	        </div>
			  </main>
			</div>
		 <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php else : header('Location: ../'); exit; endif; ?>
