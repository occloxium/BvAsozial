# Meilensteine für BvAsozial in Version 1.2

## Für den Benutzer interessante Änderungen

- [x] __Zentraler Login__ : Alle Benuter, ob normale Benutzer, Admins oder Moderatoren benutzen die gleiche Oberfläche um sich anzumelden. Im Back-End wird dann entschieden, welcher Inhalt dem Benutzer präsentiert wird.
Wie bereits erwähnt bringt 1.5 eine neue Gruppe von Benutzern mit sich:
- [x] __Moderatoren__ : Admins können bestimmte normale Benutzer zu Moderatoren der Plattform machen. Moderatoren können
    - [ ] auf alle Profile zugreifen
    - [ s] Rufnamen löschen und verändern
    - [ ] Antworten auf Fragen aller Personen bearbeiten und löschen
- [ ] __Profilbilder__ : Nun können Benutzer ihre eigenen Profilbilder setzen und auch wieder löschen
- [ ] __Privatsphäre-Einstellungen für Profile__ : Nutzer können nun festlegen, ob sie mit der Suche gefunden werden wollen oder nicht und welche Daten von ihnen angezeigt werden sollen
- [ ] __Einstellungen__ : Über den neuen Menüpunkt "Einstellungen" können Benutzer nun verschiedene Sachen an ihrem Profil einstellen. Unter anderem sind die
- [ ] __Persönlichen Daten__ : In die Profileinstellungen verschoben worden und nun nicht länger über die Profilseite selbst einsehbar und bearbeitbar.

## Für Admins interessante Änderungen

- [ ] __Finalisierung__ : In Phase 3 der Plattform (Finalisierung der Profile) können nun selektierte Profile finalisiert werden. Der Benutzer kann sich dann nach der Anmeldung in sein Profil nur noch die finalisierte Version anschauen. Dafür ist ein HTML-Template gebaut worden, in das bei dem Klick auf "Finalisieren" für ein Profil automatisch die Daten eingefügt werden, die der Benutzer in Phase 2 ausgewählt hat

## Für Entwickler interessante Änderungen

- [ ] ~~Der komplette Zugriff auf die Seite wurde verändert. Der Zugriff läuft nicht mehr über die Verzeichnisse und den darin enthaltenen PHP-Dateien, sondern läuft zentralisiert über die im obersten Verzeichnis liegende index.php-Datei, die dann zu den gewünschten Inhalten weiterleitet. Das spart eine Menge Ballast in den Dateien, da Dinge wie includes nun zentral von der Oberdatei gesteuert werden und dadurch vor allem einheitlich sind. Auch wurde die Struktur von Zugriffen und Relationen geändert, die individuellen Pfade relativer Art wurden durch Absolut-Zugriffe gesteuert durch vordefinierte Konstanten ersetzt.~~ _Dieser Teil wurde gestrichen, aus reinen Zeit- und Komplexitätsgründen. Zum derzeitigen Zeitpunkt ist ein kompletter Rewrite der Plattform nicht empfehlenswert._
