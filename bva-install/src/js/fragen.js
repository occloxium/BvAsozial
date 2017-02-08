var $ = jQuery;
(function () {
    'use strict';
    $("textarea").autogrow({onInitialize: true});
}());
var FUNCTIONS_ = {
    submitAnswers: function (buttons) {
        'use strict';
        var fragen = [];
        buttons.forEach(function (el, index) {
            fragen.push({
                antwort: $(el).prev('.mdl-textfield').children('.mdl-textfield__input').val(),
                von: $(el).parents('main').attr('data-username'),
                frageID: $(el).attr('data-item'),
                type: $(el).attr('data-category'),
                "for": $(el).attr('data-for')
            });
        });
        $.ajax({
            method: 'post',
            url: '/includes/answerQuestions.php',
            data: {
                "fragen": fragen,
                "uid": $(buttons[0]).parents('main').attr('data-username')
            }
        }).done(function (d) {
            try {
                var obj = JSON.parse(d);
                if (obj.success) {
                    buttons.forEach(function (el) {
                        $(el).children('i').text('done_all');
                    });
                    $('button.save-all').prop('disabled', true);
                } else {
                    window.console.error(d);
                }
            } catch (error) {
                window.console.error(error);
            }
        }).fail(function (e) {
            window.console.error(e);
        });
    }
};
$('li button').click(function () {
    FUNCTIONS_.submitAnswers($(this).get());
});
$('button.save-all').click(function () {
    FUNCTIONS_.submitAnswers($(this).prev('ol').children('li').find('button').get());
});
$('.mdl-textfield__input').change(function () {
    $(this).parent().next('button').children('i').text('save')
    $(this).parents('ol').next('button.save-all').prop('disabled', false);
});
