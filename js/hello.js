function isoDateReviver(value) {
    // 2011-05-24 17:51:40.414011

 if (typeof value === 'string') {
    var a = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)(?:([\+-])(\d{2})\:(\d{2}))?Z?$/.exec(value);
      if (a) {
        var utcMilliseconds = Date.UTC(+a[1], a[2]-1 , +a[3], +a[4]-2, +a[5], +a[6]);
        return new Date(utcMilliseconds);
      }
  }
  return value;
}

function timeToOrder() {
    alert("Now the pizzas should be ordered");
}


function timeToGet() {
    alert("Did someone pickup the pizza?");
}


function display_ticker(json) {
    var top_msg = document.getElementById('msg');
    if (json.pickup_time == null) {
	var order = isoDateReviver(json.order_time);	
	top_msg.innerHTML =  "<h2>Order in</h2>";
	$('#countdown').countdown({until: order,
				   compact: true,
				   format: "HMS",
				   description: '',
				   onExpiry: timeToOrder}); 
    } else {
	var pickup = isoDateReviver(json.pickup_time);
	// alert(pickup);
	top_msg.innerHTML =  "<h2>Pickup in</h2>";
	$('#countdown').countdown({until: pickup,
				   compact: true,
				   format: "HMS",
				   description: '',
				   onExpiry: timeToGet}); 
    }

}

$(document).ready(function() {
    
    $.getJSON("ajax.php",
	      { id: pid},
	      display_ticker
	     );
    
});