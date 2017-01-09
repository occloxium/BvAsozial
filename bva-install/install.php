<?php
  // verify post input is valid
  $success = true;
  if(!isset($_POST)){
    $success = false;
    die("No input received");
  }
  if($_POST['db_host'] != "localhost"){
    $success = false;
    die("wrong input");
  }
  //create independant variables
  $db_host = "localhost";
  $db_user = $_POST['db_user'];
  $db_password = $_POST['db_password'];
  $db_name = $_POST['db_name'];
  // create temporary mysqli for creating tables
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name) or die('Wrong login data for mysql server');

  // Create tables
  require_once('preset/db.php');

  // Create admin
  $admin = ($_POST['admin_uid'] != "" ? $_POST['admin_uid'] : die('admin name cannot be null'));
  $admin_password = ($_POST['admin_password'] != "" && $_POST['admin_password'] == $_POST['admin_password_confirm'] ? $_POST['admin_password'] : die('admin password cannot be null and must be the confirmed right'));
  $admin_email = ($_POST['admin_email'] != "" ? $_POST['admin_email'] : die('admin email cannot be null'));
  $query .= "INSERT INTO admin_login (uid, email, password) VALUES ('$admin','$admin_email',SHA2('$admin_password', 384));";
  $result = $mysqli->multi_query($query);
  if($result == FALSE){
    echo $mysqli->error;
    $success = false;
    die('failure on db creation');
  }
  // Create root folder & include folder
  @mkdir(__DIR__ . '/bvasozial/');
  @mkdir(__DIR__ . '/bvasozial/includes/');

  // htaccess
  $include_path = realpath(__DIR__ . '/bvasozial/includes/');
  file_put_contents(__DIR__. "/bvasozial/.htaccess", "php_value include_path \"$include_path\"");

  // constants file
  $content = file_get_contents('preset/constants.pre.php');
  $content = str_replace(['$include_path','$db_host','$db_user','$db_password','$db_name'], ["'$include_path'","'$db_host'","'$db_user'","'$db_password'","'$db_name'"], $content);
  file_put_contents(__DIR__.'/bvasozial/includes/constants.php', $content);

  // move other predefined files
  function recursive_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          recursive_copy($src . '/' . $file,$dst . '/' . $file); }
        else {
          copy($src . '/' . $file,$dst . '/' . $file); }
      }
    }
    closedir($dir);
  }
  recursive_copy("src","bvasozial");
  if($success == true) :
?>
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BvAsozial - Installer</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<style>
			header {
				background: #252830;
				color: #ffffff;
				position: relative;
			}
			header .inner {
				padding: 4rem 1rem;
			}
			form {
			  margin: 0 auto;
			  padding: 2rem 1rem;
			}
			main {
			  max-width: 768px;
			  width: 80%;
			  margin: 0 auto;
			  padding: 2rem 1rem;
			}
			.progress {
				position: absolute;
				bottom: 0;
				width: 100%;
				height: 7px;
				border-radius: 0;
			}
      section {
        padding: 1.5rem 0;
      }
		</style>
	</head>
  <body>
		<header>
			<div class="inner">
				<h1>Installiere BvAsozial</h1>
				<h6>Abgeschlossen!</h6>
			</div>
			<div class="progress">
			  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		</header>
		<main>
      <h1 class="display-1">Super!</h1>
      <h3>Die Installation ist abgeschlossen!</h3>
      <p>
        Du kannst Dich nun auf <a href="bvasozial/admin/login/" target="_blank">/admin/login/</a> mit den gerade angegebenen Anmeldedaten anmelden, um damit zu beginnen, die Plattform zu popularisieren.
      </p>
      <section>
        <h5>Deine Admin-Anmeldedaten</h5>
        <div class="card">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              Benutzername: &nbsp;<strong> <?= $admin ?></strong>
            </li>
            <li class="list-group-item">
              E-Mail-Adresse: &nbsp;<strong> <?= $admin_email?></strong>
            </li>
            <li class="list-group-item">
              Passwort: &nbsp;<strong> <?= $admin_password ?></strong>
            </li>
          </ul>
        </div>
      </section>
      <section>
        <h5>MySQL-Anmeldedaten</h5>
        <p>
          Überlass' das besser den Profis und speichere sie Dir nur irgendwo sicher ab.
        </p>
        <div class="card">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              Benutzername: &nbsp;<strong> <?= $db_user ?></strong>
            </li>
            <li class="list-group-item">
              Passwort: &nbsp;<strong> <?= $db_password ?></strong>
            </li>
            <li class="list-group-item">
              Datenbank: &nbsp;<strong> <?= $db_name ?></strong>
            </li>
          </ul>
        </div>
      </section>
		</main>
	</body>
</html>
<?php else : echo ''; endif; ?>
