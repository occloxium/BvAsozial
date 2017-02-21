var $ = jQuery;
$('#search').on('input', function(){
	$('.benutzer-container ul').children().each(function(){
		$(this).detach();
	});
	$.ajax({
		url: '/mod/_ajax/search.php',
		method: 'post',
		data: {search: $('#search').val()}
	}).done(function(data){
    try {
      var obj = JSON.parse(data);
      if(obj.success){
        $('.benutzer-container ul').append(obj.output);
      } else {
        console.log(data);
      }
    } catch(e){
      console.error(data);
    }

	});
});
