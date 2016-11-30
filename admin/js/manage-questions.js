$('a.change-question').click(function(){
  'use strict';
  var id = $(this).attr('id'),
      text = $(this).parents('li.mdl-list__item').attr('id', obj.id).children('form input.new-text').val();
});
$('form button.new-text').click(function(){
  var id = $(this).parent().parent().children()
   $.ajax({
    type: 'post',
    url: './cq.php',
    data: {'id': id}
  }).done(function(data){
    try {
      var obj = JSON.parse(data);
      if(obj.success){
        $('li.mdl-list__item#' + obj.id).children('.mdl-list__item-title').text(obj.text);
      } else {
        throw new Error(obj.msg);
      }
    } catch(ex) {
      console.error(ex.message);
      console.error(data);
    }
  });
});
$('a.remove-question').click(function(){
  'use strict';
  var id = $(this).attr('id');
  $.ajax({
    type: 'post',
    url: './rq.php',
    data: {'id': id}
  }).done(function(data){
    try {
      var obj = JSON.parse(data);
      if(obj.success){
        $('li.mdl-list__item#' + obj.id).detach();
      } else {
        throw new Error(obj.msg);
      }
    } catch(ex) {
      console.error(ex.message);
      console.error(data);
    }
  });
});
