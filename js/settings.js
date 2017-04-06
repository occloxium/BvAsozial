/**
 * Front End Scripting for /einstellungen/
 * Alexander Bartolomey - 2017
 * @package BvAsozial 1.2
 */

var $ = jQuery;

$('.form--personal-data .mdl-button').click(function(){
  $.ajax({
    type: 'post',
    url: '/einstellungen/uup.php',
    data: $('.form--personal-data').serialize();
  }).done(function(d){
    try {
      var obj = JSON.parse(d);
      if(!obj.success){
        console.error(obj.message);
      }
    } catch (e){
      console.error(d);
      console.error(e);
    }
  }).fail(function(e){
    console.error(e);
  });
});


$('.form--privacy-settings .mdl-button').click(function(){
  $.ajax({
    type: 'post',
    url: '/einstellungen/up.php',
    data: $('.form--privacy-settings').serialize();
  }).done(function(d){
    try {
      var obj = JSON.parse(d);
      if(!obj.success){
        console.error(obj.message);
      }
    } catch (e){
      console.error(d);
      console.error(e);
    }
  }).fail(function(e){
    console.error(e);
  });
});

$('.form--notification-settings .mdl-button').click(function(){
  $.ajax({
    type: 'post',
    url: '/einstellungen/un.php',
    data: $('.form--notification-settings').serialize();
  }).done(function(d){
    try {
      var obj = JSON.parse(d);
      if(!obj.success){
        console.error(obj.message);
      }
    } catch (e){
      console.error(d);
      console.error(e);
    }
  }).fail(function(e){
    console.error(e);
  });
});
