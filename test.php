<?php
require_once('rest_client.php');
$headers = [
	'Content-Type'=>'application/json',
];
$options = [
	'CURLOPT_RETURNTRANSFER' => 'true',
];
$c = new RESTClient($headers, $options);
print_r($c->get_options());
print_r($c->get_headers());
