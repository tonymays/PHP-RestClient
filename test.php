<?php
require_once('rest_client.php');
$c = new RESTClient();
$c->set_option('CURLOPT_CRLF', 'true');
$c->set_option('CURLOPT_CRLF', 'false');
$c->set_option('CURLOPT_CRLF', 'false');
$c->set_option('CURLOPT_HAPROXYPROTOCOL', 'false');
$c->set_option('CURLOPT_HAPROXYPROTOCOL', 'true');
print_r($c->get_options());