<?php
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	
	$action = $json->result->action;
	
	switch ($action) {
		case 'weather':
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
			//$newDate = date("d.m.Y", strtotime($originalDate));
			//$date = new DateTime($originalDate);
			//$newDate = $date->format('d.m.Y');
			
			$speech = sprintf("Die Temperatur in %s am %s beträgt %s Grad Celsius.", 
				$json->{'data'}->{'request'}['0']->{'query'}, 
				$originalDate, 
				$json->{'data'}->{'current_condition'}['0']->{'temp_C'} );
			break;

		case 'translate.text':
			$sourceLanguage = $json->result->parameters->lang-from;
			$sourceLanguage = substr($sourceLanguage, 0, 2);
			$targetLanguage = $json->result->parameters->lang-to;
			$targetLanguage = substr($targetLanguage, 0, 2);
			$reqText = $json->result->resolvedQuery;
			$reqText = urlencode(reqText);
			
			$premiumurl = sprintf('https://translate.googleapis.com/translate_a/single?client=gtx&sl=%s&tl=%s&dt=t&q=%s', 
				$sourceLanguage, $targetLanguage, $reqText);

			$json_reply = file_get_contents($premiumurl);

			$json=json_decode($json_reply);
			
			$sourceText = $json[0][0][1];
			$translatedText = $json[0][0][0];
			
			$speech = sprintf("Der Text \"%s\" heißt übersetzt \"%s.\"", 
				$sourceText, 
				$translatedText);
			break;

		default:
			$speech = "Entschuldigung, ich konnte Ihre Frage nicht verstehen.";
			break;
	} 

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