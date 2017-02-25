$('form button').on('click', function(e){
	e.preventDefault();
	if($('input#password').val() !== $('input#passwordconfirm').val()){
		$('input#password, input#passwordconfirm').addClass('is-invalid');
		$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({'message': 'Die Passwörter sind nicht identisch', timeout: 2000});
		return;
	} else {
    $('input#password').val(new hash('SHA-384').update($('input#password').val()).getHash());
    $('input#oldpassword').val(new hash('SHA-384').update($('input#oldpassword').val()).getHash());
		$('input#passwordconfirm').prop('disabled', true);
		$('form').submit();
	}
});
$('form input').input(function(){
  $(this).removeClass('is-invalid');
});
