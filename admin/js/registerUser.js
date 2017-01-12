var $ = jQuery;
(function(){
	$('.register_form').find('#password').val(Math.random().toString(36).substr(2,10));
}());
var userExists = function(uid, callbackFailure){
	$.ajax({
		method: 'post',
		url: '/includes/userExists.php',
		data: {u: uid},
		success: function(data){
			try {
				var obj = JSON.parse(data);
				if(obj.exists == true){
					callbackFailure();
				}
			} catch(e){
				console.error(e);
			}
		}
	});
};
var validateForm = function(form){
	var name = form.find('#name').val(),
			uid = form.find('#uid').val(),
			email = form.find('#email').val()
			password = form.find('#password').val();
	if(name.length > 0 && uid.length > 0 && !form.children('#uidContainer').hasClass('is-invalid') && email.length > 0 && password.length > 0){
		return true;
	}
	return false;
}
var resizeIframe = function(obj){
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
$('#name.mdl-textfield__input').change(function(e){
	$('.mdl-tooltip#invalidInput').detach();
	$('.mdl-textfield#uidContainer').removeClass('is-invalid');
	if($(this).val().length > 0){
		var name = $(this).val().toLowerCase();
		var split = name.split(" ");
		var uid = split[0].substr(0, 1) + '_' + split[split.length-1].substr(0, 5);
		$('#uid.mdl-textfield__input').val(uid).parent().addClass('is-dirty');
		userExists(uid, function(){
			var c = window.componentHandler;
			$('#uidContainer').addClass('is-invalid');
			$('.register_form').append('<div class="mdl-tooltip" id="invalidInput" for="uidContainer">Benutzername bereits vergeben</div>');
			c.upgradeElement($('.mdl-tooltip#invalidInput')[0]);
			c.upgradeElement($('.mdl-textfield#uidContainer')[0]);
		});
	} else {
		$('#uid.mdl-textfield__input').val('').parent().removeClass('is-dirty');
	}
});
$('.register_form button').click(function(){
	$('.container#mail').detach();
	if(validateForm($('.register_form'))){
		var formData = new FormData($('.register_form')[0]);
		$.ajax({
			method: 'POST',
			url: '/includes/registerUser.php',
			data: $('.register_form').serialize(),
			error: function(data){
				console.error(data);
			}
		}).done(function(data){
			var container = $('<div></div>').addClass('mdl-card mdl-color--white mdl-shadow--2dp container container--border-bottom is-collapsed').attr('id','mail');
			$('main').append(container);
			try {
				var obj = JSON.parse(data);
				if (obj.success){
					container.append(obj.email);
				} else {
					container.append($('<pre></pre>').text(data));
				}
			} catch(e){
				container.append($('<pre></pre>').text(data));
				console.error(e);
			} finally {
				$('.register_form').find('#password').val(Math.random().toString(36).substr(2,8));
			}
		});
	}
});
