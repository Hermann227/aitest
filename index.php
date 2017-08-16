<?php
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$loc = $json->result->parameters->address->city;
	$date = $json->result->parameters->date-time;
	
	//Can be city,state,country, zip/postal code, IP address, longtitude/latitude. If long/lat are 2 elements, they will be assembled. IP address is one element.
	$api_key="b43261f1ced54ae6b1e95314171608";		//should be embedded in your code, so no data validation necessary, otherwise if(strlen($api_key)!=24)

	//Premium API
	$premiumurl=sprintf('http://api.worldweatheronline.com/premium/v1/weather.ashx?key=%s&q=%s&date=%s&format=json', 
		$api_key, $loc, $date);
		
	$json_reply = file_get_contents($premiumurl);

	$json=json_decode($json_reply);
	
	$originalDate = $json->{'data'}->{'weather'}['0']->{'date'};
	$newDate = date("d.m.Y", strtotime($originalDate));
	
	$speech = sprintf("Die Temperatur in %s am %s beträgt %s Grad Celsius.", 
		$json->{'data'}->{'request'}['0']->{'query'}, 
		$newDate, 
		$json->{'data'}->{'current_condition'}['0']->{'temp_C'} );

	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $speech;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}


?> 