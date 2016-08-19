<div class="row dotted-border">
    <div class="col-xs-12 col-sm-12 col-md-12 h4 search-label">
        Where do you want to eat your burrito?
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <form class="form-inline" role="form" action="results.php" method="POST">
            <div class="form-group">
                <label class="sr-only" for="location"> Location </label>
                <input class="form-control" type="text" name="location" placeholder="City & State, or ZIP"/>
                <input type="hidden" name="geolocation" value="false" />
                <input type="hidden" name="lat" />
                <input type="hidden" name="lng" />
            </div>
		    <button class="btn search-btn" type="submit" name="submit-location">GO</button>
		    <button class="btn search-btn" type="button" name="submit-geolocation" onclick="geoLocateMe()"><img height="20px" src="img/geolocate.png" alt="Locate Me"/></button>
        </form>
    </div>
</div>

<script type="text/javascript">
/*
 **  Script for using the Geolocation Web API to Submit User's Location to Yelp,
  *  Based on an example for geolocation provided by MDN here:
  *  https://developer.mozilla.org/en-US/docs/Web/API/Geolocation/Using_geolocation.
  *  
  */
function geoLocateMe() {
	// declare options for geolocation API
	var options = {
	  enableHighAccuracy: true,
	  timeout: 10000,
	};
	
	// delcare vars for POST data 	    
    var lat = null;
    var lng = null;
    
	function post (lat, lng) {
	    var form = document.createElement("form");
	    form.setAttribute("method", "post");
	    form.setAttribute("action", "results.php");

	    var inputOne = document.createElement("input");
	    inputOne.setAttribute("type", "hidden");
	    inputOne.setAttribute("name", "lat");
	    inputOne.setAttribute("value", lat);
	    form.appendChild(inputOne);
	    
	    var inputTwo = document.createElement("input");
	    inputTwo.setAttribute("type", "hidden");
	    inputTwo.setAttribute("name", "lng");
	    inputTwo.setAttribute("value", lng);
	    form.appendChild(inputTwo);
	    
	    var inputThree = document.createElement("input");
	    inputThree.setAttribute("type", "hidden");
	    inputThree.setAttribute("name", "geolocation");
	    inputThree.setAttribute("value", "true");
	    form.appendChild(inputThree);
	    
	    var inputFour = document.createElement("input");
	    inputFour.setAttribute("type", "hidden");
	    inputFour.setAttribute("name", "location");
	    inputFour.setAttribute("value", "false");
	    form.appendChild(inputFour);
	    
	    document.body.appendChild(form);
	    form.submit();
	  };

    // function for success in obtaining geodata
	function success(pos) {
	
	  // location values
	  var crd = pos.coords;

	  // call POST function to submit data
	  post(crd.latitude, crd.longitude); 
	};

	function error(err) {
	  alert('ERROR(' + err.code + '): ' + err.message);
	};

	navigator.geolocation.getCurrentPosition(success, error, options);
};
</script>
