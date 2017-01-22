<?php
	require_once('functions.php');
	if(isset($e_uid, $e_pw, $e_name, $vorname, $invite_name)) :
		$enc_h_pw = base64_encode(hash('sha384', $e_pw));
		$enc_uid = base64_encode($e_uid);
		$linkData = [
			"invite_name" => base64_encode($invite_name),
			"uid" => base64_encode($e_uid),
			"pw" => base64_encode($e_pw),
			"name" => base64_encode($e_name),
			"vorname" => base64_encode($vorname)
		];
		return <<<MAIL
<div style="background-color: #252830;">
	<div style="margin: 0 auto; padding: 24px 0;">
		<div>
			<p style="color: #cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; font-size: 11px; max-width: 360px; width: 90%; text-align: center; margin: 0 auto;" align="center">
				Diese E-Mail wird nicht vernünftig angezeigt? Öffne sie im <a href="http://www.bvasozial.de/register/email?n={$linkData['invite_name']}&na={$linkData['vorname']}&u={$linkData['uid']}&p={$linkData['pw']}" style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">Browser!</a>
			</p>
		</div>
		<div style="max-width: 360px; width: 90%; margin: 0 auto; padding: 32px 0;">
			<img src="http://www.bvasozial.de/~statics/img/logo-cropped.png" alt="BvAsozial" width="66%" style="display: block; margin: 0 auto;" />
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center; font-size: 12px;" align="center">BvAsozial</p>
			<h1 style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center;" align="center">$invite_name</h1>
		</div>
		<div style="padding: 32px 0;">
			<h2 style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">Hallo {$vorname}!</h2>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				Für die Abizeitung haben wir uns dazu entschieden, Steckbriefe von Euch zu erstellen. Wir benutzen dafür die genau dafür entwickelte Plattform <b>BvAsozial</b>. Vielleicht hast du schon davon gehört!
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				Damit wollen wir ganz gezielt eure Informationen erhalten. Ihr stellt Fragen zu Eurer Person aus unserem großen Fragenkatalog und Ihr und Eure Freunde beantworten die. Ja, Eure Freunde!
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				Die könnt Ihr nämlich als solche hinzufügen und ihnen damit erlauben, bis zu 8 Fragen von Euch für Euch zu beantworten! All das wird dann hinterher ausgewertet und abgedruckt.
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				Also leg los, hier sind Deine Anmeldedaten, das Passwort kannst Du bei der Registrierung ändern, such Deine Fragen aus und füge Freunde hinzu, um selbst Fragen von anderen zu beantworten!
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: none; width: 75%; font-weight: bold; border-radius: 5px; font-size: 20px; margin: 24px auto; padding: 8px 12px; border: 1px solid #dddddd;">
				Benutzername: {$e_uid} <br />
				Passwort: {$e_pw}
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				<a style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center; display: block; max-width: 360px; width: 90%; margin: 64px auto 0;" href="http://www.bvasozial.de/registrieren/?bva_1=$enc_uid&bva_2=$enc_h_pw">http://www.bvasozial.de/registrieren/</a>
			</p>
			<p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
				<b>Dein Team der $invite_name</b>
			</p>
			<a href="http://www.bvasozial.de" style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center; display: block; max-width: 360px; width: 90%; margin: 64px auto 0;">http://wwww.bvasozial.de</a>
		</div>
	</div>
</div>
MAIL;
	else : error('clientError', 403, 'Forbidden');
	endif;
?>
