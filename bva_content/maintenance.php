<?php
header( "HTTP/1.0 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Retry-After: 600' );
 ?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<title>Occloxium</title>
		<style>
			html,
			body {
				font-family: 'Roboto', 'Helvetica', sans-serif;
				margin: 0;
			}
			main {
				flex-direction: column;
				display: flex !important;
			}
			.co {
				flex-grow: 1;
				min-height: calc(100vh);
				display: flex;
				align-items: center;
				position: relative;
				background: #252830 !important;
				color: #fff;
			}
			.bl {
				margin: 0 auto;
				max-width: 726px;
				width: 90%;
			}
			.ti {
				text-align: left;
				font-size: 3em;
				font-weight: bold;
			}
			.su {
				text-align: left;
				font-size: 2em;
				font-weight: 200;
				line-height: 1.2;
			}
		</style>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout">
			<main class="mdl-layout__content">
				<div class="co">
					<div class="bl">
						<h1 class="ti">BvAsozial</h1>
						<p class="su">Gerade bauen wir dran! Komm' später nochmal wieder!</p>
					</div>
				</div>
			</main>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
