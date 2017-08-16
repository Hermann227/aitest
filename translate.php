<?php
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

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