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

		jQuery.post('../l8.php?controller=delayController&action=filterDelay', data, function(response) {

      data1 = JSON.parse(response);
      $('#content_delays').html('<table border=1><tr><td>Name</td><td>Delay (min)</td><td>Cause</td><td>Date and Time</td></tr></table>');

      for (var i = 0; i < data1.length; i++) {
        console.log(data1[i]);
       $("#content_delays table tr:last").after('<tr><td>'+data1[i]['user_nicename']+'</td><td>'+data1[i]['time']+'</td><td>'+data1[i]['cause']+'</td><td>'+data1[i]['today']+'</td></tr>');
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
