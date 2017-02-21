/**
 * Management objects & event handlers for editing a users profile as mod
 * Alexander Bartolomey - 2017
 * @package BvAsozial 1.2
 */
var $ = jQuery;
// rufnamen section start
/**
 * Rufnamen Management Object
 * DOES NOT CHECK FOR DUBLICATES!
 * That is done by PHP on the backend returning an error in case of dublicates
 */
function rufnamenState(){
  /**
   * Holds the event handlers for rufnamen chips
   */
  this.events = {
    removeName: function(){
      rufnamenState.removeElementFromCurrentState($(this).parent());
      $(this).parent().detach();
    },
    blur: function(){
      window.getSelection().removeAllRanges();
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
    }
  };
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
    for(var i = 0; i < state.length; i++){
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
    delete this.currentState[id];
    if(this.initialState.hasOwnProperty(id)){
      delete this.initialState[id];
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
  this.bindEventHandlers($('.form--rufnamen ul .mdl-chip'), {'click': [this.events.removeName, '.mdl-chip__action'], 'input': [this.events.nameChanged], 'focusout': [this.events.blur]});
  return this;
};
(function(){
  window.rufnamenState = new rufnamenState();
})();

function Rufname(name, by){
  this.for = $('main').attr('data-username');
  this.name = name;
  this.by = by || $('main').attr('data-signedInUser');
}

/**
 * WINDOW Event Handlers for Components like adding new chips
 */
var events = {
  appendNew: function () {
    $('.form--rufnamen ul li.none').detach();
    var id = "r-" + Object.keys(rufnamenState.currentState).length;
    var el = $('<li></li>').addClass('mdl-chip mdl-chip--deletable empty').attr('id', id).append([$('<span></span>').addClass('mdl-chip__text').attr('contenteditable', 'true').text(''), $('<button></button>').attr('type','button').addClass('mdl-chip__action').append($('<i></i>').addClass('material-icons').text('cancel'))]);
    $(this).before(el);
    el.children('.mdl-chip__text').focusin();
    window.rufnamenState.updateCurrentState(el).bindEventHandlers([el],{'click': [rufnamenState.events.removeName, '.mdl-chip__action'], 'input': [rufnamenState.events.nameChanged], 'focusout': [rufnamenState.events.blur]});
	},
  /**
   * TODO Update event handlers
   */
	pushNamesToServer: function() {
		var names = [];
    for(var key in window.rufnamenState.currentState){
      if(window.rufnamenState.currentState.hasOwnProperty(key)){
        names.push(window.rufnamenState.currentState[key]);
      }
    }
    var postData = {
      'names': names,
      'for': $('main').attr('data-username'),
      'by': $('body').attr('data-mod')
    }
		$.ajax({
			method: 'post',
			data: postData,
			url: '/includes/addName.php'
		}).done(function(data){
			try {
				var obj = JSON.parse(data);
				var snackbar = $('.mdl-snackbar');
				if(obj.success == true){
					console.log(obj.log);
				} else {
					console.error(data);
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
  submitAnswers: function (e) {
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
      by: $('body').attr('data-mod')
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

$('.form--rufnamen button.save-all').on('click', events.pushNamesToServer);

$('.form--eigeneFragen ul li button').click(function () {
    events.submitAnswers($(this).prev('.mdl-textfield').children('input').get());
});
$('button.save-all').click(function () {
    events.submitAnswers($(this).prev('ul').children('li').find('input').get());
});

$('.mdl-textfield__input').change(function () {
    $(this).parent().next('button').children('i').text('save')
    $(this).parents('ol').next('button.save-all').prop('disabled', false);
});

$('button.rufnamen__add').on('click', events.appendNew);
