var $ = jQuery;

(function () {
	'use strict';
	var checkboard = {
		_data: {
			freundesfragen: {
				max: 4,
				selected: []
			},
			eigeneFragen: {
				max: 4,
				selected: []
			}
		},
		setConfidence: function (s) {
			if (s <= 8) {
				window.Checkboard._data.freundesfragen.max = s;
				window.Checkboard._data.eigeneFragen.max = s;
			} else {
				console.warn(s + ' has to be 8 or less');
			}
			$('.mdl-slider')[0].MaterialSlider.change(s);
		},
		removeQuestion: function (element) {
			if(element.substr(0,1) == "f"){
				var i = window.Checkboard._data.freundesfragen.selected.indexOf(element);
				if(i > -1){
					window.Checkboard._data.freundesfragen.selected.splice(i, 1);
				}
			} else if(element.substr(0,1) == "e") {
				var i = window.Checkboard._data.eigeneFragen.selected.indexOf(element);
				if(i > -1){
					window.Checkboard._data.eigeneFragen.selected.splice(i, 1);
				}
			} else {
				return null;
			}
		},
		getConfidence: function () {
			return (window.Checkboard._data.freundesfragen.max === window.Checkboard._data.eigeneFragen.max ?  window.Checkboard._data.freundesfragen.max : false);
		},
		displayError: function (msg) {
			$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
				message: msg,
				timeout: 2000
			});
		}
	};
	window.Checkboard = checkboard;
}());

$('.fragenkatalog-table td.frage').click(function (e) {
	$(this).parent().find('.mdl-checkbox__input').parent().click();
});

$('.mdl-checkbox__input').on('click', function (e) {
	var c = window.Checkboard;
	if ($(this).prop('checked')) {
		switch($(this).attr('data-category')){
			case 'freundesfragen' :
				if(c._data.freundesfragen.selected.length >= 8){
					c.displayError('Entferne eine andere Frage aus dieser Rubrik, um mehr auswählen zu können');
					$(this).prop('checked', false);
				}	
				else {
					c._data.freundesfragen.selected.push($(this).attr('id'));
					if(c._data.freundesfragen.selected.length > c.getConfidence())
						c.setConfidence(c.getConfidence() + 1);
				}
				break;
			case 'eigeneFragen' :
				if(c._data.eigeneFragen.selected.length >= 8){
					c.displayError('Entferne eine andere Frage aus dieser Rubrik, um mehr auswählen zu können');
					$(this).prop('checked', false);
				}
				else {
					c._data.eigeneFragen.selected.push($(this).attr('id'));
					if(c._data.eigeneFragen.selected.length > c.getConfidence())
						c.setConfidence(c.getConfidence() + 1);
				}
				break;
		}
	} else {
		c.removeQuestion($(this).attr('id'));
	}
});