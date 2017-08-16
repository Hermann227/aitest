<?php
//~ http://api.worldweatheronline.com/free/v1/weather.ashx?key=xxxxxxxxxxxxxxxxx&q=SW1&num_of_days=3&format=json

//Minimum request
//Can be city,state,country, zip/postal code, IP address, longtitude/latitude. If long/lat are 2 elements, they will be assembled. IP address is one element.
$loc="Cologne";		//data validated in foreach. 
$api_key="b43261f1ced54ae6b1e95314171608";		//should be embedded in your code, so no data validation necessary, otherwise if(strlen($api_key)!=24)
$num_of_days=1;					//data validated in sprintf
$date="2017-08-16";

//To add more conditions to the query, just lengthen the url string
$basicurl=sprintf('http://api.worldweatheronline.com/free/v1/weather.ashx?key=%s&q=%s&date=%s&format=json', 
	$api_key, $loc, $date);

print $basicurl . "<br />\n";

//Premium API
$premiumurl=sprintf('http://api.worldweatheronline.com/premium/v1/weather.ashx?key=%s&q=%s&date=%s&format=json', 
	$api_key, $loc, $date);
	
print $premiumurl . "<br />\n";

$json_reply = file_get_contents($premiumurl);

print "<pre>";
print_r($json_reply);
print "</pre>";

$json=json_decode($json_reply);
$json_webhook_reply = printf("<p>Die Temperatur in %s am %s beträgt %s Grad Celsius.</p>", 
	$json->{'data'}->{'request'}['0']->{'query'}, 
	$json->{'data'}->{'weather'}['0']->{'date'}, 
	$json->{'data'}->{'current_condition'}['0']->{'temp_C'} );

print $json_webhook_reply;

print "<pre>";
print_r($json);
print "</pre>";
?> 