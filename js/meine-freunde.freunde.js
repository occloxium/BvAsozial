$('#search').on('input', function(){
	$('.freunde-container ul').children().each(function(){
		$(this).detach();
	});
	$.ajax({
		url: './_ajax/search.php',
		method: 'get',
		data: {friend: $('#search').val()}
	}).done(function(data){
		$('.freunde-container ul').append(data);
	});
});
$('.mdl-list').on('click', '.unfriend', function(e){
	e.stopPropagation();
	var request = {
		friend: $(this).attr('data-uid'),
		uid: $('main').attr('data-username')
	};
	$.ajax({
		method: 'post',
		url: './_ajax/unfriend.php', 
		data: request
	}).done(function(data){
		try {
			var obj = JSON.parse(data);
			if(obj.success){
				listFriends($('#search').val());
			} else {
				console.error(data);
			}
		} catch (e) {
			console.error(data);
		}
	})
});
var listFriends = function(searchKey){
	if(searchKey.length > 0){
		$('.freunde-container ul').children().forEach(function(){
			$(this).detach();
		});
		$.ajax({
			url: './_ajax/search.php',
			method: 'get',
			data: {friend: searchKey}
		}).done(function(data){
			$('.freunde-container').append(data);
		});
	} else {
		$('.freunde-container ul').children().forEach(function(){
			$(this).detach();
		});
		$.ajax({
			url: './_ajax/init.php',
			method: 'get'
		}).done(function(data){
			$('.freunde-container ul').append(data);
		});
	}
}