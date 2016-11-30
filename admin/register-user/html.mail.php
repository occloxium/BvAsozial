<?php
	if(isset($e_uid, $e_pw, $e_name)) : 
		$vorname = explode(' ', $e_name)[0];
		return <<<MAIL
<div class="wrapper" style="background-color: #131814;">
		<div class="wrapper" style="background-color: #131814;">
      <table style="border-collapse: collapse;table-layout: fixed;color: #5c6266;font-family: Roboto,Tahoma,sans-serif;" align="center">
        <tbody>
					<tr>
						<td class="preheader__snippet" style="padding: 10px 0 5px 0;vertical-align: top;width: 280px;">
							<p style="Margin-top: 0;Margin-bottom: 0;font-size: 12px;line-height: 19px;">BvAsozial.de - Einladung</p>
						</td>
					</tr>
      	</tbody>
			</table>
			<div class="header__logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #c3ced9;font-family: Roboto,Tahoma,sans-serif;Margin-left: 20px;Margin-right: 20px;">
				<div class="logo-center" style="font-size:0px !important;line-height:0 !important;" align="center" id="emb-email-header">
					<img style="height: auto;width: 100%;border: 0;max-width: 190px;" src="http://www.bvasozial.de/~statics/img/logo-cropped.png" alt="" width="190" height="105">
				</div>
			</div>
      <div style="Margin-left: 10px;Margin-right: 10px;">
				<h1 style="Margin-top: 0;Margin-bottom: 20px;font-style: normal;font-weight: normal;color: #fff;font-size: 14px;line-height: 34px;font-family: avenir,sans-serif;text-align: center;">
					<span class="font-avenir"><span style="color:#ffffff">bvasozial.de</span></span>
				</h1>
			</div> 
			<div style="Margin-left: 10px;Margin-right: 10px;">
				<div style="line-height:40px;font-size:1px">&nbsp;</div>
			</div>
      <div style="Margin-left: 10px;Margin-right: 10px;">
      	<h1 class="size-56" style="Margin-top: 0;Margin-bottom: 20px;font-style: normal;font-weight: normal;color: #fff;font-size: 56px;line-height: 60px;font-family: avenir,sans-serif;text-align: center; font-size: 2em;">
					<span class="font-avenir">Abi-Zeitung des BVA 2016</span>
				</h1>
    	</div>
      <div style="font-size: 30px;line-height: 30px;mso-line-height-rule: exactly;">&nbsp;</div>
			<div style="width: 300px; margin: 0 auto">
				<h1 style="font-family: Roboto, Tahoma, sans-serif; width: 100%; font-size: 24px; color: #a6b5b3; text-align: center;">Hallo $vorname!</h1>
				<div class="column" style="padding: 1em;text-align: left;vertical-align: top;color: #a6b0b3;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;">
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;">Wir wollen Steckbriefe von euch erstellen. Dafür haben wir in den letzten Wochen eine Menge programmiert und präsentieren dir nun unsere extra dafür entwickelte Plattform:</p><p style="Margin-top: 0px;Margin-bottom: 0;"><b>bvasozial.de</b></p>
					</div>
				</div>
				<div class="column" style="padding: 1em;text-align: left;vertical-align: top;color: #a6b0b3;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;">
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;">Damit wollen wir ganz gezielt eure Informationen erhalten. Ihr stellt Fragen zu eurer Person aus unserem großen Fragenkatalog und Ihr und Eure Freunde beantworten die. Ja, eure Freunde!&nbsp;</p>
					</div>
				</div>
				<div class="column" style="padding: 1em;text-align: left;vertical-align: top;color: #a6b0b3;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;">
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;">Die könnt ihr nämlich als solche hinzufügen und ihnen damit erlauben, bis zu 8 Fragen von Euch für Euch zu beantworten! All das wird dann hinterher ausgewertet und abgedruckt</p>
					</div>
				</div>
				<div class="column" style="padding: 0;text-align: left;vertical-align: top;color: #a6b0b3;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;">
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;">Also leg los, hier sind deine Anmeldedaten, das Passwort kannst du noch ändern, such deine Fragen aus und füge Freunde hinzu, um selbst Fragen von anderen zu beantworten!</p>
					</div>
				</div>
				<div style="font-size: 20px;line-height: 20px;mso-line-height-rule: exactly;">&nbsp;</div>
				<div class="column" style="padding: 0;text-align: left;vertical-align: top; background: #131814; color: #ffffff;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;width: 600px;">
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;">Benutzername: {$e_uid}</p>
						<p style="Margin-top: 20px;Margin-bottom: 20px;">Passwort: {$e_pw}</p>
					</div>
					<div style="Margin-left: 10px;Margin-right: 10px;">
						<p style="Margin-top: 0;Margin-bottom: 0;"><a style="text-decoration: underline;transition: opacity 0.1s ease-in;color: #fff;" href="http://www.bvasozial.de/registrieren/">http://www.bvasozial.de/registrieren/</a></p>
					</div>
				</div>
      </div>
      <div style="font-family: Roboto, Tahoma, sans-serif; color: #5c6266; font-size: 20px;line-height: 20px;mso-line-height-rule: exactly;">&nbsp;</div>
			<div class="footer__inner" style="font-family: Roboto, Tahoma, sans-serif; color: #5c6266; padding: 0;font-size: 12px;line-height: 19px; width: 300px; margin: 24px auto 0;">
				<div>
					<div>Abi-Zeitungs-Team BvA mit dem bvasozial.de-Entwicklerteam mit Alexander Bartolomey, Mark Wolff und Jan-Philipp Kiel</div>
				</div>
				<div class="footer__permission" style="Margin-top: 18px; padding-bottom: 24px">
					<div>Du hast ein Foto eingesendet, also erhälst du eine Einladung zur bvasozial-Plattform</div>
				</div>
			</div>   
		</div>
MAIL;
	else : header("HTTP/1.1 403 Forbidden");
	endif;
?>