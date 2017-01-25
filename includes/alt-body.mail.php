<?php
if(isset($linkData, $invite_name, $this)):
  return <<<EMAIL
Diese E-Mail wird nicht vernünftig angezeigt? Öffne sie
im Browser! ( http://{$linkData['domain']}/notify/email?n={$linkData['invite_name']}&na={$linkData['name']}&e={$linkData['msg']}&ma={$linkData['invite_mail']} )

BvAsozial

-------
Hallo {$this->name}!
-------

Wir benachrichtigen dich per E-Mail darüber, dass ein
Administrator etwas an deinem Profil geändert hat. Die von uns
registrierte Änderung sieht so aus:

{$this->message}

Sollte diese Änderung nicht von dir authorisiert worden
sein, so melde das bitte an, damit wir die Änderung widerrufen
können.

Dein Team der $invite_name

http://{$linkData['domain']}/ ( http://{$linkData['domain']}/ )

Du kannst dem Empfang weiterer solcher
Benachrichtigungs-E-Mails widersprechen, indem du dich auf
http://{$linkData['domain']}/anmelden/ ( http://{$linkData['domain']}/anmelden/ ) anmeldest und in den
Einstellungen deines Profils die Benachrichtigungen per E-Mail
abschaltest.
Wenn dein Account gelöscht wird oder dein Passwort von einem
Administrator geändert wird, wirst du trotzdem benachrichtigt.
EMAIL;
endif;
 ?>
