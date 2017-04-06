(function () {
  $.ajax({
    method: 'get',
    url: '/includes/getUserFile.php',
    dataType: 'json',
  }).done(function(data){
    if(data.success){
      data = JSON.parse(data.json);
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
    } else {
      console.error(data);
    }
  }).fail(function(e){
    console.error(e);
  });
})();
