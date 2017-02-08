var $ = jQuery;

$('.mdl-list#received').on('click', 'button.accept-request', function(e){
	var d = {
		user: $('main').attr('data-username'),
		friend: $(this).attr('data-requestor')
	},
	ref = this;
	$.ajax({
		method: 'post',
		url: '/includes/addAsFriend.php',
		data: d,
		success: function(data){
			try {
				var obj = JSON.parse(data);
				if(obj.success){
					$(ref).parent().parent().detach();
				} else {
					if(obj.code === 502)
						$(ref).parent().parent().detach();
					console.error(data);
				}
			} catch (e){
				console.error(e);
			}
		}
	});
});