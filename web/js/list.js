 $('#reload').click(function() {
     $.get("/refresh", function( data ) {
     $('#demands').html( data );
 });
