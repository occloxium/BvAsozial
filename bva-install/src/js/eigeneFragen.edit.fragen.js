var $ = jQuery;
$('.fragenkatalog-table td.frage').click(function (e) {
	$(this).parent().find('.mdl-checkbox__input').parent().click();
});
$('.mdl-checkbox__input').on('click', function (e) {
	if ($(this).prop('checked')) {
		if($('.mdl-checkbox__input:checked').length > 8){
			$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({message: "Entferne eine andere Frage aus dieser Rubrik, um mehr auswählen zu können", timeout: 2000});
			$(this).prop("checked", false);
		}
	}
});