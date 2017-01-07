<?php
    include_once 'includes/functions.php';
    include_once 'includes/db_connect.php';

    secure_session_start();
    if (login_check($mysqli) == true) :
        $user = getUser($_SESSION['username'], $mysqli);
?>
<!doctype html>
<html lang="de">
<!-- Internal Landing page - Overview -->
	<head>
	  <meta charset="utf-8">
	  <meta name="description" content="">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	  <title>BvAsozial</title>

	  <meta name="mobile-web-app-capable" content="yes">
	  <link rel="icon" sizes="192x192" href="images/android-desktop.png">

	  <meta name="apple-mobile-web-app-capable" content="yes">
	  <meta name="apple-mobile-web-app-status-bar-style" content="black">
	  <meta name="apple-mobile-web-app-title" content="BvAsozial">

	  <link rel="shortcut icon" href="images/favicon.png">

	  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
	  <link rel="stylesheet" href="/css/material-icons.css">

	  <link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
	  <link rel="stylesheet" href="/css/sidewide.css">
	  <link rel="stylesheet" href="/css/overview.css">
	  <script src="/js/jquery-2.1.4.min.js"></script>
	  <script src="/js/pie-chart.src.js"></script>
	</head>
	<body>
	  <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
	    <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
	      <header class="drawer-header">
	        <img class="avatar" src="<?php echo '/users/'.$user['directory'].'/avatar.jpg'?>">
	        <div>
	          <div class="flex-container">
	            <span><?php echo $user['name']; ?></span>
	            <div class="mdl-layout-spacer"></div>
	          </div>
	        </div>
	      </header>
	      <?php get_nav('home') ?>
	    </div>
  	<main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?php echo $user['uid'] ?>" data-directory="<?php echo $user['directory'] ?>" data-friends="<?php echo $user['freundesanzahl'] ?>">
	    <header class="header mdl-layout__header mdl-color--blue-grey-900 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Übersicht</span>
          <div class="mdl-layout-spacer"></div>
          <form class="header__form" action="/freunde/" method="get">
            <label>Suche nach Freunden</label>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
              <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
								<i class="material-icons">search</i>
							</label>
              <div class="mdl-textfield__expandable-holder">
                <input class="mdl-textfield__input" type="text" name="search" id="search">
                <label class="mdl-textfield__label" for="search">Suche nach Freunden...</label>
            	</div>
            </div>
          </form>
        </div>
	    </header>
	    <div class="mdl-grid content">
	        <div class="mdl-color--white personal-data mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
	            <div class="mdl-cell mdl-cell--4-col mdl-cell--4-desktop">
	                <img class="avatar" src="<?php echo './users/'.$user['directory'].'/avatar.jpg'?>">
	            </div>
	            <div class="mdl-cell mdl-cell--8-col mdl-cell--8-desktop">
								<div class="data-container" id="personalData">
									<span class="data-container__title"><?php echo $user['name'] ?></span>
									<ul class="data-list">
										<li class="data-list__item">
											<span class="data-list__item--index">Erhaltene Freundschaftsanfragen</span>
											<span class="data-list__item--content"><?php echo $user['received_requests'] ?></span>
										</li>
										<li class="data-list__item">
											<span class="data-list__item--index">Gesendete Freundschaftsanfragen</span>
											<span class="data-list__item--content"><?php echo $user['sent_requests'] ?></span>
										</li>
										<li class="data-list__item">
											<span class="data-list__item--index">Benutzerverzeichnis</span>
											<span class="data-list__item--content">/users/<?php echo $user['directory'] ?>/</span>
										</li>
										<li class="data-list__item">
											<span class="data-list__item--index">Registriert seit</span>
											<span class="data-list__item--content"><?php echo date('d.m.Y', strtotime($user['registered_since'])) ?></span>
										</li>
									</ul>
								</div>
							</div>
	          </div>
	          <div class="charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col ">
							<h1>Ziele</h1>
							<div class="mdl-grid">
								<div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
									<svg width="200px" height="200px" class="chart-1"></svg>
									<span>Eigene beantwortete Fragen</span>
								</div>
								<div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
									<svg width="200px" height="200px" class="chart-2"></svg>
									<span>Von Freunden beantwortete Fragen</span>
								</div>
								<div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
									<svg width="200px" height="200px" class="chart-3"></svg>
									<span>Persönliche Angaben</span>
								</div>
								<script>
									var pie1 = new PieChart({
										total: 8,
										initial: 0,
										color: '#00b84f',
										radius: 30,
	                  strokeWidth: 8,
										container: '.chart-1'
									});
									var pie2 = new PieChart({
										total: 8,
										initial: 0,
										color: '#00b84f',
										radius: 30,
	                	strokeWidth: 8,
										container: '.chart-2'
									});
									var pie3 = new PieChart({
										total: 8,
										initial: 0,
										color: '#00b84f',
										radius: 30,
	                  strokeWidth: 8,
										container: '.chart-3'
									});
								</script>
							</div>
						</div>
	        </div>
        </main>
      </div>
      <script src="/js/progress.js"></script>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php
  else :
?>
<!-- Blank HTML Preset -->
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<title>BvAsozial</title>

		<meta name="mobile-web-app-capable" content="yes">
		<link rel="icon" sizes="192x192" href="/images/android-desktop.png">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="BvAsozial">
		<link rel="apple-touch-icon-precomposed" href="/images/ios-desktop.png">

		<meta name="msapplication-TileImage" content="/images/touch/ms-touch-icon-144x144-precomposed.png">
		<meta name="msapplication-TileColor" content="#252830">

		<link rel="shortcut icon" href="/images/favicon.png">

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="/css/material-icons.css">

		<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/frontend.css">

		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>

	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
			<div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<?php get_nav('home'); ?>
			</div>
			<main class="mdl-layout__content mdl-color--grey-100">
				<div class="container--fullsize mdl-color--blue-grey-900">
					<div>
						<img class="logo logo--center" src="/img/logo-cropped.png">
						<p class="mdl-typography--headline mdl-color-text--white">BvAsozial.de</p>
						<p class="mdl-typography--body-1 mdl-color-text--white">Eine Plattform der Abi-Zeitung des<br>Bettina-von-Arnim-Gymnasiums Dormagen</p>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--white" href="/anmelden/">
							Anmelden
						</a>
					</div>
					<div class="navicon mdl-color-text--blue-grey-800">
						<a class="material-icons" href="#einleitung">keyboard_arrow_down</a>
					</div>
				</div>
			</main>
		</div>
		<div class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button class="mdl-snackbar__action" type="button"></button>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php endif; ?>
