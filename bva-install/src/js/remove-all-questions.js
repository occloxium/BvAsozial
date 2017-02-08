/**
 * Handles front-end of deletion of all question in catalogue.
 * Pops up with a confirmation dialogue once the user hits 'Delete All'
 */

 // Import polyfill for dialogue

 // MDL-Dialogue

// After Button in overlay is pressed and Confirmation word is entered
$('.confirmation').click(function(){
  if(isValid()){

  } else {
    $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
      message: 'Du musst erst das Bestätigungswort eingeben!',
      timeout: 5000
    });
  }
});
