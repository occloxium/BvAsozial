/**
* <h1>Notification class</h1>
* To be used to output graphical callbacks being fired from user interactions
*/
(function(){
	'use strict';
	var notification = function ( msg, code ) {
				var COLORS = {
					success: '#00b84f',
					warning: '#ffc107',
					error: '#F44336'
				};
				$('body').append('<div class="nll-msg"></div>');
				$('.nll-msg')
					.text(msg)
					.addClass('nll-msg-' + code)
					.css('background-color', COLORS[code]);
				setTimeout(function(){
					$('.nll-msg').fadeOut(500, function(){
						$(this).detach();
					});
				}, 4500);
			};
	window.notification = notification;
})();
