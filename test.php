<?php
require_once('rest_client.php');
$c = new RESTClient();
$c->set_header('Content-Type', 'application/json');
$data = <<<DATA
{"username": "root","password": "abc123xyz890"}
DATA;
$response = $c->post("http://45.55.49.63:8080/auth", $data);
$c->set_header('Auth-Token', $response['headers']['auth-token']);
$response = $c->get("http://45.55.49.63:8080/users");
print_r(json_decode($response['response'], true));


/*
// let's get some users now that we have logged in


print_r($c->get_headers());
$c->delete_header('TEST');
print_r($c->get_headers());

$c->set_option(CURLOPT_HEADER, 1);
print_r($c->get_options());
$c->delete_option(CURLOPT_HEADER);
print_r($c->get_options());
*/
