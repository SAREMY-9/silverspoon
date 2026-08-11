<?php

$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);

var_dump([
    'response' => $response,
    'error' => curl_error($ch),
    'errno' => curl_errno($ch),
    'info' => curl_getinfo($ch),
]);

curl_close($ch);
