<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFH9Goqy9OHIUeCFKjZURHBUuJPDqxaUo"></script>
<script type="text/javascript">

/*
 ** Directions Service script adapted from Google documentation
 ** https://developers.google.com/maps/documentation/javascript/directions
 **
  */
    function initMap() {
    	var directionsDisplay = new google.maps.DirectionsRenderer;
    	var directionsService = new google.maps.DirectionsService;
	var lat = document.getElementById("lat").value;
      	var lng = document.getElementById("lng").value;
      	var mapCenter = new google.maps.LatLng(lat, lng);
      	var mapOptions = {
            	zoom:13,
            	center: mapCenter
      	}
      	var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
      	directionsDisplay.setMap(map);
      	directionsDisplay.setPanel(document.getElementById("directions-panel"));
    }

    function calcRoute(directionsService, directionsDisplay) {
      	var start = document.getElementById("start").value;
      	var end = document.getElementById("end").value;
      	var mode = document.getElementById("mode").value;
     	var request = {
        	origin: start,
        	destination: end,
		travelMode: mode
      	};
      	directionsService.route(request, function(response, status) {
        if (status == 'OK') {
          	directionsDisplay.setDirections(response);
        }
        else {
        	window.alert("Directions request failed due to" + status);
        }
      });
    }
        
    google.maps.event.addDomListener(window, 'load', initMap);

</script>
<p class="credit h4">
    Scroll Down For Directions    
</p>
<div class="table-responsive">
<form role="form" action="myburritos.php" method="POST">
<input type="hidden" name="function" value="add" />
<table class="table table-striped text-left">
    <tbody>
        <tr>
            <td><b> Burrito Place </b></td>
            <td><b> Yelp Rating </b></td>
            <td class="text-center"> <button type="submit" class="btn search-btn btn-sm"> Save </button> </td>
        </tr>
     
   <?php 
        // get geo data for Google Maps API directions service
        $lat = $results["region"]["center"]["latitude"];
        $lng = $results["region"]["center"]["longitude"];
        
        // set the businesses array from rendered results
        $businesses = $results["businesses"];  
        // results counter and business name/location holder arrays
        $count = 1;
        $biz = [];
        $loc = [];
        
        // for each loop to extract the arrays for each of the five returned results
        foreach($businesses as $business):
            // if business is closed, skip forward
            if ($business["is_closed"] == "true") {
                continue;
            }
            // set default (nullified) values for any empty fields to be used in html below
            if (empty($business["name"])) {
                $business["name"] = "Business name not provided";
            }
            if (empty($business["location"]["address"][0])) {
                $business["location"]["address"][0] = "Street Address not provided";
            }
            if (empty($business["location"]["city"])) {
                $business["location"]["city"] = "City not provided";
            }
            if (empty($business["location"]["state_code"])) {
                $business["location"]["state_code"] = "XX";
            }
            if (empty($business["location"]["postal_code"])) {
                $business["location"]["postal_code"] = "00000";
            }
            if (empty($business["display_phone"])) {
                $business["display_phone"] = "Phone Number not provided";
            }    
   ?>
        <tr>
        <td> 
            <address>
            <strong><?= $business["name"] ?> </strong> <br>
            <?= $business["location"]["address"][0] ?> <br>
            <?= $business["location"]["city"] ?>, <?= $business["location"]["state_code"] ?> <?= $business["location"]["postal_code"] ?><br>
            <abbr title="Phone">P:</abbr> <?= $business["display_phone"] ?>
            </address>
        </td>
        <td> <img class="img-responsive" src="<?= $business['rating_img_url'] ?>" /> </td>
        <td class="text-center"> <input type="checkbox" name="<?= $count ?>" value="<?= $business['id'] ?>" aria-label="save me" /> </td>
        </tr>
        <?php 
            $biz[$count] = $business["name"];
            $loc[$count] = $business["location"]["address"][0] . ", " . $business["location"]["postal_code"];
            ++$count ?> 
    <?php endforeach ?>
 
        <tr>
        <td></td>
        <td></td>
        <td class="text-center"> <button type="submit" class="btn search-btn btn-sm"> Save </button> </td>
        </tr>

    </tbody>
</table>
</form>
    <input type="hidden" id="lat" value="<?= $lat ?>" />
    <input type="hidden" id="lng" value="<?= $lng ?>" />
</div>
<div class="dotted-border-search">
<h4> Directions </h4>
<div class="form-inline">
    <div class="form-group">
        <label for="start" class="sr-only">From:</label>
        <input type="text" class="form-control" id="start" placeholder="From" value="<?= $location ?>" onchange="calcRoute();">
    </div>
    <div class="form-group">
        <label for="end" class="sr-only">To:</label>
            <select class="form-control" id="end" onchange="calcRoute();">
                <option value="<?= $loc["1"] ?>"><?= $biz["1"] ?></option>
                <option value="<?= $loc["2"] ?>"><?= $biz["2"] ?></option>
                <option value="<?= $loc["3"] ?>"><?= $biz["3"] ?></option>
                <option value="<?= $loc["4"] ?>"><?= $biz["4"] ?></option>
                <option value="<?= $loc["5"] ?>"><?= $biz["5"] ?></option>
            </select>
    </div>
    <div class="form-group">
        <label for="mode" class="sr-only">How:</label>
            <select class="form-control" id="mode" onchange="calcRoute();">
                <option value="DRIVING">Drive</option>
                <option value="WALKING">Walk</option>
                <option value="TRANSIT">Public Transit</option>
            </select>
    </div>  
</div>     
</div>

<div id="directions-panel"></div>
<div id="map-canvas"></div>

