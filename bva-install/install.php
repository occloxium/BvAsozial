<?php
  ini_set('display_errors','1');
  $umask = umask();
  umask(0002);
  // verify post input is valid
  $success = true;
  if(!isset($_POST)){
    $success = false;
    die("No input received");
  }

  //create independent variables
  $db_host = $_POST['db_host'];
  $db_user = $_POST['db_user'];
  $db_password = $_POST['db_password'];
  $db_name = $_POST['db_name'];
  $smtp_host = $_POST['smtp_host'];
  $smtp_port = $_POST['smtp_port'];
  $smtp_mail = $_POST['smtp_mail'];
  $smtp_password = $_POST['smtp_password'];
  $smtp_name = $_POST['smtp_name'];
  $domain = $_POST['domain'];

  // create temporary mysqli for creating tables
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name) or die('Wrong login data for mysql server');

  // Create tables
  require_once('preset/db.php');

  // Create admin
  $admin = ($_POST['admin_uid'] != "" ? $_POST['admin_uid'] : die('admin name cannot be null'));
  $admin_password = ($_POST['admin_password'] != "" && $_POST['admin_password'] == $_POST['admin_password_confirm'] ? $_POST['admin_password'] : die('admin password cannot be null and must be the confirmed right'));
  $admin_email = ($_POST['admin_email'] != "" ? $_POST['admin_email'] : die('admin email cannot be null'));
  $result = $mysqli->multi_query($query );

    do {
        if($result == FALSE){
            echo $mysqli->error;
            $success = false;
            die('failure on db creation');
        }
    } while ($mysqli->next_result());

//  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name) or die('Wrong login data for mysql server');
  $query = "INSERT INTO login (id, uid, email, password) VALUES (1,'$admin','$admin_email',SHA2('$admin_password', 384));";
  $query .= "INSERT INTO admins (id, boundTo) VALUES (1,'$admin');";
  $result = $mysqli->multi_query($query);

  $mysqli->close();

  // Create root folder & include folder
  mkdir(__DIR__ . '/../includes/');

  // htaccess
  $include_path = realpath(__DIR__ . '/../includes/');
  file_put_contents(__DIR__. "/../.htaccess", "php_value include_path \"$include_path\"");

  // constants file
  $content = file_get_contents('preset/constants.pre.php');
  $content = str_replace(
    ['$domain','$include_path','$db_host','$db_user','$db_password','$db_name','$smtp_host','$smtp_port','$smtp_mail','$smtp_password','$smtp_name'],
    ["'$domain'","'$include_path'","'$db_host'","'$db_user'","'$db_password'","'$db_name'","'$smtp_host'","'$smtp_port'","'$smtp_mail'","'$smtp_password'","'$smtp_name'"],
    $content);
  file_put_contents(__DIR__.'/../includes/constants.php', $content);

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
  recursive_copy("src","../");

  // Create composer Installer
  $composer_installer = "<?php exec('php composer.phar install'); exec('php composer.phar require phpmailer/phpmailer'); ?>";
  file_put_contents('../composer-installer.php', $composer_installer);
  require_once('../composer-installer.php');
  // Created Composer and PHPMailer

  if($success == true) :
    umask($umask);
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
      @media print {
        header {
          display: none;
        }
        main > p {
          display: none;
        }
        main > h1 {
          display: none;
        }
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
        <h5>SMTP-Anmeldedaten</h5>
        <p>
          Überlass' das besser den Profis und speichere sie Dir nur irgendwo sicher ab.
        </p>
        <div class="card">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              Benutzername: &nbsp;<strong> <?= $smtp_mail ?></strong>
            </li>
            <li class="list-group-item">
              Passwort: &nbsp;<strong> <?= $smtp_password ?></strong>
            </li>
            <li class="list-group-item">
              Name: &nbsp;<strong> <?= $smtp_name ?></strong>
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
