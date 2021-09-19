<?php
require_once('rest_client.php');

// setup default headers and options
$headers = [
	'Content-Type' => 'application/json',
];
$options = [
	CURLOPT_RETURNTRANSFER => 1,
];

// instantiate the rest class
$c = new RESTClient();

$c->set_option(CURLOPT_RETURNTRANSFER, 1);
$c->set_header('Content-Type', 'application/json');

// let's login to our backend
$data = <<<DATA
{
	"username": "root",
	"password": "abc123xyz890"
}
DATA;
$response = $c->post("http://45.55.49.63:8080/auth", $data);
print_r($response);

// let's get some users now that we have logged in
$c->set_header('Auth-Token', $response['headers']['auth-token']);
$response = $c->get("http://45.55.49.63:8080/users");
print_r(json_decode($response['response'], true));

