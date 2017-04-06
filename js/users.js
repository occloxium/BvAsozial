/**
 * Front End Scripting for /users/
 * Alexander Bartolomey - 2017
 * @package BvAsozial 1.2
 */
var $ = jQuery;

/**
 * Holds the event handlers for rufnamen chips
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
  sendFriendRequest: function(){
    var request = {
      from: data.signedInUser.username,
      to: data.specatedUser.username,
    }
    $.ajax({
      data: request,
      method: 'post',
      url: '/includes/sendFriendRequest.php',
      cache: false
    }).done(function(data){
      try {
        var obj = JSON.parse(data);
        if(obj.success){
          $('button.btnaddfriend').prop('disabled', true);
          $('.mdl-snackbar').attr('data-success', 'true');
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
            message: "Deine Freundschaftsanfrage wurde verschickt",
            timeout: 2000
          });
        } else {
          $('.mdl-snackbar').attr('data-success', 'false');
          $('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
            message: "Es ist ein Fehler aufgetreten",
            timeout: 2000
          });
        }
      } catch(e) {
        console.error(e);
      }
    }).fail(function(data){
      console.error(data);
    });
  }
};

(function () {
	document.title = "BvAsozial - " + $('main').attr('data-name');
	$('textarea').autogrow({onInitialize: true});
}());

$('button#addQuote').click(function(){
  $.ajax({
    method: 'post',
    url: '/includes/addQuote.php',
    data: $(this).parent('form').serialize()
  }).done(function(d){
    $('button#addQuote').fadeOut(200, function(){
      $(this).addClass('mdl-button--icon mdl-color-text--white mdl-color--primary').text("").append($('<i></i>').addClass('material-icons').text('done')).fadeIn(500);
    });
    setTimeout(function(){
      $('button#addQuote').fadeOut(200, function(){
        $(this).removeClass('mdl-button--icon mdl-color-text--white mdl-color--primary').text('Spruch speichern').fadeIn(500).children('i').detach().parent();
      });
    }, 2000);
    console.log(d);
  })
});

var data = {
	signedInUser: {
		username: $('body').attr('data-signedInUser')
	},
	specatedUser: {
		username: $('main').attr('data-username'),
		name: $('main').attr('data-name'),
		directory: $('main').attr('data-directory')
	}
};

function Rufname(name, by){
  this.for = $('main').attr('data-username');
  this.name = name;
  this.by = by || $('main').attr('data-signedInUser');
}

$('.form--rufnamen button.save-all').on('click', events.pushNamesToServer);

$('button.rufnamen__add').on('click', events.appendNew);

$('button.btnaddfriend').on('click', events.sendFriendRequest);

$('.data-section__form').on('submit', function(e){
	e.preventDefault();
});
$('.form--rufnamen').on('click', '.mdl-chip__action', events.removeName).on('input', '.mdl-chip', events.nameChanged).on('focusout', '.mdl-chip', events.blur);
