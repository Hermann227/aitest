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
?> 