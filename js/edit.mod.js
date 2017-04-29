/**
 * event handlers for editing a users profile as mod
 * Alexander Bartolomey | 2017
 * @package BvAsozial 1.2
 */
var $ = jQuery;
/**
 * Event Handlers for Components like adding new chips
 */
events = {
  removeName: function() {
    $(this).parent().detach();
  },
  blur: function() {
    getSelection().removeAllRanges();
    var name = $(this).children('.mdl-chip__text').text();
    if (name.replace(/\s/g, '').length == 0) {
      $(this).detach();
    }
  },
  nameChanged: function() {
    if (!($(this).children('.mdl-chip__text').text().length > 0)) {
      $(this).addClass('emtpy');
    } else {
      $(this).removeClass('empty');
    }
  },
  appendNew: function() {
    $('.form--rufnamen ul li.none').detach();
    var el = $('<span></span>').addClass('mdl-chip mdl-chip--deletable empty').append([$('<span></span>').addClass('mdl-chip__text').attr('contenteditable', 'true').text(''), $('<button></button>').attr('type', 'button').addClass('mdl-chip__action').append($('<i></i>').addClass('material-icons').text('cancel'))]);
    $(this).before(el);
    el.children('.mdl-chip__text').focusin();
  },
  pushNamesToServer: function() {
    var names = [];
    $('.form--rufnamen ul .mdl-chip__text').each(function(){
      names.push($(this).text());
    });
    var postData = {
      'names': names,
      'for': $('main').attr('data-username'),
      'by': $('body').attr('data-mod')
    };
    $.ajax({
      method: 'post',
      data: postData,
      url: '/includes/addName.php'
    }).done(function(data) {
      try {
        var obj = JSON.parse(data);
        if (obj.success == true) {
          $('.form--rufnamen ul span').detach();
          $('.form--rufnamen ul').prepend(obj.html);
        } else {
          console.error(obj.message);
        }
      } catch (e) {
        console.error(e);
        console.error(data);
      }
    }).fail(function(data) {
      console.error(data);
    });
  },
  submitAnswers: function(e) {
    var fragen = [];
    e.forEach(function(el, index) {
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
      by: $('body').attr('data-mod')
    };
    $.ajax({
      type: 'post',
      url: '/includes/answerQuestions.php',
      data: postData
    }).done(function(d) {
      try {
        var obj = JSON.parse(d);
        if (!obj.success) {
          console.error(d);
        }
      } catch (error) {
        console.error(error);
      }
    }).fail(function(e) {
      console.error(e);
    });
  }
};

(function() {

})();

function Rufname(name, by) {
    this.for = $('main').attr('data-username');
    this.name = name;
    this.by = by || $('body').attr('data-mod');
}

$('.form--rufnamen button.save-all').on('click', events.pushNamesToServer);

$('.form--eigeneFragen ul li button').click(function() {
    events.submitAnswers($(this).prev('.mdl-textfield').children('input').get());
});
$('.form--eigeneFragen button.save-all, .form--freundesfragen button.save-all').click(function() {
    events.submitAnswers($(this).prev('ul').children('li').find('input').get());
});

$('.mdl-textfield__input').change(function() {
    $(this).parent().next('button').children('i').text('save')
    $(this).parents('ol').next('button.save-all').prop('disabled', false);
});

$('.form--rufnamen').on('click', '.mdl-chip__action', events.removeName).on('input', '.mdl-chip', events.nameChanged).on('focusout', '.mdl-chip', events.blur);

$('button.rufnamen__add').on('click', events.appendNew);
