<?php
  if(isset($linkData,$invite_name,$this)):
  return <<<EMAIL
<div style="background-color: #252830;">
  <div style="margin: 0 auto; padding: 24px 0;">
    <div>
      <p style="color: #cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; font-size: 11px; max-width: 360px; width: 90%; text-align: center; margin: 0 auto;" align="center">
        Diese E-Mail wird nicht vernünftig angezeigt? Öffne sie im <a href="http://www.bvasozial.de/notify/email?n={$linkData['invite_name']}&na={$linkData['name']}&e={$linkData['msg']}&ma={$linkData['invite_mail']}" style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">Browser!</a>
      </p>
    </div>
    <div style="max-width: 360px; width: 90%; margin: 0 auto; padding: 32px 0;">
      <img src="http://www.bvasozial.de/~statics/img/logo-cropped.png" alt="BvAsozial" width="66%" style="display: block; margin: 0 auto;" />
      <p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center; font-size: 12px;" align="center">BvAsozial</p>
      <h1 style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center;" align="center">$invite_name</h1>
    </div>
    <div style="padding: 32px 0;">
      <h2 style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">Hallo {$this->name}!</h2>
      <p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
        Wir benachrichtigen dich per E-Mail darüber, dass ein Administrator etwas an deinem Profil geändert hat. Die von uns registrierte Änderung sieht so aus:
      </p>
      <p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: none; width: 75%; font-weight: bold; border-radius: 5px; font-size: 20px; margin: 24px auto; padding: 8px 12px; border: 1px solid #dddddd;">
        {$this->message}
      </p>
      <p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
        Sollte diese Änderung nicht von dir authorisiert worden sein, so melde das bitte an &lt;INVITE_MAIL&gt;, damit wir die Änderung widerrufen können.
      </p>
      <p style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; margin: 0 auto;">
        <b>Dein Team der $invite_name</b>
      </p>
      <a href="http://www.bvasozial.de" style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; text-align: center; display: block; max-width: 360px; width: 90%; margin: 64px auto 0;">http://wwww.bvasozial.de</a>
    </div>
    <div>
      <p style="color: #cccccc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; max-width: 360px; width: 90%; font-size: 11px; text-align: center; margin: 0 auto;" align="center">
        Du kannst dem Empfang weiterer solcher Benachrichtigungs-E-Mails widersprechen, indem du dich auf <a href="http://www.bvasozial.de/anmelden/" style="color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">http://www.bvasozial.de/anmelden/</a> anmeldest und in den Einstellungen deines Profils die Benachrichtigungen per E-Mail abschaltest. <br /> Wenn dein Account gelöscht wird oder dein Passwort von einem Administrator geändert wird, wirst du trotzdem benachrichtigt.
      </p>
    </div>
  </div>
</div>
EMAIL;
endif;
 ?>
