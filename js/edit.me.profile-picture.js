$('form button').click(function(){
    $('input.file').click();
});
$('input.file').change(function(){
    $('form button').prop('disabled', true);
    var prog = $('<div id="p2" class="mdl-progress mdl-js-progress mdl-progress--indeterminate"></div>');
    window.componentHandler.upgradeElement(prog[0]);
    $('form').append(prog);
    $('form').trigger('submit');
});