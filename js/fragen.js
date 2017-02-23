/**
 * Event handlers for saving and changing answers of editable questions
 * Alexander Bartolomey | 2017
 * @package BvAsozial 1.2
 */

var $ = jQuery;

var events = {
  getFriendlyQuestions: function(el){
    var uid = el.children('input').attr('data-uid');
    $.ajax({
      method: 'post',
      url: '/fragen/_ajax/freundesfragen.php',
      data: {'uid': uid}
    }).done(function(data){
      try {
        var obj = JSON.parse(data);
        if(obj.success){
          $('.container--freundesfragen').css('height', $('.container--freundesfragen').height() + "px");
          $('.container--freundesfragen *').detach()
          $('.container--freundesfragen').append(obj.html);
          componentHandler.upgradeElements($('.container--freundesfragen').get());
          $('.container--freundesfragen').css('height', "auto").show(333);
        } else {
          console.error(data);
        }
      } catch (e) {
        console.error(e);
        console.error(data);
      }
    }).fail(function(e){
      console.error(e);
    });
  },
  submitAnswers: function(e) {
    var fragen = [];
    e.forEach(function (el, index) {
      fragen.push({
        antwort: $(el).val(),
        von: $(el).attr('data-freund'),
        frageID: $(el).attr('data-item'),
        type: $(el).attr('data-category'),
        "for": $(el).attr('data-for')
      });
    });
    var postData = {
      "fragen": fragen,
      by: $('main').attr('data-username')
    };
    $.ajax({
      type: 'post',
      url: '/includes/answerQuestions.php',
      data: postData
    }).done(function (d) {
      try {
        var obj = JSON.parse(d);
        if (!obj.success) {
          console.error(d);
        }
      } catch (error) {
        console.error(error);
      }
    }).fail(function (e) {
      console.error(e);
    });

  }
};
(function() {
  //$('textarea').autogrow({onInitialize: true});
  events.getFriendlyQuestions($('.mdl-radio.first.uid'));
})();
$('ul li button').click(function() {
  events.submitAnswers($(this).prev('.mdl-textfield').children('.mdl-textfield__input').get());
});
$('.container--freundesfragen').on('click', 'ul li button', function(e){
  events.submitAnswers($(this).prev('.mdl-textfield').children('.mdl-textfield__input').get());
});
$('button.save-all').click(function() {
  events.submitAnswers($(this).prev('ul').children('li').find('.mdl-textfield__input').get());
});
$('.container--freundesfragen').on('click', 'button.save-all', function(){
  events.submitAnswers($(this).prev('ul').children('li').find('.mdl-textfield__input').get());
});
$('.mdl-textfield__input').change(function() {
  $(this).parent().next('button').children('i').text('save')
  $(this).parents('ol').next('button.save-all').prop('disabled', false);
});
$('.mdl-radio.uid').click(function(){
  events.getFriendlyQuestions($(this));
});
