# Versionsnotizen

### Moderatoren-Anpassungen

##### Rufnamen werden änderbar

*   `click` öffnet Overlay/Dialog mit altem Namen als Metadata

*   `submit` des Dialogs (Pseudo-Event, eigentlich nur `click` des "Ändern"-Buttons) postet mit jQuery ein Objekt der Struktur
    ```javascript
    {
      for: <String>,
      name: <String>,
      oldName: <String>,
      postedBy: <String>
    }
    ```
    an den `/includes/changeName.php` und updatet mit der HTML Response die Liste

*   **PHP**: `rufnamenliste` muss so angepasst werden, dass nur *Privilegierte* den Dialog öffnen können.

    Dazu wird `_DATA_.signedInUser` in `users.js` um eine Eigenschaft `isPrivileged` ergänzt, die ebenfalls aus dem `body`-Tag extrahiert wird. Beim Seitenaufbau wird diese durch PHP gesetzt, da PHP entscheiden kann, ob der `$requestor` die Seite überhaupt sehen darf. Der Wert dieser Eigenschaft steuert, ob der Benutzer überhaupt die Seite sehen darf (Profil-Privatsphäre).

    `is_privileged` ist als Zahl zwischen 0 und 2 konstruiert, 0 schließt jegliche Lese-(Betrachtungs-)Rechte von persönlichen Daten aus, 1 erlaubt das Betrachten der Persönlichen Eigenschaften, 2 erlaubt das Ändern.

    By default haben *Mods* und *Admins* immer 2, ebenso der Besitzer des Profils selbst. Der Besitzer des Profils kann unter *Eintstellungen* konfigurieren, ob für Freunde `isPrivileged` auf 0 oder 1 gesetzt wird.



*   Bei `click` auf den Rufnamen wird `isPrivileged` überprüft und entsprechend gehandelt.

    Bei 0 wird jede Aktion und Folgeaktion sofort terminiert, der Benutzer darf eigentlich keinen Zugriff auf die Rufnamen haben.

    Zu dem Zweck wird dann eine Funktion `updateRelation` aufgerufen, die dann eine möglicherweise manipulierte `isPrivileged`-Variable korrigiert und auch die Metadaten im `body`-Tag. Sollte beim weiteren Überprüfen auf dem Server auffallen, dass die Variable (erneut) manipuliert wurde, dann sendet der Server bekanntlich ein **error**-Objekt mit *403*. Wird diese Antwort von JavaScript interpretiert, wird ebenfalls `updateRelation` aufgerufen

    `updateRelation` muss also nicht nur die Variablen anpassen, sondern auch den dem Benutzer präsentierten Inhalt. Benutzer dafür `$().remove()` auf alle relevanten Daten, um irreversibel zu sein. Ggf. kann auch die ganze Seite neu geladen werden.

*   **PHP** : Um weitere Verschachtelung zu vermeiden, wird beim ``GET`` von `/users/index.php` die Rückgabe von `is_privileged`

    (a) überprüft und damit die Auswahl des Inhalts entschieden, um Fremdzugriff zu vermeiden und

    (b) die Variable `data-is-privileged` in den Body der HTML-Antwort eingebettet. Anhand des Wertes kann JavaScript entscheiden, welcher dynamische Inhalt präsentiert wird.

### Profil-Zugriff

Der Zugriff erfolgt auf **PHP**-Seite durch die Rückgabe von `is_privileged($requestor, $target, $mysqli)`.

Ist diese *false*, so wird danach überprüft, ob der Benutzer, auf dessen Profil zugegriffen wird, erlaubt hat, dass Fremde auch seine persönlichen Informationen sehen können. Ist dies der Fall, so wird die Seite so erstellt, als wäre der Benutzer mit dem zugreifenden Benutzer befreundet. Ansonsten
wird dem Benutzer, der zugreift, Sicht auf jegliche persönliche Informationen verwehrt, nur der Name, der Standard-Avatar, Möglichkeit der Freundschaftsanfrage und Freundesanzahl werden gezeigt.

Ist diese aber *true*, so wird die Seite gerendert, als wären A und B befreundet und die client-side-Variable `data-is-privileged` auf 2 gesetzt, um JavaScript zu informieren, dass der Zugreifende auch Schreibzugriff auf die Eigenschaften des Profils hat.

Wenn ein privilegierter Benutzer etwas an den Inhalten ändert, so wird das auf PHP-Seite erneut überprüft, wenn die Änderung stattfindet. Ist sie dann noch legitim, wenn Manipulation durch den Benutzer ausgeschlossen werden kann, so wird in einer Tabelle `logs` auf der Datenbank diese Aktion abgespeichert. Dieses Logging dient dem Umgehen einer inhaltlichen Evaluierung der Änderung.
Der Besitzer kann sich anzeigen lassen, *wer* *wann* etwas an seinem Profil geändert hat und die Änderung ggf. beanstanden. Für die inhaltliche Reversion ist er aber selbst zuständig.

Die Beanstandung führt aber dazu, dass, über eine weitere Tabelle namens `reports`, in der Benutzername, Zeitpunkt des Reports und **Prüfsumme** (jeder solchen Änderung wird eine Prüfsumme verliehen, `SHA-384 BENUTZERNAME . ZEITPUNKT`) der Änderung zur Identifikation gespeichert werden, ein Eintrag angelegt wird, der von *Admins* bewertet werden kann und der *Moderator* aufgrunddessen einen Strike erhalten kann. Nach 3 Strikes wird der Moderator-Account als *suspended* markiert und bei der Überprüfung durch `is_mod` nicht mehr gefunden. Damit wird somit der Moderatoren-Status eines Accounts zurückgerufen.

### Einstellungen

Ein neuer Menüeintrag führt zur Einstellungs-Seite, die accountbezogene Informationen zur Änderung präsentiert (E-Mail, Passwort ändern, Namen ändern etc.)
Auf der Einstellungsseite kann auch die Privatsphäre des Benutzers eingestellt werden. Zu diesem Zweck wird der Benutzer über die Privatsphäre-Einstellung informiert und die Tiefe der Änderungen erklärt.

JavaScript-Dateien, die bisher auf `/users/index.php` die persönlichen Daten ändern, werden entsprechend in eine `/js/settings.js`-Datei ausgelagert.

### Profilbilder

Die Benutzer sollen ihre Profilbilder selbst anpassen können. Das ist schon sei **1.0** klar, aber eine solche Implementierung ist bisher immer daran gescheitert, dass nicht klar definiert war, ob Bilder vom Server zurechtgeschnitten werden sollen, ob der Benutzer auswählen sollen darf, wie sein Bild zugeschnitten wird, ob die Bilder im Rohformat abgespeichert werden sollen und dann hinterher überlegt, wie zugeschnitten wird und auf der Seite einfach das Bild zentriert in ein Quadrat gequetscht wird. Eine derartige Standardisierung geht der Implementierung voraus.

Danach müssen, je nach Wahl, Fähigkeiten mit der PHP-Image-Manipulation Library angeeignet werden. Dies ist wieder mit mehr Aufwand verbunden. Zu diesem Zweck schlage ich Option 3 vor.

Der Benutzer lädt sein Bild einfach unverändert hoch, es ersetzt den Standard-Avatar in seinem Verzeichnis. Auf den Seiten, auf denen die Avatare verwendet werden (Profil, Freunde, Meine Freunde, Dashboard) werden anstatt `img`-Tags, die das Bild stauchen würden, Container in der Größe des vorgesehenen Bilds mit einem Unterobjekt, das das Bild als Hintergrund lädt, verwendet. Der Code dafür könnte so aussehen:
##### HTML
```html
<div class="profile-image__container">
  <div class="profile-image__inner" style="background-image: url(<Avatar>)">
  </div>
</div>
```
##### CSS
```css
.profile-image__container {
  width: <Breite>;
  height: <Höhe>;
  position: relative;
}
.profile-image_inner {
  background-image: <Default-Avatar>;
  background-size: cover;
  position: absolute;
  width: 100%;
  height: 100%;
}
```
