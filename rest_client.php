<?php
declare(strict_types = 1);

class RESTClient {
	private $request = [
		'url'=>'',
		'method'=>'',
		'headers'=>[],
		'options'=>[],
		'body'=>'',
	];

	private $response = [
		'headers'=>[],
		'body'=>'',
		'http_code'=>404,
	];

	public function __construct() {}
	public function __destruct() {}

	// actions

	public function exec() {

	}

	public function post() {

	}

	public function patch() {

	}

	public function put() {

	}

	public function get() {

	}

	public function head() {

	}

	public function options() {

	}

	// getters

	final public function get_request() : array {
		return $this->request;
	}

	final public function get_request_url() : string {
		return $this->request['url'];
	}

	final public function get_request_method() : string {
		return $this->request['method'];
	}

	final public function get_request_headers() : array {
		return $this->request['headers'];
	}

	final public function get_request_options() : array {
		return $this->request['options'];
	}

	final public function get_request_body() : string {
		return $this->request['body'];
	}

	final public function get_response() : array {
		return $this->response;
	}

	final public function get_response_headers() : array {
		return $this->response['headers'];
	}

	final public function get_response_body() : string {
		return $this->response['body'];
	}

	final public function get_response_http_code() : int {
		return $this->response['http_code'];
	}




	// clear
	public function clear_request() : void {

	}




	// privates

	private function is_curl_enabled() :bool {
		return (
			function_exists('curl_init') &&
			function_exists('curl_setopt') &&
			function_exists('curl_exec') &&
			function_exists('curl_close')
		);
	}
}