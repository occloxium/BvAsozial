$('#search').on('input', function(){
	$('.container ul').children().detach();
	$.ajax({
		url: './_ajax/search.php',
		method: 'get',
		data: {friend: $('#search').val()}
	}).done(function(data){
		try {
      var obj = JSON.parse(data);
      if(obj.success){
        $('.container ul').append(obj.html);
      } else {
        console.log(obj);
      }
    } catch (e) {
      console.error(data);
    }
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
    $('.container ul').children().detach();
		$.ajax({
			url: './_ajax/search.php',
			method: 'get',
			data: {friend: searchKey}
		}).done(function(data){
      try {
        var obj = JSON.parse(data);
        if(obj.success){
          $('.container ul').append(obj.html);
        } else {
          console.error(data.message);
        }
      } catch(e){
        console.error(data);
      }
		}).fail(function(e){
      console.error(e);
    });
	} else {
		$('.container ul').children().detach();
		$.ajax({
			url: './_ajax/init.php',
			method: 'get'
		}).done(function(data){
      try {
        var obj = JSON.parse(data);
        if(obj.success){
          $('.container ul').append(obj.html);
        } else {
          console.error(data.message);
        }
      } catch(e){
        console.error(data);
      }
		}).fail(function(e){
      console.error(e);
    });
	}
};

(function() {
  $.ajax({
    url: './_ajax/init.php',
    method: 'get'
  }).done(function(data){
    try {
      var obj = JSON.parse(data);
      if(obj.success){
        $('.container ul').append(obj.html);
      } else {
        console.error(data.message);
      }
    } catch(e){
      console.error(data);
    }
  }).fail(function(e){
    console.error(e);
  });
})();
