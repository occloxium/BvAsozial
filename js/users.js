/**
 * Front End Scripting for /users/
 * Alexander Bartolomey - 2017
 * @package BvAsozial 1.2
 */
var $ = jQuery;
"use strict";
/**
 * Holds the event handlers for rufnamen chips
 */
var events = {
   removeName: function(){
     var id = $(this).parent().attr('id');
     if(rufnamenState.currentState.hasOwnProperty(id)){
       rufnamenState.currentState[id] = "";
     }
     if(rufnamenState.initialState.hasOwnProperty(id)){
       rufnamenState.initialState[id] = "";
    }
    console.log(rufnamenState.currentState);
    $(this).parent().detach();
   },
   blur: function(){
     getSelection().removeAllRanges();
     var id = $(this).attr('id'), name = $(this).children('.mdl-chip__text').text();
     if(name.length > 0 && name != " "){
       rufnamenState.updateCurrentState($(this));
     } else {
       if(rufnamenState.initialState.hasOwnProperty(id)){
         $(this).children('.mdl-chip__text').text(rufnamenState.initialState[id]);
       } else {
         delete rufnamenState.currentState[id];
         $(this).detach();
       }
     }
   },
   nameChanged: function(){
     rufnamenState.updateCurrentState($(this));
     if(!($(this).children('.mdl-chip__text').text().length > 0)){
       $(this).addClass('emtpy');
     } else {
       $(this).removeClass('empty');
     }
   },
   appendNew: function () {
     $('.form--rufnamen ul li.none').detach();
     var id = "r-" + Object.keys(rufnamenState.currentState).length;
     var el = $('<li></li>').addClass('mdl-chip mdl-chip--deletable empty').attr('id', id).append([$('<span></span>').addClass('mdl-chip__text').attr('contenteditable', 'true').text(''), $('<button></button>').attr('type','button').addClass('mdl-chip__action').append($('<i></i>').addClass('material-icons').text('cancel'))]);
     $(this).before(el);
     el.children('.mdl-chip__text').focusin();
     rufnamenState.updateCurrentState(el).bindEventHandlers([el],{'click': [events.removeName, '.mdl-chip__action'], 'input': [events.nameChanged], 'focusout': [events.blur]});
   },
   pushNamesToServer: function() {
     var names = [];
     for(var key in rufnamenState.currentState){
       if(rufnamenState.currentState.hasOwnProperty(key) && rufnamenState.currentState[key] != ""){
         names.push(rufnamenState.currentState[key]);
       }
     }
     var postData = {
       'names': names,
       'for': data.specatedUser.username,
       'by': data.signedInUser.username
     };
     $.ajax({
       method: 'post',
       data: postData,
       url: '/includes/addName.php'
     }).done(function(data){
       try {
         var obj = JSON.parse(data);
         var snackbar = $('.mdl-snackbar');
         if(obj.success == true){
           $('.form--rufnamen ul span').detach();
           $('.form--rufnamen ul').prepend(obj.html);
           rufnamenState.setInitialState().setCurrentState();
           snackbar.attr('data-success', 'true');
           snackbar[0].MaterialSnackbar.showSnackbar({
             message: 'Die Rufnamen wurden abgespeichert',
             timeout: 2000,
           });
         } else {
           snackbar.attr('data-success', 'false');
           snackbar[0].MaterialSnackbar.showSnackbar({
             message: obj.error,
             timeout: 2000,
           });
         }
       } catch(e){
         console.error(e);
         console.error(data);
       }
     }).fail(function(data){
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

/**
 * Rufnamen Management Object
 * DOES NOT CHECK FOR DUBLICATES!
 * That is done by PHP on the backend returning an error in case of dublicates
 */
function RufnamenState(){
  /**
   * Holds the initialState
   */
  this.initialState = {};
  /**
   * Sets the given state object based on the HTML
   */
  this._setState = function(state){
    state = {};
    $('.form--rufnamen ul span.mdl-chip').each(function(key, el){
      var id = $(this).attr('id'), val = $(this).children('span.mdl-chip__text').text();
      state[id] = val;
    });
    return state;
  };
  /**
   * Returns an HTML representation of the given state
   */
  this._getState = function(state){
    var html = [];
    $('.form--rufnamen ul span.mdl-chip').detach();
    for(var i = 0; i < Object.keys(state).length; i++){
      var key = "r-" + i;
      html.push($('<li></li>').addClass('mdl-chip mdl-chip--deletable').attr('id', key).append([$('<span></span>').addClass('mdl-chip__text').prop('contenteditable', true).text(state[key]), $('<button></button>').addClass('mdl-chip__action').append($('<i></i>').addClass('material-icons').text('cancel'))]));
    }
    $('.form--rufnamen ul').append(html);
  };
  /**
   * Overrides initial state
   */
  this.overrideInitialState = function(){
    this.initialState = this.currentState;
    return this;
  };
  /**
   * Sets the inital state
   */
  this.setInitialState = function(){
    this.initialState = this._setState(this.initialState);
    return this;
  };
  /**
   * Returns an HTML representation of the saved initial state
   */
  this.getInitialState = function(){
    this._getState(this.initialState);
    return this;
  };
  /**
   * Holds the current state and gets updated
   */
  this.currentState = {};
  /**
   * Updates the current state | Only capable of adding elements
   */
  this.updateCurrentState = function(el){
    var id = el.attr('id');
    this.currentState[id] = el.children('.mdl-chip__text').text();
    return this;
  };
  /**
   * Removes a certain element from the current state set
   */
  this.removeElementFromCurrentState = function(e){
    var id = e.attr('id');
    if(this.currentState.hasOwnProperty(id)){
      this.currentState[id] = "";
      //delete this.currentState[id];
    }
    if(this.initialState.hasOwnProperty(id)){
      this.initialState[id] = "";
      //delete this.initialState[id];
    }
    return this;
  }
  /**
   * Fully sets the current state
   */
  this.setCurrentState = function(){
    this.currentState = this._setState(this.currentState);
    return this;
  };
  /**
   * Returns an HTML representation of the current state
   */
  this.getCurrentState = function(){
    this._getState(this.currentState);
    return this;
  };
  /**
   * Binds a set of event handlers to a set of elements
   */
  this.bindEventHandlers = function(setOfElements,events){
    $.each(setOfElements, function(key, val){
      for(event in events){
        if(event[1] === undefined)
          $(val).on(event, events[event][0]);
        else
          $(val).on(event, events[event][1], events[event][0]);
      }
    });
  };
  this.setInitialState();
  this.setCurrentState();
  this.bindEventHandlers($('.form--rufnamen ul .mdl-chip'), {'click': [events.removeName, '.mdl-chip__action'], 'input': [events.nameChanged], 'focusout': [events.blur]});
  return this;
};

rufnamenState = new RufnamenState();

(function () {
	document.title = "BvAsozial - " + $('main').attr('data-name');
	//$('textarea').autogrow({onInitialize: true});
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
