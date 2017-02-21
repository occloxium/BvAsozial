(function () {
  var url = './users/' + $('main').attr('data-directory') + '/' + $('main').attr('data-username') + '.json',
      result = jQuery.ajax({
          method: 'get',
          url: url,
          dataType: 'json',
          success: function (data) {
            // Eigene Fragen
            var beantwortet = 0;
            for (var frage in data.eigeneFragen) {
              if (data.eigeneFragen[frage].beantwortet){
                beantwortet++;
              }
            }
            pie1.setTotal(data.eigeneFragen.length).setValue(beantwortet);

            var beantwortet = 0;
            for (var frage in data.freundesfragen) {
              if (Object.keys(data.freundesfragen[frage].antworten).length > 0){
                beantwortet++;
              }
            }
            pie2.setTotal(data.freundesfragen.length).setValue(beantwortet);
          }
      });
})();
