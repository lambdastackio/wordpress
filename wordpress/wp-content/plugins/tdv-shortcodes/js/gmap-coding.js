function codeAddress(geocoder, theMap, address) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			theMap.setCenter(results[0].geometry.location);
			var image = new google.maps.MarkerImage("../wp-content/plugins/tdv-shortcodes/img/location-pin.png", null, null, null, new google.maps.Size(32, 32));
			var beachMarker = new google.maps.Marker({
				map: theMap,
				icon: image,
				position: results[0].geometry.location
			});

		} else {
			alert('Geocode was not successful for the following reason: ' + status);
		}
	});
}