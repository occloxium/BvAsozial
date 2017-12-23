var $ = jQuery;
(function () {
	document.title = "BvAsozial - " + $('main').attr('data-name');
	$('textarea').autogrow({onInitialize: true});
}());

$('ul.data-section__list').on("click", '.mdl-chip.mdl-chip--deletable > .mdl-chip__action', function(e){
  if($(this).hasClass('is-confirming')){
  	var data = {
      username: $('main').attr('data-username'),
      name: $(this).prev('span').text(),
      directory: $('main').attr('data-directory')
    };
	  $.ajax({
      method: 'post',
      url: '/includes/removeName.php',
      data: data
	  }).done(function(d){
      try {
	      var obj = JSON.parse(d);
	      if(obj.success){
          _EVENTS_.updateList_(obj.html);
	      } else {
          throw new Error(obj.msg);
	      }
      } catch (e) {
        console.log(d);
        console.log(e);
      }
	  });
  } else {
    $(this).addClass('is-confirming').children('i').text('done');
  }
});

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

$('button#updateUserData').click(function(){
	$.ajax({
		method: 'post',
		url: '/includes/updateUserData.php',
		data: $(this).parent('form').serialize()
	}).done(function(d){
		console.log(d);
	});
});

$('li button').click(function(){
	var frage = {
		frage: $(this).parent('div.flex-container').prev('b').text(),
		antwort: $(this).prev('.mdl-textfield').children('input').val(),
		von: $('body').attr('data-signedInUser'),
		frageID: $(this).attr('data-item'),
		type: $(this).attr('data-category'),
		for: $(this).attr('data-for')
	};
	var btn = $(this);
	$.ajax({
		method: 'post',
		url: '/includes/answerQuestions.php',
		data: {"fragen": [frage], "uid": $('body').attr('data-signedInUser')},
		cache: false
	}).done(function(d){
		try {
			var obj = JSON.parse(d);
			if(obj.success){
				btn.children('i').text('done_all');
			} else {
				console.error(d);
			}
		} catch (e){
			console.error(d);
		}
	}).fail(function(e){
		console.error(e);
	})
});

$('li.frage input').change(function(){
	var e = $(this).parent('div').next('button').children('i');
	if(e.text() == 'done_all')
		e.text('save');
})

var _DATA_ = {
	signedInUser: {
		username: $('body').attr('data-signedInUser')
	},
	specatedUser: {
		username: $('main').attr('data-username'),
		name: $('main').attr('data-name'),
		directory: $('main').attr('data-directory')
	}
};
var Rufnamen = {
	lastRequest: {
		for: "",
		name: "",
		postedBy: ""
	}
};
window.document['Rufnamen'] = Rufnamen;

var _EVENTS_ = {
	updateList_: function (html) {
		var list = $('.data-section__list');
		list.children('.mdl-chip').each(function(){
			$(this).detach();
		})
		list.prepend(html);
	},
	pushNameToServer: function() {
		var	postData = {
			for: _DATA_.specatedUser.username,
			name: $('#textfieldAddName').val(),
			postedBy: _DATA_.signedInUser.username
		};
		if(postData.name.length <= 1){
			var snackbar = $('.mdl-snackbar');
			snackbar.attr('data-success', "false");
			snackbar[0].MaterialSnackbar.showSnackbar({
				message: 'Fehler: Der eingegebene Name ist ungültig',
				timeout: 2000
			});
			console.warn('Kein Name.');
			return;
		}
		$(this).children('i.material-icons').text('done');
		$.ajax({
			method: 'post',
			data: postData,
			url: '/includes/addName.php'
		}).done(function(data){
			// Request done : wether successfully or failure occured has to be differentiated after this
			try {
				var obj = JSON.parse(data);
				window.document.Rufnamen.lastRequest = obj.request;
				var snackbar = $('.mdl-snackbar');
				if(obj.success == true){
					// success
					snackbar.attr('data-success', 'true');
					snackbar[0].MaterialSnackbar.showSnackbar({
						message: 'Dein vorgeschlagener Rufname wurde verpasst.',
						timeout: 2000,
						actionHandler: _EVENTS_.undoPushAndRemoveName,
						actionText: 'Rückgängig'
					});
					_EVENTS_.updateList_(obj.html);
					$('#saveNameAndPushToServer').children('i.material-icons').text('done_all');
					setTimeout(function(){
						$('button#btnaddname').click();
					}, 300);
				} else {
					// failure serverside
					snackbar.attr('data-success', 'false');
					snackbar[0].MaterialSnackbar.showSnackbar({
						message: obj.error,
						timeout: 2000,
					});
					$('#saveNameAndPushToServer').children('i.material-icons').text('save');
					$('.data-section__form .mdl-textfield').val("");
				}
			} catch(e){
				console.error(e);
				console.error(data);
			}
		}).fail(function(data){
			// Request failed
			console.error(data);
		});
	},
	appendForm: function () {
		$(this).off('click', _EVENTS_.appendForm).on('click', _EVENTS_.detachForm).children('i.material-icons').text('close');
		var textfield = $('<div></div>').addClass('mdl-textfield mdl-js-textfield mdl-textfield__floating-label flex-element flex-element--fill flex-element--no-padding').append([
			$('<input/>').addClass('mdl-textfield__input').attr('type','text').attr('id','textfieldAddName').attr('name','rufname').on('change', function(){
				$('#saveNameAndPushToServer').prop('disabled', false);
				$(this).off(this);
			}),
			$('<label></label>').addClass('mdl-textfield__label').attr('for','textfieldAddName').text('Rufnamen hinzufügen')
		]),
		button = $('<button></button').addClass('mdl-button mdl-js-button mdl-button--icon').attr('id','saveNameAndPushToServer').attr('type','button').on('click', _EVENTS_.pushNameToServer).append($('<i></i>').addClass('material-icons').text('save')),
		form = $('<div></div>').addClass('data-section__form data-section__form--active flex-container');
		window.componentHandler.upgradeElement(textfield[0]);
		window.componentHandler.upgradeElement(button[0]);
		form.append([textfield, button]);
		$('ul.data-section__list').after(form);
	},
	detachForm: function () {
		$(this).off('click', _EVENTS_.detachForm).on('click', _EVENTS_.appendForm).children('i.material-icons').text('add');
		$('.data-section__form').animate({height: 0}, 100,"swing", function(){
			$(this).detach();
		});
	},
	undoPushAndRemoveName: function () {
		var request = window.document.Rufnamen.lastRequest;
		request.undo = true;
		$.ajax({
			method: 'post',
			data: request,
			url: '/includes/addName.php'
		}).done(function(data){
			try {
				var obj = JSON.parse(data),
						snackbar = $('.mdl-snackbar');
				if(obj.success == true){
					snackbar.attr('data-success','true');
					snackbar[0].MaterialSnackbar.showSnackbar({
						message: 'Änderungen erfolgreich rückgängig gemacht',
						timeout: 2000,
					});
				} else {
					console.error(obj.error);
					snackbar.attr('data-success','false');
					snackbar[0].MaterialSnackbar.showSnackbar({
						message: obj.error,
						timeout: 2000,
					});
				}
				_EVENTS_.updateList_(obj.html);
			} catch(e){
				console.error(e);
				console.error(data);
			}
		}).fail(function(data){
			console.error(data);
		});
		window.document.Rufnamen.lastRequest = request;
	},
	sendFriendRequest: function(){
		var request = {
			from: _DATA_.signedInUser.username,
			to: _DATA_.specatedUser.username,
			//timestamp: null // Auf PHP Definieren damit nicht gefaked wird
		}
		$.ajax({
			data: request,
			method: 'post',
			url: '/includes/sendFriendRequest.php',
			cache: false,
			success: function(data){
				try {
					var obj = JSON.parse(data);
					if(obj.success){
						$('button.btnaddfriend').prop('disabled', true);
						$('.mdl-snackbar').attr('data-success', 'true');
						$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({message: "Deine Freundschaftsanfrage wurde verschickt", timeout: 2000});
					} else {
						$('.mdl-snackbar').attr('data-success', 'false');
						$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({message: "Es ist ein Fehler aufgetreten", timeout: 2000});
					}
				} catch(e) {
					console.error(e);
				}
			},
			error: function(data){
				console.error(data);
			}
		});
	}
};

$('button#btnaddname').on('click', _EVENTS_.appendForm);

$('button.btnaddfriend').on('click', _EVENTS_.sendFriendRequest);

$('.data-section__form').on('submit', function(e){
	e.preventDefault();
});
