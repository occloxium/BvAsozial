(function () {
    'use strict';
    var url = './users/' + $('main').attr('data-directory') + '/' + $('main').attr('data-username') + '.json',
        result = jQuery.ajax({
            method: 'get',
            url: url,
            dataType: 'json',
            success: function (data) {
                // Eigene Fragen
                var eigeneFragen = data.eigeneFragen,
                    anzahlFragen = eigeneFragen.length,
                    unbeantwortet = 0;
                for (var frage in eigeneFragen)
                    if (frage.beantwortet) 
                        unbeantwortet++;
                pie1.setTotal(anzahlFragen).setValue(anzahlFragen - unbeantwortet);

                // Freundesfragen
                // Korrektur relativ zur Anzahl der Freunde muss durchgeführt werden
                var freundesfragen = data.freundesfragen,
                    unbeantwortet = 0,
                    anzahlFragen = freundesfragen.length;

                for (var frage in freundesfragen)
                    if (frage.beantwortet)
                        unbeantwortet++;
                pie2.setTotal(anzahlFragen).setValue(anzahlFragen - unbeantwortet);
            }
        });
})();
