$('#global-progress')[0].addEventListener('mdl-componentupgraded', function() {
  this.MaterialProgress.setProgress(0);
});
/**
 * Tries to log user in using Ajax. Handles returns by itself
 * @param uid the uid of the user logging in
 * @param password the hashed password of the user
 */
var attemptLogin = function(uid, password){
  $.ajax({
    method: 'post',
    url: '/includes/isValidInvite.php',
    data: {"uid": uid, "password": password}
  }).done(function(data){
    try {
			var obj = JSON.parse(data);
			if(obj.isValid){
				$('form').submit();
			} else {
        if(obj.message == "Wrong password"){
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
  					message: "Falsches Passwort für diesen Benutzernamen. Versuche es bitte erneut.",
  					timeout: 5000
  				});
          $('form input#password').val("");
        } else if(obj.message == "invite expired") {
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
            message: "Deine Einladung ist ausgelaufen oder wurde entfernt. Wenn du meinst, dass das fälschlicherweise passiert ist, bewirb dich erneut.",
            timeout: 5000
          });
          $('form input#password').val("");
          $('form input#uid').val("");
        } else if(obj.message == "Unknown username and / or password") {
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
            message: "Unbekannter Benutzername und / oder Passwort. Stelle erst sicher, das die Daten korrekt sind.",
            timeout: 5000
          });
          $('form input#password').val("");
          $('form input#uid').val("");
        } else {
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
            message: "Es ist ein Fehler aufgetreten. Versuche es später nochmal.",
            timeout: 5000
          });
          $('form input#password').val("");
          $('form input#uid').val("");
        }
			}
		} catch (e) {
      console.error(data);
      $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
        message: "Es ist ein schwerwiegender Fehler aufgetreten. Versuche es später nochmal (Details stehen in der Konsole).",
        timeout: 5000
      });
		}
	}).fail(function(data){
		console.error(data);
    $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
      message: "Es ist ein schwerwiegender Fehler aufgetreten. Versuche es später nochmal (Details stehen in der Konsole).",
      timeout: 5000
    });
	});
}

$('form button').click(function(){
  hash_password($('form'));
  attemptLogin($('form input#uid'), $('form input#password'));
});
