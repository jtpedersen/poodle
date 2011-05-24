function isoDateReviver(value) {
    // 2011-05-24 17:51:40.414011

 if (typeof value === 'string') {
    var a = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)(?:([\+-])(\d{2})\:(\d{2}))?Z?$/.exec(value);
      if (a) {
        var utcMilliseconds = Date.UTC(+a[1], +a[2] , +a[3], +a[4]-2, +a[5], +a[6]);
        return new Date(utcMilliseconds);
      }
  }
  return value;
}


function display_ticker(json) {
    var test = document.getElementById('countdown');
    
    var t = json.pickup_time;
    test.innerHTML = isoDateReviver(t);
}


$(document).ready(function() {
    var pid = "00966550fc3c84848baf1a55a2386b22";

    $.getJSON("ajax.php",
	      { id: pid},
	      display_ticker
	     );
    
});