var $ = jQuery;

$('form button').click(function(e){
	e.preventDefault();
	$.ajax({
		method: 'post',
		url: '/includes/processLogin.php',
		data: $('form').serialize(),
	}).done(function(data){
		try {
			var obj = JSON.parse(data);
			if(obj.success){
				location.replace('../');
			} else {
				$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
					message: obj.message,
					timeout: 5000
				});
			}
		} catch (e) {
			location.replace('../index.php');
		}
	}).fail(function(data){
		console.error(data);
	});
});