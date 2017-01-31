var $ = jQuery;

var hash_password = function(form){
	var password = form.find("#password").val();
	form.find("#password").val(new hash('SHA-384').update(password).getHash());
}

$('form button').click(function(e){
	e.preventDefault();
	hash_password($('form'));
	$.ajax({
		method: 'post',
		url: '/includes/processLogin.php',
		data: $('form').serialize(),
	}).done(function(data){
		try {
			var obj = JSON.parse(data);
			if(obj.success){
				if(obj.data.is_admin){
					location.replace('../admin/');
				} else {
          location.replace('../');
        }
			} else {
				$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
					message: obj.message,
					timeout: 5000
				});
        $('form #password').val('');
			}
		} catch (e) {
			location.replace('../index.php');
		}
	}).fail(function(data){
		console.error(data);
	});
});
