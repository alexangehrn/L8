(function($) {
  console.log('hello');

  $("#filter select").on("change", function(){
    var filter = $("#filter select").val();
     $.ajax({
       url:'../l8.php?controller=delayController&action=filterDelay',
       type:'POST',
       data : { id: filter },
       success: function(data){
         console.log(data);
       }
     });
    return false;
  });


})( jQuery );
