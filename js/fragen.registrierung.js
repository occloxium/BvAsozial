var $ = jQuery;
/**
 * Checkboard to keep track of the files the user selected.
 * A users 'confidence' means the maximum amout of questions he's allowed to have. That name is actually quite misleading.
 * the 'selected' arrays shall save the id of the questions selected.
 * the 'removeQuestion' function is working as expected
 * 'displayError' works as expected, if an error is triggered
 */
function Checkboard(){
		this.freundesfragen = {
				max: 8,
				selected: []
	  };
    this.eigeneFragen = {
				max: 8,
				selected: []
		};
		this.removeQuestion = function (element) {
			if(element.substr(0,1) == "f"){
				var i = this.freundesfragen.selected.indexOf(element);
				if(i > -1){
					this.freundesfragen.selected.splice(i, 1);
				}
			} else if(element.substr(0,1) == "e") {
				var i = this.eigeneFragen.selected.indexOf(element);
				if(i > -1){
					this.eigeneFragen.selected.splice(i, 1);
				}
			} else {
				return null;
			}
		};
		this.getConfidence = function () {
			return (this.freundesfragen.max === this.eigeneFragen.max ?  this.freundesfragen.max : false);
		};
		this.displayError = function (msg) {
			$('.mdl-snackbar')[0].MaterialSnackbar.showSnackbar({
				message: msg,
				timeout: 2000
			});
		};
	};
(function(){
  window.Checkboard = new Checkboard();
})();

$('.fragenkatalog-table td.frage').click(function (e) {
	$(this).parent().find('.mdl-checkbox__input').parent().click();
});

$('.mdl-checkbox__input').on('click', function (e) {
	var c = window.Checkboard;
	if ($(this).prop('checked')) {
		switch($(this).attr('data-category')){
			case 'freundesfragen' :
				var ca = c.freundesfragen;
				if(ca.selected.length >= 8){
					c.displayError('Entferne eine andere Frage aus dieser Rubrik, um mehr auswählen zu können');
					$(this).prop('checked', false);
				}
				else {
					ca.selected.push($(this).attr('id'));
				}
				break;
			case 'eigeneFragen' :
				if(c.eigeneFragen.selected.length >= 8){
					c.displayError('Entferne eine andere Frage aus dieser Rubrik, um mehr auswählen zu können');
					$(this).prop('checked', false);
				}
				else {
					c.eigeneFragen.selected.push($(this).attr('id'));
				}
				break;
		}
	} else {
		c.removeQuestion($(this).attr('id'));
	}
});
