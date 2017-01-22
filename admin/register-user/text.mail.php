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
Diese E-Mail wird nicht vernünftig angezeigt? Öffne sie
im Browser! ( http://www.bvasozial.de/register/email?n={$linkData['invite_name']}&na={$linkData['vorname']}&u={$linkData['uid']}&p={$linkData['pw']} )

BvAsozial
$invite_name
-------
Hallo {$vorname}!
-------

Für die Abizeitung haben wir uns dazu entschieden,
Steckbriefe von Euch zu erstellen. Wir benutzen dafür
die genau dafür entwickelte Plattform BvAsozial.
Vielleicht hast du schon davon gehört!

Damit wollen wir ganz gezielt eure Informationen
erhalten. Ihr stellt Fragen zu Eurer Person aus unserem
großen Fragenkatalog und Ihr und Eure Freunde beantworten
die. Ja, genau Eure Freunde!

Die könnt Ihr nämlich als solche hinzufügen und ihnen
erlauben, bis zu 8 Fragen von Euch für Euch zu
beantworten! All das wird dann hinterher ausgewertet und
abgedruckt.

Also leg los, hier sind Deine Anmeldedaten, das Passwort
kannst Du bei der Registrierung ändern, such Deine Fragen
aus und füge Freunde hinzu, um selbst Fragen von anderen
zu beantworten!

Benutzername: {$e_uid}
Passwort: {$e_pw}

Registrieren! ( http://www.bvasozial.de/registrieren/?bva_1=$enc_uid&bva_2=$enc_h_pw )

Dein Team der $invite_name

http://wwww.bvasozial.de ( http://www.bvasozial.de )
MAIL;
else : error('clientError', 403, 'Forbidden');
endif;
 ?>
