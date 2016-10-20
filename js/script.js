(function($) {
  $("#delay_form input[type=radio]").on("change", function(){
    if($('input[type=radio][value=RATP]:checked').length){
      $('#RATP').show();
      $('#other').hide();
    }else{
      $('#other').show();
      $('#RATP').hide();
    }
  });

  $("#filter select").on("change", function(){

    var filter = $("#filter select").val();

    var data = {
			'action': 'filter',
			'id': filter
		};

		jQuery.post(ajaxurl, data, function(response) {
      console.log(response)

      data1 = JSON.parse(response);
      $('#content_delays').html('<table border=1><tr><td>Name</td><td>Delay (min)</td><td>Cause</td><td>Detail</td><td>Type</td><td>Line</td><td>Date and Time</td></tr></table>');

      for (var i = 0; i < data1.length; i++) {
        content = '<tr><td>'+data1[i]["user_nicename"]+'</td><td>'+data1[i]["time"]+'</td><td>'+data1[i]["cause"]+'</td><td>'+data1[i]["detail"]+'</td><td>'+data1[i]["type"]+'</td><td>'+data1[i]["line"]+'</td><td>'+data1[i]["today"]+'</td></tr>'
       $("#content_delays table tr:last").after(content);
      }

		});

  });

  $("#traffic button").on("click", function(){

    var type = $(this).attr('class');
    var line = $(this).attr('id');

    var linesUrl = 'http://api-ratp.pierre-grimaud.fr/v2/traffic/'+type+'/'+line;

     var data = {
       'action': 'traffic'
     };

     jQuery.post(linesUrl, data, function(response) {
         $('#result').html(type +" "+ line +" : "+ response["response"]["message"]);
    });
  });


})( jQuery );
