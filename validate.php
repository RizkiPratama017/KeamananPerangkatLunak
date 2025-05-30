<?php

$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,[
    'secret' => '6LcKlE4rAAAAAJE1BiW3l60SIghzFOOxECw8q4V_',
    'response' => $_POST['g-recaptcha-response'],
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = json_decode(curl_exec($ch));
return $response->success;
 