<?php
$premiumurl = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=en&dt=t&q=Heute;";

$json_reply = file_get_contents($premiumurl);

print "<pre>";
print_r($json_reply);
print "</pre>";

$json=json_decode($json_reply);

print "<pre>";
print_r($json);
print "</pre>";

$sourceText = $json[0][0][1];
$translatedText = $json[0][0][0];

$speech = sprintf("Der Text %s heißt übersetzt %s", 
		$sourceText, 
		$translatedText);

print $speech;
?> 