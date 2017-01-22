$('.mdl-checkbox__input').change(function(){
  if($('.mdl-checkbox__input:checked').length == 0){
    $(this).prop('checked', true).parent().addClass('is-checked');
  }
});
