var $ = jQuery;

function readURL(input){
	if(input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function(e){
			$('.image-preview').filter('[for="'+ $(input).attr("id").substr(1,1) +'"]').addClass('is-set').css({
				backgroundImage: 'url('+ e.target.result +')'
			}).attr('src', "");
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$('.image-preview').click(function(e){
	$('.image-input#i' + $(this).attr('for')).click();
});
$('.image-input').change(function(e){
	readURL(this);
});