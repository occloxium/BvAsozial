var $ = jQuery;

$('img.avatar').on('click', function (e) {
	e.stopPropagation();
	$('input.avatar').click();
});

$('input.avatar').on('change', function () {
  var formData = new FormData($('form#avatar-form')[0]);
	$.ajax({
    method: 'post',
    url: '/registrieren/upload.php',
    data: { type: 'img', data: formData },
    success: function( data ) {
      try {
        var obj = JSON.parse(data);
        if(!obj.hasOwnProperty(error)) {
          $('img.avatar').attr('src', obj.href);
        } else {
					debugger;
          console.error(data);
        }
      } catch (e){
        console.error(e);
      }
    },
    error: function(data){
			console.error(data);
		},
    cache: false,
    processData: false
  });
});
